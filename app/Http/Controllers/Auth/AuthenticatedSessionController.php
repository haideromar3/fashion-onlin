<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة']);
    }

    $user = Auth::user();

    // ✅ جلب السلة من قاعدة البيانات
    $dbCartItems = Cart::where('user_id', $user->id)->get();

    // ✅ تجهيز السلة بشكل session
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

    // ✅ تخزين السلة في الجلسة
    session()->put('cart', $sessionCart);
    session()->put('cart_count', array_sum(array_column($sessionCart, 'quantity')));

    return redirect()->intended(route('home'));
}

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

