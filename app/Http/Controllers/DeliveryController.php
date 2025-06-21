<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();
        return view('delivery.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,returned,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'تم تحديث الحالة بنجاح.');
    }


    public function show(Order $order)
{
    return view('delivery.orders.show', compact('order'));
}

}

