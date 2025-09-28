<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->has('attributes')) {
            foreach ($request->attributes as $attribute => $value) {
                $query->whereHas('attributes', function ($q) use ($attribute, $value) {
                    $q->where('name', $attribute)->where('value', $value);
                });
            }
        }

        return response()->json($query->get());
    }
}
