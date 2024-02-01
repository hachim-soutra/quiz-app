<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Order;
use App\Models\QuizTheme;
use App\Models\User;
use App\Services\StripeService;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class UserController extends Controller
{
    public StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
        // elaaaaaaach
    }
    public function index()
    {
        return view('client.home');
    }

    public function settings()
    {
        return view('client.account');
    }

    public function checkout(Request $request, $price_token, $quiz_id)
    {
        $session = $request->user()->checkout($price_token, [
            'success_url' => route('checkout-success').'?token={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout-cancel'),
            'mode' => 'payment',
            'metadata' => [
                'id' => auth()->id(),
                'quiz_id' => $quiz_id,
            ],
        ]);

        $quiz = Quiz::where('id', $session->metadata['quiz_id'])->first();
        Order::create([
            'session_id' => $session->id,
            'quiz_id' => $session->metadata['quiz_id'],
            'client_id' => $session->metadata['id'],
            'status' => $session->payment_status,
            'amount_stripe' => $session->amount_total / 100,
            'current_price' => $quiz->price,
            'currency' => $session->currency
        ]);

        return redirect($session->url);
    }

    public function checkoutSuccess(Request $request)
    {
        $session = Cashier::stripe()->checkout->sessions->retrieve(
            $request->token,[]);
        Order::where('session_id',$session->id)->where('status','unpaid')->update([
            'status' => 'paid',
        ]);
        $quiz = Quiz::where('id',$session->metadata['quiz_id'])->first();
        return view('checkout.success',['quiz' => $quiz]);
    }
    public function checkoutCancel()
    {
        return redirect()->route('client.home')->with('status', 'Your payment is incomplet');
    }

    public function quizzes()
    {
        $quizzes = Quiz::OrderBy('payement_type')->with(['orders' => function ($query) {
            $query->where('client_id', auth()->id())
            ->where('status', 'paid');
        }])->get();
        return view('client.quizzes', ['quizzes' => $quizzes]);
    }

    public function updateAccount(User $user, Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'email',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'updated_at' => now()
        ]);
        return redirect()->back()->with('status', 'Profile Updated');
    }

    public function answers()
    {
        $answer = Answer::with("quiz")->where('email', Auth::user()->email)->get();
        return view('client.answers')->with(["answers" => $answer]);
    }

    public function destroy(Request $request)
    {
        $answer = Answer::whereIn('id', $request->item)->get();
        foreach ($answer as $item) {
            $item->delete();
        };
        return response()->json(['message' => 'answer(s) deleted Successfully'], 200);
    }
}
