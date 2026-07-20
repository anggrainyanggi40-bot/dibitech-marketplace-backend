<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Cart;

class PaymentController extends Controller
{
    public function createPayment(Request $request, $orderId)
{
    $order = Order::with('orderItems.product')
        ->where('user_id', $request->user()->id)
        ->findOrFail($orderId);

    if ($order->order_status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Order ini tidak dapat dibayar'
        ], 422);
    }

    $existingPayment = Payment::where('order_id', $order->id)->first();

    if ($existingPayment) {
         return response()->json([
        'success' => false,
        'message' => 'Payment untuk order ini sudah dibuat',
        'data' => $existingPayment
        ], 422);
    }

    $amount = $order->orderItems->sum(function ($item) {
        return $item->price * $item->quantity;
    });

    Config::$serverKey = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $transactionId = 'ORDER-' . $order->id . '-' . time();

    $params = [
        'transaction_details' => [
            'order_id' => $transactionId,
            'gross_amount' => (int) $amount,
        ],
        'customer_details' => [
            'email' => $request->user()->email,
        ],
    ];

    $snapToken = Snap::getSnapToken($params);

    $payment = Payment::create([
        'order_id' => $order->id,
        'transaction_id' => $transactionId,
        'amount' => $amount,
        'payment_status' => 'pending',
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Transaksi pembayaran berhasil dibuat',
        'data' => [
            'payment' => $payment,
            'snap_token' => $snapToken,
        ]
    ]);
}
public function notification(Request $request)
{
    $serverKey = config('services.midtrans.server_key');

    $signatureKey = hash(
        'sha512',
        $request->order_id .
        $request->status_code .
        $request->gross_amount .
        $serverKey
    );

    if ($signatureKey !== $request->signature_key) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid signature'
        ], 403);
    }

    $payment = Payment::where(
        'transaction_id',
        $request->order_id
    )->firstOrFail();

    $transactionStatus = $request->transaction_status;

    if ($transactionStatus === 'settlement' ||
        $transactionStatus === 'capture') {

        $payment->update([
            'payment_status' => 'paid',
            'payment_method' => $request->payment_type,
            'payment_date' => now(),
        ]);

        $payment->order->update([
            'order_status' => 'completed'
        ]);

        $productIds = $payment->order
         ->orderItems()
         ->pluck('product_id');

Cart::where('user_id', $payment->order->user_id)
    ->whereIn('product_id', $productIds)
    ->delete();

    } elseif ($transactionStatus === 'pending') {

        $payment->update([
            'payment_status' => 'pending',
            'payment_method' => $request->payment_type,
        ]);

    } elseif (in_array($transactionStatus, [
        'deny',
        'cancel',
        'expire'
    ])) {

        $payment->update([
            'payment_status' => $transactionStatus === 'expire'
                ? 'expired'
                : 'failed',
            'payment_method' => $request->payment_type,
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Notification berhasil diproses'
    ]);
}
}
