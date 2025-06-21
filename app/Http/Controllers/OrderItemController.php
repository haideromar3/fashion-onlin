<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        // عرض آخر العناصر مع التصفح
        $orderItems = OrderItem::latest()->paginate(10);
        return view('order_items.index', compact('orderItems'));
    }

    public function create()
    {
        // صفحة إنشاء عنصر طلب جديد
        return view('order_items.create');
    }

    public function store(Request $request)
    {
        // تحقق من صحة البيانات
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
        ]);

        OrderItem::create($validatedData);

        return redirect()->route('order-items.index')
                         ->with('success', 'تمت إضافة عنصر الطلب بنجاح.');
    }

    public function show($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        return view('order_items.show', compact('orderItem'));
    }

    public function edit($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        return view('order_items.edit', compact('orderItem'));
    }

    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات للتحديث (يمكن إضافة باقي الحقول إذا رغبت)
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
            'size' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:50',
        ]);

        $orderItem = OrderItem::findOrFail($id);
        $orderItem->update($validatedData);

        return redirect()->route('order-items.index')
                         ->with('success', 'تم تعديل عنصر الطلب.');
    }

    public function destroy($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        $orderItem->delete();

        return redirect()->route('order-items.index')
                         ->with('success', 'تم حذف عنصر الطلب.');
    }
}

