<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $shippings = Shipping::latest()->paginate(10);
        return view('shippings.index', compact('shippings'));
    }

    public function create()
    {
        return view('shippings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'address' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        Shipping::create($request->all());

        return redirect()->route('shippings.index')
                         ->with('success', 'تم إضافة معلومات الشحن.');
    }

    public function show($id)
    {
        $shipping = Shipping::findOrFail($id);
        return view('shippings.show', compact('shipping'));
    }

    public function edit($id)
    {
        $shipping = Shipping::findOrFail($id);
        return view('shippings.edit', compact('shipping'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $shipping = Shipping::findOrFail($id);
        $shipping->update($request->all());

        return redirect()->route('shippings.index')
                         ->with('success', 'تم تحديث بيانات الشحن.');
    }

    public function destroy($id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping->delete();

        return redirect()->route('shippings.index')
                         ->with('success', 'تم حذف الشحنة.');
    }
}
