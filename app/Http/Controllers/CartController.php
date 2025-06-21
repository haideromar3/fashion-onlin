<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
public function index()
{
    $cart = session('cart', []); // أو من قاعدة البيانات إن كنت تستخدمها للمستخدم المسجّل

    return view('carts.index', [
        'cart' => $cart
    ]);
}


public function add(Request $request)
{
    $productId = $request->input('product_id');
    $quantity = max(1, (int) $request->input('quantity', 1));
    $size = $request->input('size');
    $color = $request->input('color');

    $product = Product::with('images')->find($productId);
    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'المنتج غير موجود.'
        ]);
    }

    if (!$size || !$color) {
        return response()->json([
            'success' => false,
            'message' => 'الرجاء اختيار القياس واللون.'
        ]);
    }

    if ($product->stock < $quantity) {
        return response()->json([
            'success' => false,
            'message' => 'الكمية المطلوبة غير متوفرة في المخزون.'
        ]);
    }

    // خصم الكمية من المخزون
    $product->stock -= $quantity;
    $product->save();

    // جلب السلة من الجلسة أو إنشاؤها
    $cart = session()->get('cart', []);
    $itemKey = $productId . '_' . $size . '_' . $color;

    if (isset($cart[$itemKey])) {
        $cart[$itemKey]['quantity'] += $quantity;
    } else {
        $cart[$itemKey] = [
            'product_id' => $productId,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $quantity,
            'size' => $size,
            'color' => $color,
            'image' => $product->images->first()->image_path ?? 'images/no-image.png',
        ];
    }

    session()->put('cart', $cart);

    // إذا كان المستخدم مسجّل دخول، حدّث قاعدة البيانات
    if (auth()->check()) {
        $item = $cart[$itemKey];

        $existing = Cart::where([
            'user_id' => auth()->id(),
            'product_id' => $item['product_id'],
            'size' => $item['size'],
            'color' => $item['color'],
        ])->first();

        if ($existing) {
            $existing->quantity += $quantity;
            $existing->price = $item['price'];
            $existing->name = $item['name'];
            $existing->image = $item['image'];
            $existing->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $item['product_id'],
                'size' => $item['size'],
                'color' => $item['color'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'name' => $item['name'],
                'image' => $item['image'],
            ]);
        }
    }

    // حساب عدد العناصر الكلي في السلة (مجموع الكميات)
    $cartCount = array_sum(array_column($cart, 'quantity'));
    session()->put('cart_count', $cartCount);

    return response()->json([
        'success' => true,
        'message' => 'تمت إضافة المنتج إلى السلة.',
        'cart_count' => $cartCount,
    ]);
}



    public function update(Request $request, $key)
{
    $request->validate([
        'quantity' => 'required|integer|min:1'
    ]);

    $cart = session()->get('cart', []);

    if (isset($cart[$key])) {
        $oldQuantity = $cart[$key]['quantity'];
        $newQuantity = $request->quantity;
        $difference = $newQuantity - $oldQuantity;

        $product = Product::find($cart[$key]['product_id']);
        if ($product) {
            // ✅ التحقق من توفر الكمية الجديدة
            if ($difference > 0 && $product->stock < $difference) {
                return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون.');
            }

            // ✅ تعديل المخزون
            $product->stock -= $difference;
            $product->save();
        }

        $cart[$key]['quantity'] = $newQuantity;
        session()->put('cart', $cart);

        if (auth()->check()) {
            [$product_id, $size, $color] = explode('_', $key);
            Cart::where('user_id', auth()->id())
                ->where('product_id', $product_id)
                ->where('size', $size)
                ->where('color', $color)
                ->update(['quantity' => $newQuantity]);
        }
    }

    $cartCount = array_sum(array_column($cart, 'quantity'));
    session()->put('cart_count', $cartCount);

    return redirect()->route('cart.index')->with('success', 'تم تعديل الكمية بنجاح');
}


public function remove($key)
{
    $cart = session()->get('cart', []);

    if (isset($cart[$key])) {
        $item = $cart[$key];

        // ✅ استرجاع الكمية إلى المخزون
        $product = Product::find($item['product_id']);
        if ($product) {
            $product->stock += $item['quantity'];
            $product->save();
        }

        unset($cart[$key]);
        session()->put('cart', $cart);

        if (auth()->check()) {
            [$product_id, $size, $color] = explode('_', $key);
            Cart::where('user_id', auth()->id())
                ->where('product_id', $product_id)
                ->where('size', $size)
                ->where('color', $color)
                ->delete();
        }
    }

    $cartCount = array_sum(array_column($cart, 'quantity'));
    session()->put('cart_count', $cartCount);

    return back()->with('success', 'تمت إزالة المنتج من السلة');
}



   public function clear()
{
    $cart = session()->get('cart', []);

    // ✅ استرجاع الكميات إلى المخزون
    foreach ($cart as $item) {
        $product = Product::find($item['product_id']);
        if ($product) {
            $product->stock += $item['quantity'];
            $product->save();
        }
    }

    session()->forget('cart');
    session()->forget('cart_count');

    if (auth()->check()) {
        Cart::where('user_id', auth()->id())->delete();
    }

    return redirect()->route('cart.index')->with('success', 'تم إفراغ السلة بنجاح');
}




    public function loadCartFromDatabase()
{
    if (!auth()->check()) {
        return;
    }

    $dbCartItems = Cart::where('user_id', auth()->id())->get();
    $sessionCart = [];

    foreach ($dbCartItems as $item) {
        $key = $item->product_id . '_' . $item->size . '_' . $item->color;
        $sessionCart[$key] = [
            'product_id' => $item->product_id,
            'name' => $item->name,
            'price' => $item->price,
            'quantity' => $item->quantity,
            'size' => $item->size,
            'color' => $item->color,
            'image' => $item->image,
        ];
    }

    session()->put('cart', $sessionCart);
    session()->put('cart_count', array_sum(array_column($sessionCart, 'quantity')));
}




}
