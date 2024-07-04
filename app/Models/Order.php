<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['session_id','product_id','product_type','client_id','status','amount_stripe','current_price','currency'];


    public function user()
    {
        return $this->belongsTo(User::class ,'client_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
