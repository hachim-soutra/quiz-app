<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'price', 'active', 'image', 'price_token', 'product_token'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function quizzes()
    {
        return $this->belongsToMany(config('laravel-quiz.models.quiz'));
    }
    //  Get all of the promo's products.
    public function product(): MorphOne
    {
        return $this->morphOne(Product::class, 'productable');
    }
}
