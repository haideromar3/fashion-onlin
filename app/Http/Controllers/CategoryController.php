<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductType;

class CategoryController extends Controller
{
public function index()
{
    $categories = Category::withCount('products')->get();
    $types = ProductType::all();

    return view('categories.index', compact('categories', 'types'));
}
    
    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        return redirect()->route('categories.index')->with('success', 'تمت إضافة التصنيف بنجاح');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.show', compact('category'));
    }

  public function edit($id)
{
    $category = Category::findOrFail($id);
    $types = ProductType::all(); // أو حسب الفئة إن أردت الربط لاحقًا

    return view('categories.edit', compact('category', 'types'));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('categories.index')
                         ->with('success', 'تم تعديل الفئة بنجاح.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')
                         ->with('success', 'تم حذف الفئة بنجاح.');
    }

    public function products(Category $category)
{
    $products = $category->products()->with('images')->latest()->paginate(12);
    return view('categories.products', compact('category', 'products'));
}

}
