<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function valueInventory()
    {
        $response = Product::with('category')->get();
        $totalValue = 0;
        foreach ($response as $product) {
            $totalValue += $product->price * $product->stock_quantity;
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Nilai inventaris berhasil dikalkulasi',
            'total_value' => $totalValue
        ], 200);
    }
}