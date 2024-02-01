<?php

namespace App\Services;

use Laravel\Cashier\Cashier;

class StripeService
{
    public $stripe;

    public function __construct()
    {
        $this->stripe = Cashier::stripe();
    }
    public function createProduct($name, $price)
    {
        $product = $this->stripe->products->create([
            'name' => $name,
            'active' => true,
            'default_price_data' => [
                'currency' => 'usd',
                'unit_amount' => $price * 100,
            ],
        ]);
        return $product;
    }
    public function updateProduct($productId, $priceToken)
    {
        $product = $this->stripe->products->update([
            $productId,
            ['default_price' => $priceToken]
        ]);
        return $product;
    }
    public function updateProductPrice(string $productToken, float $price)
    {
        $newPrice = $this->stripe->prices->create([
            'active' => true,
            'currency' => 'usd',
            'unit_amount' => $price * 100,
            'product' => $productToken,
            ]);
        return $newPrice;
    }
}
