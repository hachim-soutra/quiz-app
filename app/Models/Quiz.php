<?php

namespace App\Models;

use Harishdurga\LaravelQuiz\Models\Quiz as ModelsQuiz;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Quiz extends ModelsQuiz
{
    public function isPurchasedBy(int $id)
    {
        $orders_promos = Order::where('status', 'paid')->where('product_type', Promo::class)->pluck('product_id')->toArray();
        $cdt1 = $this->promos && $this->promoIsPayed($orders_promos);
        return $cdt1 || $this->product->orders()->where('status', 'paid')->where('client_id', $id)->count() > 0;
    }

    //  Get all of the quiz's products.
    public function product(): MorphOne
    {
        return $this->morphOne(Product::class, 'productable');
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'promo_quiz');
    }

    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'formation_quiz');
    }

    public function promoIsPayed($orders_promos)
    {
        return $this->promos()->whereHas('product', function ($q) use ($orders_promos) {
            $q->whereIn('id', $orders_promos);
        })->count() > 0;
    }
}
