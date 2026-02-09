<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
        ]);

        if (! $order->canTransitionTo($request->status)) {
            return response()->json([
                'message' => 'Invalid order status transition',
            ], 422);
        }

        $order->update([
            'status' => $request->status,
        ]);

        $order->notifyStatusChanged();

        return response()->json([
            'message' => 'Order status updated',
            'status'  => $order->status,
        ]);
    }
}