<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {

                // Ambil produk yang ingin dibeli
                $product = Product::findOrFail($request->product_id);

                // Buat order
                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'order_status' => 'pending',
                    'order_date' => now()->toDateString(),
                ]);

                // Buat order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $product->price,
                ]);

                return $order;
            });

            $order->load('orderItems.product');

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat',
                'data' => $order,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order gagal dibuat',
            ], 500);
        }
    }

    public function checkoutCart(Request $request)
{
    try {
        $order = DB::transaction(function () use ($request) {

            // Ambil semua cart milik user yang sedang login
            $carts = Cart::with('product')
                ->where('user_id', $request->user()->id)
                ->get();

            // Pastikan cart tidak kosong
            if ($carts->isEmpty()) {
                throw new \Exception('Cart kosong');
            }

            // Buat satu order
            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_status' => 'pending',
                'order_date' => now()->toDateString(),
            ]);

            // Masukkan semua produk cart ke order_items
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);
            }

            return $order;
        });

        $order->load('orderItems.product');

        return response()->json([
            'success' => true,
            'message' => 'Order dari cart berhasil dibuat',
            'data' => $order,
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 422);
    }
}

    public function index()
{
    $orders = Order::with('orderItems.product')
        ->where('user_id', auth()->id())
        ->get();

    return response()->json([
        'success' => true,
        'message' => 'Data order berhasil diambil',
        'data' => $orders
    ]);
}
public function show(Request $request, $id)
{
    $order = Order::with('orderItems.product')
        ->where('user_id', $request->user()->id)
        ->findOrFail($id);

    return response()->json([
        'success' => true,
        'message' => 'Detail order berhasil diambil',
        'data' => $order
    ]);
}
public function cancel(Request $request, $id)
{
    $order = Order::where('user_id', $request->user()->id)
        ->findOrFail($id);

    if ($order->order_status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Hanya order dengan status pending yang dapat dibatalkan'
        ], 422);
    }

    $order->update([
        'order_status' => 'cancelled'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Order berhasil dibatalkan',
        'data' => $order
    ]);
}
}
