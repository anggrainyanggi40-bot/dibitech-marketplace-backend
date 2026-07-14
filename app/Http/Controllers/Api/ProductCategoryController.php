<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductCategoryController extends Controller
{
    public function index()
    {
        $category = ProductCategory::all();
        return response()->json([
            'success' => true,
            'message' => 'Data kategori berhasil diambil',
            'data' => $category
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'seller') {
        return response()->json([
            'success' => false,
            'message' => 'Anda tidak memiliki akses untuk melakukan tindakan ini'
        ], 403);
        }

        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $category = ProductCategory::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Category berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    public function show(ProductCategory $productCategory)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data kategori berhasil diambil',
            'data' => $productCategory
        ]);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $productCategory->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Category berhasil diupdate',
            'data' => $productCategory
        ]);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return response()->json([
            'success' => true,
            'message' => 'Category berhasil dihapus'
        ]);
    }
}
