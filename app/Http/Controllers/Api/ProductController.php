<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $produk = Product::with(['seller', 'category']);

        //search by product name
        if($request->search){
        $produk->where('Product_name', 'like', '%' . $request->search . '%');
        }

        //filter by category
        if($request->category_id){
        $produk->where('category_id', $request->category_id);
        }

        //filter by price range
        if($request->min_price){
        $produk->where('price', '>=', $request->min_price);
        }
        if($request->max_price){
        $produk->where('price', '<=', $request->max_price);
        }

        //sorting
        if($request->sort_by){
        $order= $request->order === 'asc'? 'asc' : 'desc';
        $allowedSorts = ['price', 'product_name'];
        if(in_array($request->sort_by, $allowedSorts)){
        $produk->orderBy($request->sort_by, $order);
        }
        }

        $products = $produk->get();
        return response()->json([
            'success' => true,
            'message' => 'Data produk berhasil diambil',
            'data' => $products
        ]);

    }

    public function sellerProducts(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'seller') {
        return response()->json([
            'success' => false,
            'message' => 'Hanya seller yang dapat mengakses halaman ini'
        ], 403);
        }

        $products = Product::with('category')
            ->where('seller_id', $user->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Produk seller berhasil diambil',
            'data' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'seller') {
        return response()->json([
            'success' => false,
            'message' => 'Anda tidak memiliki akses untuk melakukan tindakan ini'], 403);
        }
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'detail_product' => 'nullable|string',
            'category_id' => 'required|integer|exists:product_categories,id',
            'price' => 'required|numeric',
            'file_size' => 'required|numeric',
            'file_url' => 'required|string',
            'stock' => 'required|integer',
        ]);

        $validated['seller_id'] = Auth::id();

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product berhasil dibuat',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Product::with(['seller', 'category'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Data produk berhasil diambil',
            'data' => $produk
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {


        if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product tidak ditemukan'
            ], 404);
        }

        $user = Auth::user();
        if ($user->role !== 'seller' || $product->seller_id !== $user->id) {
    return response()->json([
        'success' => false,
        'message' => 'Anda tidak memiliki akses untuk mengubah produk ini'
    ], 403);
}

        $validator = Validator::make($request->all(), [
            'product_name' => 'sometimes|required|string|max:255',
            'detail_product' => 'nullable|string',
            'category_id' => 'sometimes|required|integer|exists:product_categories,id',
            'price' => 'sometimes|required|numeric',
            'file_size' => 'sometimes|required|numeric',
            'file_url' => 'sometimes|required|string',
            'stock' => 'sometimes|required|integer',
        ]);

        if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
        }

        $product->update($validator->validated());
        return response()->json([
            'success' => true,
            'message' => 'Product berhasil diupdate',
            'data' => $product
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product tidak ditemukan'
        ], 404);
        }
        $user = Auth::user();
        if ( $user->role !== 'seller' || $product->seller_id !== $user->id ) {
        return response()->json([
            'success' => false,
            'message' => 'Anda tidak memiliki akses untuk menghapus produk ini'
        ], 403);
        }

        $product->delete();
        return response()->json([
            'success' => true,
            'message' => 'Product berhasil dihapus'
        ],200);

    }
}
