<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanPostBlog
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->can_post_blog) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}