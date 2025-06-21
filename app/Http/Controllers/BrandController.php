<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view('brands.index', compact('brands'));
    }

    public function create()
{
    // السماح فقط للمصمم أو المورد
    if (!in_array(auth()->user()->role, ['designer', 'supplier','admin'])) {
        abort(403, 'ليس لديك صلاحية لإضافة ماركة');
    }

    return view('brands.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $brand = new Brand();
    $brand->name = $request->name;
    $brand->description = $request->description;
    $brand->user_id = auth()->id();

    if ($request->hasFile('logo')) {
        $brand->logo = $request->file('logo')->store('brand_logos', 'public');
    }

    $brand->save();

    return redirect()->route('brands.index')->with('success', 'تمت إضافة الماركة بنجاح');
}
    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brands.show', compact('brand'));
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, \App\Models\Brand $brand)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $brand->name = $request->name;
    $brand->description = $request->description;

    // ✅ إذا تم رفع شعار جديد
    if ($request->hasFile('logo')) {
        // (اختياري) حذف الشعار القديم من التخزين:
        if ($brand->logo && \Storage::disk('public')->exists($brand->logo)) {
            \Storage::disk('public')->delete($brand->logo);
        }

        // تخزين الشعار الجديد
        $brand->logo = $request->file('logo')->store('brand_logos', 'public');
    }

    $brand->save();

    return redirect()->route('brands.index')->with('success', 'تم تعديل الماركة بنجاح');
}

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('brands.index')
                         ->with('success', 'تم حذف الماركة بنجاح.');
    }
}
