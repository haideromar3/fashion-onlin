<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Cart;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * إعادة توجيه المستخدم بعد تسجيل الدخول حسب الدور
     */
    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return '/admin/dashboard';
        }

        if ($user->role === 'designer' || $user->role === 'customer') {
            return '/products';
        }

        return '/products';
    }

    /**
     * تنفيذ بعد تسجيل الدخول بنجاح
     */
    protected function authenticated(Request $request, $user)
{
    // تحميل السلة كما عندك
    $dbCartItems = Cart::where('user_id', $user->id)->get();
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

    // التوجيه حسب الدور
    if ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($user->role === 'delivery') {
        return redirect('/delivery/orders');  // مسار صفحة الديلفري
    } elseif ($user->role === 'customer' || $user->role === 'designer') {
        return redirect('/products');
    }

    return redirect('/products');
}


    /**
     * الإنشاء
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
