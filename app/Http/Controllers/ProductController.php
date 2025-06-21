<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use App\Models\ProductType;

class ProductController extends Controller
{
    // عرض جميع المنتجات مع الفلترة والبحث ودعم العملة
   public function index(Request $request)
{
    $query = Product::with(['images', 'creator'])->where('is_published', true);

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    if ($request->filled('type')) {
        $query->where('product_type_id', $request->type);
    }

    $products = $query->latest()->paginate(12);

    $categories = Category::all();
    $types = ProductType::all();

   $currency = session('currency', 'USD');

$rates = [
    'USD' => 1,
    'EUR' => 0.92,
    'GBP' => 0.79,
    'SAR' => 3.75,
    'SYP' => 10000, // أضف السعر التقريبي للصرف هنا
];

$conversionRate = $rates[$currency] ?? 1;

return view('products.index', compact('products', 'categories', 'types', 'currency', 'conversionRate'));

}


public function create()
{
    $user = auth()->user();

    if ($user->role === 'admin') {
        // الأدمن يقدر ينشئ بدون مشاكل
    } elseif (in_array($user->role, ['supplier', 'designer'])) {
        if (!$user->can_post_products) {
            abort(403, 'ليس لديك صلاحية لإضافة منتجات حالياً');
        }
    } else {
        abort(403, 'غير مصرح لك.');
    }

    $categories = Category::all();
    $brands = Brand::all();
    $types = ProductType::all();

    return view('products.create', compact('categories', 'brands', 'types'));
}

 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'in:small,medium,large,1XL,2XL,3XL,4XL',
            'colors' => 'nullable|array',
            'colors.*' => 'in:red,blue,yellow,black,white',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $discount = $request->input('discount');
        $price = $validated['price'];
        $final_price = $discount ? $price - ($price * $discount / 100) : $price;

        $product = new Product();
        $product->name = $validated['name'];
        $product->description = $request->description;
        $product->discount = $discount;
        $product->price = $final_price;
        $product->stock = $validated['stock'];
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->product_type_id = $request->product_type_id;
        $product->user_id = auth()->id();
        $product->is_published = $request->has('is_published');
        $product->sizes = json_encode($request->sizes);
        $product->colors = json_encode($request->colors);
        $product->is_black_friday = $request->has('is_black_friday');

        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('products.index')->with('success', 'تمت إضافة المنتج بنجاح');
    }

public function show(Product $product)
{
    // تحميل علاقات المنتج مع الصور، التصنيف، الماركة، المستخدم (المالك أو المنشئ)
    $product->load(['images', 'category', 'brand', 'user']);

    // جلب التقييمات المرتبطة بالمنتج مع تحميل بيانات المستخدم المقيّم (إن وجدت)
    $reviews = $product->reviews()->with('user')->get();

    // تمرير المنتج والتقييمات إلى صفحة العرض
    return view('products.show', compact('product', 'reviews'));
}


    public function edit(Product $product)
    {
        if (auth()->user()->role !== 'admin' && auth()->id() !== $product->user_id) {
            abort(403, 'غير مصرح لك بتعديل هذا المنتج.');
        }

        $categories = Category::all();
        $brands = Brand::all();
        $types = ProductType::all();

        return view('products.edit', compact('product', 'categories', 'brands', 'types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'in:small,medium,large,1XL,2XL,3XL,4XL',
            'colors' => 'nullable|array',
            'colors.*' => 'in:red,blue,yellow,black,white',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = Product::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->id() !== $product->user_id) {
            abort(403, 'غير مصرح لك بتعديل هذا المنتج.');
        }

        $discount = $request->input('discount');
        $price = $request->price;
        $final_price = $discount ? $price - ($price * $discount / 100) : $price;

        $product->name = $request->name;
        $product->description = $request->description;
        $product->discount = $discount;
        $product->price = $final_price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->product_type_id = $request->product_type_id;
        $product->is_published = $request->has('is_published');
        $product->sizes = json_encode($request->sizes);
        $product->colors = json_encode($request->colors);
        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('product_images', 'public');
                $product->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('products.index')->with('success', 'تم تعديل المنتج بنجاح.');
    }

public function destroy(ProductImage $image)
{
    $product = $image->product;

    if (!$product || !Product::find($product->id)) {
        return redirect()->back()->with('error', 'الصورة غير مرتبطة بأي منتج أو أن المنتج محذوف.');
    }

    if (auth()->user()->role !== 'admin' && auth()->id() !== $product->user_id) {
        abort(403, 'غير مصرح لك بحذف هذه الصورة.');
    }

    if (Storage::disk('public')->exists($image->image_path)) {
        Storage::disk('public')->delete($image->image_path);
    }

    $image->delete();

    return response()->json(['message' => 'تم حذف الصورة بنجاح.']);
}

public function destroyProduct(Product $product)
{
    if (auth()->user()->role !== 'admin' && auth()->id() !== $product->user_id) {
        abort(403, 'غير مصرح لك بحذف هذا المنتج.');
    }

    // حذف الصور من التخزين
    foreach ($product->images as $image) {
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
    }

    // حذف السجلات من جدول الصور
    $product->images()->delete();

    // حذف المنتج نفسه
    $product->delete();

    return redirect()->route('products.index')->with('success', 'تم حذف المنتج والصور المرتبطة به بنجاح.');
}




    public function blackFriday()
{
$products = Product::where('is_black_friday', true)->paginate(12); // أو أي رقم تريده

    return view('products.black_friday', compact('products'));
}
}
