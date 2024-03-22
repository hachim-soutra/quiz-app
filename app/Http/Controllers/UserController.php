<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Order;
use App\Models\QuestionsCategorization;
use App\Models\QuizTheme;
use App\Models\User;
use App\Services\StripeService;
use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Cashier\Cashier;

class UserController extends Controller
{
    public StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index()
    {
        $answer = Auth::user()->answers()->latest()->limit(5)->get();
        $quiz = Quiz::latest()->get();
        $categories = QuestionsCategorization::all();
        $xValues = [];
        $yValues = [];
        $barColors = [];
        foreach ($categories as $categorie) {
            $xValues[] = $categorie->name;
            $barColors[] = $categorie->color;
            $yValues[] = $categorie->questions->count();
        }
        return view('client.home', ['answer' => $answer, 'quiz' => $quiz, 'xValues' => $xValues, 'yValues' => $yValues, 'barColors' => $barColors]);
    }

    public function settings()
    {
        $orders = auth()->user()->orders()->with('quiz')->latest()->get();
        return view('client.account', ['orders' => $orders]);
    }

    public function checkout(Request $request, $price_token, $quiz_id)
    {
        $session = $request->user()->checkout($price_token, [
            'success_url' => route('checkout-success') . '?token={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout-cancel'),
            'mode' => 'payment',
            'metadata' => [
                'id' => auth()->id(),
                'quiz_id' => $quiz_id,
            ],
        ]);

        Order::create([
            'session_id' => $session->id,
            'quiz_id' => $session->metadata['quiz_id'],
            'client_id' => $session->metadata['id'],
            'status' => $session->payment_status,
            'amount_stripe' => $session->amount_total / 100,
            'current_price' => $session->amount_total / 100,
            'currency' => $session->currency
        ]);

        return redirect($session->url);
    }

    public function checkoutSuccess(Request $request)
    {
        $session = Cashier::stripe()->checkout->sessions->retrieve(
            $request->token,
            []
        );
        Order::where('session_id', $session->id)->where('status', 'unpaid')->update([
            'status' => 'paid',
        ]);
        $quiz = Quiz::find($session->metadata['quiz_id']);
        return view('checkout.success', ['quiz' => $quiz]);
    }

    public function checkoutCancel()
    {
        return redirect()->route('client.home')->with('status', 'Your payment is incomplet');
    }

    public function quizzes()
    {
        $quizzes = Quiz::OrderBy('payement_type')->with(['orders' => function ($query) {
            $query->where('client_id', auth()->id())->where('status', 'paid');
        }])->get();
        return view('client.quizzes', ['quizzes' => $quizzes]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'min:6|confirmed',
        ]);
        auth()->user()->update([
            'password' => Hash::make($request->password)
        ]);
        return redirect()->back()->with('status', 'Password Updated successfully.');
    }

    public function answers()
    {
        $answer = Auth::user()->answers()->latest()->get();
        return view('client.answers')->with(["answers" => $answer]);
    }

    public function edit()
    {
        return view('client.edit');
    }

    public function saveUpdatedProfil(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:50',
            'email' => 'email',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('images/', $filename);
        } else {
            $filename = auth()->user()->image;
        }

        auth()->user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $filename,
        ]);

        return redirect()->back()->with('status', 'Profile Updated');
    }

    public function updatePasswordView()
    {
        return view('client.update_password');
    }

    public function destroy(Request $request)
    {
        $answer = Answer::whereIn('id', $request->item)->get();
        foreach ($answer as $item) {
            $item->delete();
        }
        return response()->json(['message' => 'Answer(s) deleted Successfully']);
    }
}
