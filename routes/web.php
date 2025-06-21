<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FashionModelController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ProductTypeController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\VacancyController;
use App\Http\Controllers\VirtualCardController;
use App\Http\Controllers\VirtualCardTransactionController;
use App\Http\Controllers\DeliveryController;




//Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/about', [\App\Http\Controllers\PageController::class, 'about'])->name('about');


// ✅ Auth routes
Auth::routes();


Route::middleware(['auth', 'delivery'])->prefix('delivery/orders')->name('delivery.orders.')->group(function () {
    Route::get('/', [DeliveryController::class, 'index'])->name('index');
    Route::get('/{order}', [DeliveryController::class, 'show'])->name('show');
    Route::put('/{order}', [DeliveryController::class, 'updateStatus'])->name('update');
    Route::put('/delivery/orders/{order}', [DeliveryController::class, 'updateStatus'])->name('delivery.orders.update');

});


Route::delete('/comments/{id}', [BlogController::class, 'deleteComment'])->name('comments.destroy');


Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');

Route::post('/payments/process', [PaymentController::class, 'verify'])->name('payments.process');

Route::middleware(['auth'])->group(function () {
    Route::get('/my-transactions', [VirtualCardTransactionController::class, 'index'])
        ->name('user.transactions.virtual_card');
});

Route::get('/vacancies', [VacancyController::class, 'index'])->name('vacancies.index');
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show'])->name('vacancies.show');
Route::post('/vacancies/{vacancy}/apply', [ApplicationController::class, 'store'])->name('applications.store');

Route::get('virtual_cards', [VirtualCardController::class, 'index'])->name('virtual_cards.index');
Route::post('/order/store-shipping', [OrderController::class, 'storeShipping'])->name('orders.storeShipping');
Route::post('/store-shipping-session', [OrderController::class, 'storeShippingSession'])->name('orders.storeShippingSession');

Route::middleware(['auth', 'prevent-back-history'])->group(function () {
Route::get('/set-currency/{currency}', function ($currency) {
$allowedCurrencies = array_keys(config('helpers.rates'));

    if (!in_array($currency, $allowedCurrencies)) {
        abort(404);
    }

    session(['currency' => $currency]);
    return back();
})->name('set.currency');

 Route::get('/fashion-models', [FashionModelController::class, 'index'])->name('fashion-models.index');
 Route::get('/fashion-models/{fashionModel}', [FashionModelController::class, 'show'])->name('fashion-models.show');  

//Route::get('/checkout', [PaymentController::class, 'create'])->name('payments.create');
//Route::post('/checkout/process', [PaymentController::class, 'verify'])->name('payments.process');


Route::get('/payment', [PaymentController::class, 'show'])->name('payment');
//Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');

Route::post('/reviews/{product}', [ReviewController::class, 'store'])->name('reviews.store');
Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');



Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::post('/orders/create-from-cart', [OrderController::class, 'createOrderFromCart'])->name('orders.createFromCart');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::post('/order/create', [OrderController::class, 'createOrderFromCart'])->name('order.create');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
//Route::post('/orders/create', [OrderController::class, 'createOrderFromCart'])->name('orders.createFromCart');
//Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');

//Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');


Route::post('/blogs/{blog}/like', [BlogController::class, 'like'])->middleware('auth');
Route::post('/blogs/{blog}/comment', [BlogController::class, 'comment'])->middleware('auth');
Route::put('/comments/{id}/ajax-update', [BlogController::class, 'ajaxUpdateComment'])->name('comments.ajaxUpdate');
Route::put('/comments/{comment}', [BlogController::class, 'ajaxUpdateComment'])->name('comments.update');


    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


// ✅ الصفحة الرئيسية
Route::get('/', [ProductController::class, 'index'])->name('home');
//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ✅ عرض المنتجات والبحث متاح للجميع
Route::resource('products', ProductController::class)->middleware(['auth', 'canPostProduct'])->except(['index', 'show']);
//Route::get('/products', [ProductController::class, 'index'])->name('products.index');
//Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/contact-admin', [App\Http\Controllers\MessageController::class, 'create'])->name('messages.create');
    Route::post('/contact-admin', [App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');

    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/my-complaints', [ComplaintController::class, 'myComplaints'])->name('complaints.my');
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
Route::get('complaints/{complaint}/edit', [ComplaintController::class, 'edit'])->name('complaints.edit');
Route::put('complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
Route::delete('/complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');


     Route::get('/black-friday', [ProductController::class, 'blackFriday'])->name('products.black_friday');

Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{blog}', [BlogController::class, 'show'])->name('blogs.show');

// ✅ صلاحية إضافة / تعديل / حذف المنتجات - للمستخدمين المصرح لهم فقط (admin, designer, supplier)
Route::middleware(['auth', 'canPostProduct'])->group(function () {
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');//
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroyProduct'])->name('products.destroy');
    Route::resource('brands', BrandController::class);

 //Route::get('/black-friday', [ProductController::class, 'blackFriday'])->name('products.black_friday');
 
    Route::delete('/product-images/{id}', [App\Http\Controllers\ProductImageController::class, 'destroy'])->name('product-images.destroy');

});
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// ✅ باقي الموارد
//Route::resource('brands', BrandController::class);
//Route::resource('carts', CartController::class);
//Route::resource('orders', OrderController::class);
//Route::resource('order-items', OrderItemController::class);
//Route::resource('payments', PaymentController::class);
Route::resource('shippings', ShippingController::class);
//Route::resource('reviews', ReviewController::class);
Route::resource('wishlists', WishlistController::class);
Route::resource('points', PointController::class);
Route::resource('notifications', NotificationController::class);
//Route::resource('blogs', BlogController::class);
//Route::resource('profiles', ProfileController::class);

// ✅ مسارات المسؤول (Admins فقط)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // إدارة المستخدمين
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users/{id}/role', [UserController::class, 'updateRole'])->name('admin.users.updateRole');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{id}/toggle-post-permission', [UserController::class, 'togglePostPermission'])->name('admin.users.togglePostPermission');

    // إدارة التصنيفات
    Route::resource('categories', CategoryController::class);
    Route::get('/categories/{category}/products', [CategoryController::class, 'products'])->name('categories.products');

    // إدارة العارضات
    Route::resource('fashion-models', FashionModelController::class)->except(['index', 'show']); 
    //Route::delete('/product-images/{id}', [App\Http\Controllers\ProductImageController::class, 'destroy'])->name('product-images.destroy');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
   // Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');
    Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');



    Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');

    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');

    Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');


     Route::get('/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
Route::post('/complaints/{id}/reply', [ComplaintController::class, 'reply'])->name('admin.complaints.reply');



    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('admin.reports.exportPDF');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCSV'])->name('admin.reports.exportCSV');

 Route::resource('product-types', ProductTypeController::class)->except(['show', 'create']);

    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('admin.complaints.show');

 Route::delete('complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('admin.complaints.destroy');


    Route::get('/vacancies', [VacancyController::class, 'index'])->name('admin.vacancies.index');

    Route::get('/vacancies/create', [VacancyController::class, 'create'])->name('admin.vacancies.create');
    Route::post('/vacancies/store', [VacancyController::class, 'store'])->name('admin.vacancies.store');
    Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show'])->name('admin.vacancies.show');
    Route::get('/vacancies/{vacancy}/edit', [VacancyController::class, 'edit'])->name('admin.vacancies.edit');
    Route::put('/vacancies/{vacancy}', [VacancyController::class, 'update'])->name('admin.vacancies.update');
    Route::delete('/vacancies/{vacancy}', [VacancyController::class, 'destroy'])->name('admin.vacancies.destroy');
});


// إنشاء وتخزين التدوينات للمستخدمين المصرح لهم فقط
  // Route::middleware(['auth', 'canPostBlog'])->group(function () {

   // Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');

    //Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
   // Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');

  //  Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');
//Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');

//});

});