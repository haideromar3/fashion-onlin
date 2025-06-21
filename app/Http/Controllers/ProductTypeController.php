<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductType;

class ProductTypeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ProductType::create([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'تمت إضافة النوع بنجاح.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $type = ProductType::findOrFail($id);
        $type->update([
            'name' => $request->name,
        ]);

        return redirect()->route('categories.index')->with('success', 'تم تعديل النوع بنجاح.');
    }

    public function destroy($id)
    {
        $type = ProductType::findOrFail($id);
        $type->delete();

        return redirect()->route('categories.index')->with('success', 'تم حذف النوع بنجاح.');
    }
}
