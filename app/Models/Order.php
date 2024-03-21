<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['session_id','quiz_id','client_id','status','amount_stripe','current_price','currency'];


    public function user()
    {
        return $this->belongsTo(User::class ,'client_id');
    }

    public function quiz()
    {
        return $this->belongsTo(config('laravel-quiz.models.quiz'));
    }
}
