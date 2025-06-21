<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\FashionModel;
use App\Models\Vacancy;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        // العدادات
        $userCount = User::count();
        $productCount = Product::count();
        $categoryCount = Category::count();
        $orderCount = Order::count();
        $modelCount = FashionModel::count();
        $vacancyCount = Vacancy::count();
        $blogCount = Blog::count();
        $commentCount = Comment::count();
        $reviewCount = Review::count();

        // أحدث المنتجات مع المورد/المصمم/المستخدم
        $latestProducts = Product::with('creator')
            ->latest()
            ->take(5)
            ->get();

        // أحدث الطلبات
        $latestOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // الإشعارات غير المقروءة لهذا الأدمن
$notifications = auth()->user()->notifications()
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

// عدد الإشعارات غير المقروءة
$unreadCount = auth()->user()->unreadNotifications()->count();


return view('admin.dashboard', compact(
    'userCount', 'productCount', 'categoryCount', 'orderCount',
    'modelCount', 'vacancyCount', 'blogCount', 'commentCount', 'reviewCount',
    'latestProducts', 'latestOrders', 'notifications', 'unreadCount'
));

    }
}
