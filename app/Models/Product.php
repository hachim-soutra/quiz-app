<?php

namespace App\Models;

use Harishdurga\LaravelQuiz\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Product extends Model
{
    use HasFactory;

    public $fillable = ['productable_id', 'productable_type'];

    /**
     * Get all of the models that own product.
     */
    public function productable(): MorphTo
    {
        return $this->morphTo();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id', 'id');
    }

    public function getNameAttribute()
    {
        if ($this->productable_type == Quiz::class)
        {
            return $this->productable->name;
        }
            return $this->productable->title;
        
    }

    public function getTypeAttribute()
    {
        if ($this->productable_type == Quiz::class)
        {
            return 'Quiz';
        }
            return 'Promotion';
        
    }
}
