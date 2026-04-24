<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $response = Product::with(['category', 'activePromotion'])->get();
        if($response->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil ditemukan',
                'data' => $response
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
                'data' => []
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:tb_category,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }
        $data = [
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'price' => $request->input('price'),
            'stock_quantity' => $request->input('stock_quantity'),
        ];
        $response = Product::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil dibuat',
            'data' => $response
        ], 201);
    }

    public function show($id)
    {
        $response = Product::with(['category', 'activePromotion'])->find($id);
        if($response) {
            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil ditemukan',
                'data' => $response
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
                'data' => []
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'category_id' => 'required|exists:tb_category,id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }
        $data = [
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'price' => $request->input('price'),
            'stock_quantity' => $request->input('stock_quantity'),
        ];
        $response = Product::where('id', $id)->update($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil diupdate',
            'data' => $response
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $response = $product->delete();
        if($response) {
            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil dihapus',
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }
    }

    public function search(Request $request)
    {
        $query = Product::with(['category', 'activePromotion']);

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $response = $query->get();
        if($response->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil ditemukan',
                'data' => $response
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
                'data' => []
            ], 404);
        }
    }

    public function updateStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'quantity_sold' => 'required|integer|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $product = Product::find($request->input('id'));
        
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        if ($product->stock_quantity < $request->input('quantity_sold')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Stok produk tidak mencukupi',
            ], 400);
        }

        // Mengurangi stok produk (decrement)
        $product->decrement('stock_quantity', $request->input('quantity_sold'));

        return response()->json([
            'status' => 'success',
            'message' => 'Stok produk berhasil dikurangi',
            'data' => $product->fresh()
        ], 200);
    }
}