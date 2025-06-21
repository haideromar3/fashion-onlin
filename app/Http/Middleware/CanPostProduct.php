<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanPostProduct
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // السماح للادمن
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // السماح لمن لديهم خاصية can_post_products مثلا
        if ($user && $user->can_post_products) {
            return $next($request);
        }

        abort(403, 'ليس لديك صلاحية لإضافة منتجات');
    }
}