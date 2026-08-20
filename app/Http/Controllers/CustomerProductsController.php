<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CustomerProductsController extends Controller
{
    public function index()
    {
        $products = Product::with('variants')
            ->where('status', 'active')
            ->latest()
            ->get();

        return view(
            'customer.index',
            compact('products')
        );
    }
}