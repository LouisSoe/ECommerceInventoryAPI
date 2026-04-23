<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $response = Category::all();
        if($response->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil ditemukan',
                'data' => $response
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori tidak ditemukan',
                'data' => []
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }
        $data = [
            'name' => $request->input('name')
        ];
        $response = Category::create($data);
        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dibuat',
            'data' => $response
        ], 201);
    }
}