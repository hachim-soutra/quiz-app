<?php

namespace App\Http\Controllers;

use App\Enum\PayementTypeEnum;
use App\Models\Answer;
use App\Models\Formation;
use App\Models\Order;
use App\Models\Promo;
use App\Models\Product;
use App\Models\QuestionsCategorization;
use App\Models\Quiz;
use App\Models\QuizTheme;
use App\Models\Settings;
use App\Models\User;
use App\Services\StripeService;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use PDF;
use Dompdf\Options;
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

    public function promos()
    {
        $promos = Promo::where('active', true)->with(['product.orders' => function ($query) {
            $query->where('client_id', auth()->id())->where('status', 'paid');
        }])->get();
        return view('client.promos', ['promos' => $promos]);
    }

    public function formations()
    {
        $formations = Formation::all();
        return view('client.formations', ['formations' => $formations]);
    }

    public function show(Formation $formation)
    {
        return view('client.video_formation', ['formation' => $formation]);
    }

    // show first quiz in the collection
    public function showQuiz($id)
    {
        $formation = Formation::with('quizzes')->findOrFail($id);
        $quiz = $formation->getQuizzesByIndex()->first();
        session(['formation' => $formation]);
        return redirect()->route('quiz', ['slug' => $quiz['quiz']->slug]);
    }

    public function showNextQuiz($id)
    {
        $formation = session('formation');
        $previousQuiz = $formation->getQuiz($id);
        $nextQuiz = $formation->getQuizzesByIndex()->sortBy('index')->filter(function ($item) use ($previousQuiz) {
            return $item['index'] > $previousQuiz['index'];
        })
        ->first();
        if ($nextQuiz)
        {
            return redirect()->route('quiz', ['slug' => $nextQuiz['quiz']->slug]);
        }
            return redirect()->route('client.formations');
    }

    public function settings()
    {
        $orders = auth()->user()->orders()->with('quiz')->latest()->get();
        return view('client.account', ['orders' => $orders]);
    }

    public function checkout(Request $request, $price_token, $product_id, $product_type, $query)
    {
        $session = $request->user()->checkout($price_token, [
            'success_url' => route('checkout-success') . '?token={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout-cancel'),
            'mode' => 'payment',
            'metadata' => [
                'id' => auth()->id(),
                'product_id' => $product_id,
                'product_type' => $product_type,
                'query' => $query
            ],
        ]);

        Order::create([
            'session_id' => $session->id,
            'product_id' => $session->metadata['product_id'],
            'product_type' => $session->metadata['product_type'],
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
        $product = Product::where('id', $session->metadata['product_id'])->where('productable_type', $session->metadata['product_type'])->firstOrFail();
        if ($product->productable_type == Quiz::class) {
            $quiz = Quiz::find($product->productable->id);
            return view('checkout.success', ['quiz' => $quiz]);
        } else {
            $promo = Promo::find($product->productable->id);
            $query = $session->metadata['query'];
            return view('checkout.success', ['promo' => $promo, 'query' => $query]);
        }
    }

    public function checkoutCancel()
    {
        return redirect()->route('client.home')->with('status', 'Your payment is incomplet');
    }

    public function quizzes(Request $request)
    {
        // to get quizzes from their promo
        if ($request->query('ids')) {
            $quizzes = Quiz::where('payement_type', '!=', PayementTypeEnum::NONAPPLICABLE->value)->whereIn('id', $request->query('ids'))->get();
        }
        // get just quizzes payed by the client
        else if ($request->query("type") == 'payed') {
            $ordersIds = auth()->user()->orders()->where('status', 'paid')->pluck('product_id')->toArray();
            $quizzes = Quiz::with('product')->whereHas('product', function ($xx) use ($ordersIds) {
                $xx->produwhereIn('id', [39]);
            })->get();

        }
        // get all quizzes free, payed and paid
        else if (!$request->query("type")) {
            $quizzes = Quiz::where('payement_type', '!=', PayementTypeEnum::NONAPPLICABLE->value)->OrderBy('payement_type')->with(['product.orders' => function ($query) {
                $query->where('client_id', auth()->id())->where('status', 'paid');
            }])->get();
        }
        // get free or paid quizzes (it's depend on query sended)
        else {
            $quizzes = Quiz::where('payement_type', '!=', PayementTypeEnum::NONAPPLICABLE->value)->where('payement_type', 'like', request()->query("type"))->OrderBy('payement_type')->with(['product.orders' => function ($query) {
                $query->where('client_id', '!=', auth()->id());
            }])->get();
        }

        $orders_promos = Order::where('status', 'paid')->where('product_type', Promo::class)->pluck('product_id')->toArray();
        $total_quizzes = Quiz::count();
        return view('client.quizzes', ['quizzes' => $quizzes, 'total_quizzes' => $total_quizzes, 'orders_promos' => $orders_promos]);
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

    public function viewPDF($token)
    {
        $answer = Answer::whereToken($token)->with(['quiz', 'quiz.questions', 'quiz.questions.question', 'quiz.questions.question.question_type'])->firstOrFail();
        // To replace the target tag with value
        $below_target_text = Settings::where('name', 'below target text recap')->first();
        $above_target_text = Settings::where('name', 'above target text recap')->first();
        $count = 0;
        $terms[] = $answer->target . "%";
        $below_target = preg_replace_callback('/\{{2}(.*?)\}{2}/', function ($match) use (&$count, $terms) {
            $return = !empty($terms[$count]) ? $terms[$count] : '';
            $count++;
            return $return;
        }, $below_target_text->value);
        $above_target = preg_replace_callback('/\{{2}(.*?)\}{2}/', function ($match) use (&$count, $terms) {
            $return = !empty($terms[$count]) ? $terms[$count] : '';
            $count++;
            return $return;
        }, $above_target_text->value);

        return view('pdf.quizRecap', ['answer' =>  $answer, 'below_target' => $below_target, 'above_target' => $above_target]);
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $pdf = PDF::loadView('pdf.quizRecap', ['answer' =>  $answer, 'below_target' => $below_target, 'above_target' => $above_target])
            ->setPaper('a4', 'portrait')
            ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->stream();
    }

    // public function downloadPDF($token)
    // {
    //     $answer = Answer::whereToken($token)->with(['quiz', 'quiz.questions', 'quiz.questions.question', 'quiz.questions.question.question_type'])->firstOrFail();
    //     // To replace the target tag with value
    //     $below_target_text = Settings::where('name', 'below target text recap')->first();
    //     $above_target_text = Settings::where('name', 'above target text recap')->first();
    //     $count = 0;
    //     $terms[] = $answer->target . "%";
    //     $below_target = preg_replace_callback('/\{{2}(.*?)\}{2}/', function ($match) use (&$count, $terms) {
    //         $return = !empty($terms[$count]) ? $terms[$count] : '';
    //         $count++;
    //         return $return;
    //     }, $below_target_text->value);
    //     $above_target = preg_replace_callback('/\{{2}(.*?)\}{2}/', function ($match) use (&$count, $terms) {
    //         $return = !empty($terms[$count]) ? $terms[$count] : '';
    //         $count++;
    //         return $return;
    //     }, $above_target_text->value);

    //     $pdf = PDF::loadView('pdf.quizRecap', ['answer' =>  $answer, 'below_target' => $below_target, 'above_target' => $above_target ])
    //     ->setPaper('a4', 'portrait');

    //     return $pdf->download('quiz-recap.pdf');
    // }
}
