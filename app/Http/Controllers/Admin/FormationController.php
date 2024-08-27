<?php

namespace App\Http\Controllers\Admin;;

use App\Enum\PayementTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\FormationRequest;
use App\Models\Formation;
use App\Models\Order;
use App\Models\Quiz;
use App\Models\Product;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FormationController extends Controller
{
    public StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    function index()
    {
        $formations = Formation::with('quizzes')->get();
        return view('admin.formations.index', ['formations' => $formations]);
    }

    function create()
    {
        $quiz = Quiz::where("payement_type", PayementTypeEnum::FREE)->get();
        return view('admin.formations.create', ['quiz' => $quiz]);
    }
    function store(FormationRequest $request)
    {
        $quizzes = $request->select_quizzes;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('images/', $filename);
        }

        $price_token = null;
        $product_token = null;

        if ($request->payment_type == PayementTypeEnum::PAYED->value) {
            $product_stripe = $this->stripeService->createProduct($request->title, $request->price);
            $price_token = $product_stripe->default_price;
            $product_token = $product_stripe->id;
        }

        $formation = Formation::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->hasFile('image') ? $filename : "blank.png",
            'video' => $request->video ? 'videos/' . $request->video : null,
            'payment_type' => $request->payment_type,
            'price' => $request->price,
            'price_token' => $price_token,
            'product_token' => $product_token
        ]);


        $formation->quizzes()->attach($quizzes);

        $product = new Product([]);
        $formation->product()->save($product);

        return redirect()->back()->with('status', 'Formation created successfully');
    }


    public function edit(Formation $formation)
    {
        $quiz = Quiz::where("payement_type", PayementTypeEnum::FREE)->get();
        return view('admin.formations.update', compact('formation', 'quiz'));
    }

    function update(FormationRequest $request, Formation $formation)
    {
        $quizzes = $request->select_quizzes;

        if ($request->hasFile('image')) {
            $destination = 'images/' . $formation->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images/', $filename);
        }

        $price_token = null;
        $product_token = null;

        if (in_array($request->payment_type, [PayementTypeEnum::FREE->value, PayementTypeEnum::NONAPPLICABLE->value])) {
            $request->merge([
                'price' => null,
            ]);
            if ($formation->product_token) {
                $this->stripeService->deleteProduct($formation->product_token, $formation->price_token);
            }
        } else {
            if (in_array($formation->payment_type, [PayementTypeEnum::FREE->value, PayementTypeEnum::NONAPPLICABLE->value])) {
                $product = $this->stripeService->createProduct($request->title, $request->price);
                $price_token = $product->default_price;
                $product_token = $product->id;
            } elseif ($request->price != $formation->price) {
                $price = $this->stripeService->updateProductPrice($formation->product_token, $request->price);
                $price_token = $price->id;
                $product_token = $formation->product_token;
                $this->stripeService->updateProduct($product_token, $price_token);
                $formation->product?->orders()?->update(['current_price' => $request->price]);
            } else {
                $product_token = $formation->product_token;
                $price_token = $formation->price_token;
            }
        }

        $formation->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $request->hasFile('image') ? $filename : $formation->image,
            'video' => $request->video ? 'videos/' . $request->video : null,
            'payment_type' => $request->payment_type,
            'price' => $request->price,
            'price_token' => $price_token,
            'product_token' => $product_token
        ]);

        $formation->quizzes()->sync($quizzes);

        return  redirect()->back()->with('status', 'Formation updated successfully');
    }

    function destroy(Formation $formation)
    {
        $formation->quizzes()->detach();
        $formation->delete();
        $formation->product()->delete();
        if ($formation->product_token) {
            $this->stripeService->deleteProduct($formation->product_token, $formation->price_token);
        }
        return  redirect()->back()->with('status', 'Formation deleted successfully');
    }


    public function show(Formation $formation)
    {
        return view('admin.formations.show', ['formation' => $formation]);
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
        if ($nextQuiz) {
            return redirect()->route('quiz', ['slug' => $nextQuiz['quiz']->slug]);
        }
        return redirect()->route('formation.index');
    }
}
