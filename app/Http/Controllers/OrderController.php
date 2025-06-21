<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.product'])
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function storeShipping(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
        ]);

        session()->put('shipping', [
            'shipping_method' => $request->input('shipping_method'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
        ]);

        return redirect()->route('payment.checkout'); // صفحة الدفع
    }

    public function createOrderFromCart(Request $request)
{
    $request->validate([
        'shipping_method' => 'required|in:standard,express',
        'payment_method' => 'required|in:cod,card',
        'city' => 'required|string|max:100',
        'address' => 'required|string|max:500',
    ]);

    $user = auth()->user();
    $cart = session('cart', []);

    if (empty($cart)) {
        return redirect()->back()->with('error', 'سلتك فارغة!');
    }

    // تحقق من توفر الكميات
    foreach ($cart as $itemKey => $item) {
        $parts = explode('_', $itemKey);
        $productId = $parts[0];
        $product = Product::find($productId);

        if (!$product || $product->stock < $item['quantity']) {
            return back()->with('error', "المنتج {$product->name} غير متوفر بالكميات المطلوبة.");
        }
    }

    // حساب الإجمالي
    $total = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    // إنشاء الطلب
    $order = Order::create([
        'user_id' => $user->id,
        'total' => $total,
        'status' => 'pending',
        'shipping_method' => $request->shipping_method,
        'payment_method' => $request->payment_method,
        'city' => $request->city,
        'address' => $request->address,
    ]);

    // إنشاء عناصر الطلب + خصم الكمية من المخزون
    foreach ($cart as $itemKey => $item) {
        $parts = explode('_', $itemKey);
        $productId = $parts[0];

        // إنشاء العنصر في الطلب
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $productId,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'size' => $item['size'] ?? null,
            'color' => $item['color'] ?? null,
        ]);

        // خصم الكمية من المخزون
        $product = Product::find($productId);
        if ($product) {
            $product->stock -= $item['quantity'];
            $product->save();
        }
    }

    // إفراغ السلة
    session()->forget('cart');
    session()->forget('cart_count');

    return redirect()->route('orders.show', $order->id)
                     ->with('success', 'تم إنشاء الطلب بنجاح!');
}


    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity', 1);

        if ($product->stock < $quantity) {
            return back()->with('error', 'الكمية غير متوفرة حالياً في المخزون.');
        }

        $cart = session()->get('cart', []);
        $key = $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);
        session()->put('cart_count', count($cart));

        return back()->with('success', 'تمت إضافة المنتج إلى السلة.');
    }
}
