<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Melihat cart user yang sedang login
    public function index(Request $request)
    {
        $carts = Cart::with('product.seller', 'product.category')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data cart berhasil diambil',
            'data' => $carts
        ]);
    }

    // Menambahkan produk ke cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);

        $existingCart = Cart::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingCart) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sudah ada di cart'
            ], 422);
        }

        $cart = Cart::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        $cart->load('product');

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke cart',
            'data' => $cart
        ], 201);
    }

    // Menghapus item dari cart
    public function destroy(Request $request, $id)
    {
        $cart = Cart::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari cart'
        ]);
    }
}
