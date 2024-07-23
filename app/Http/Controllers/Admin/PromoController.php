<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PayementTypeEnum;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PromoRequest;
use App\Models\Product;
use App\Models\Promo;
use App\Services\StripeService;
use App\Models\Quiz;
use Illuminate\Support\Facades\File;

class PromoController extends Controller
{
    public StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }
    public function index()
    {
        $promos = Promo::with("quizzes")->get();
        $quiz = Quiz::where("payement_type", PayementTypeEnum::PAYED)->get();

        return view('admin.promos.index', compact('promos', 'quiz'));
    }

    public function create()
    {
        $quiz = Quiz::where("payement_type", PayementTypeEnum::PAYED)->get();
        return view('admin.promos.create', compact('quiz'));
    }

    public function store(PromoRequest $request)
    {
        $quizzes = $request->select_quizzes;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extention = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $file->move('images/', $filename);
        }

        $product_stripe = $this->stripeService->createProduct($request->title, $request->price);
        $price_token = $product_stripe->default_price;
        $product_token = $product_stripe->id;

        $promo = Promo::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'active' => $request->has('active') ? true : false,
            'image' => $request->hasFile('image') ? $filename : "blank.png",
            'price_token' => $price_token,
            'product_token' => $product_token
        ]);


        $promo->quizzes()->attach($quizzes);

        $product = new Product([]);
        $promo->product()->save($product);

        return redirect()->back()->with('status', 'Promotion created successfully');
    }

    public function edit(Promo $promo)
    {
        $quiz = Quiz::where("payement_type", PayementTypeEnum::PAYED)->get();
        return view('admin.promos.update', compact('promo','quiz'));
    }

    public function update(PromoRequest $request, Promo $promo)
    {
        $quizzes = $request->select_quizzes;

        if ($request->hasFile('image')) {
            $destination = 'images/' . $promo->image;
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

        if ($request->price != $promo->price) {
            $price = $this->stripeService->updateProductPrice($promo->product_token, $request->price);
            $price_token = $price->id;
            $product_token = $promo->product_token;
            $this->stripeService->updateProduct($product_token, $price_token);
            $promo->product->orders()->update(['current_price' => $request->price]);
        } else {
            $product_token = $promo->product_token;
            $price_token = $promo->price_token;
        }

        $promo->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'active' => $request->has('active') ? true : false,
            'image' => $request->hasFile('image') ? $filename : $promo->image,
            'price_token' => $price_token,
            'product_token' => $product_token
        ]);

        $promo->quizzes()->sync($quizzes);

        return  redirect()->back()->with('status', 'Promotion updated successfully');
    }

    public function destroy(Promo $promo)
    {
        $promo->quizzes()->detach();
        $promo->product()->delete();
        $promo->delete();
        $this->stripeService->deleteProduct($promo->product_token, $promo->price_token);
        return  redirect()->back()->with('status', 'Promotion deleted successfully');
    }
}
