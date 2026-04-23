<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promotion;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function index()
    {
        $response = Promotion::with('product')->get();
        if($response->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data promosi berhasil ditemukan',
                'data' => $response
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data promosi tidak ditemukan',
                'data' => []
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:tb_product,id',
            'name' => 'required|string|max:100',
            'discount_percentage' => 'required|integer|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        // Check if there's already an active promotion for this product in the same date range
        $conflict = Promotion::where('product_id', $request->input('product_id'))
            ->where('is_active', true)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })->exists();

        if ($conflict) {
            return response()->json([
                'status' => 'error',
                'message' => 'Produk ini sudah memiliki promosi aktif di rentang tanggal tersebut',
            ], 400);
        }

        $data = $request->only([
            'product_id', 'name', 'discount_percentage', 'start_date', 'end_date'
        ]);
        $data['is_active'] = $request->input('is_active', true);

        $response = Promotion::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Promosi berhasil dibuat',
            'data' => $response
        ], 201);
    }

    public function show($id)
    {
        $response = Promotion::with('product')->find($id);
        if($response) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data promosi berhasil ditemukan',
                'data' => $response
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data promosi tidak ditemukan',
                'data' => []
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::find($id);
        if (!$promotion) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data promosi tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_id' => 'exists:tb_product,id',
            'name' => 'string|max:100',
            'discount_percentage' => 'integer|min:1|max:100',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'data' => $validator->errors()
            ], 422);
        }

        $promotion->update($request->only([
            'product_id', 'name', 'discount_percentage', 'start_date', 'end_date', 'is_active'
        ]));

        return response()->json([
            'status' => 'success',
            'message' => 'Promosi berhasil diupdate',
            'data' => $promotion
        ], 200);
    }

    public function destroy($id)
    {
        $promotion = Promotion::find($id);
        if ($promotion) {
            $promotion->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Promosi berhasil dihapus',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data promosi tidak ditemukan',
            ], 404);
        }
    }
}
