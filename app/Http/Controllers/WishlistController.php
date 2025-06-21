<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::latest()->paginate(10);
        return view('wishlists.index', compact('wishlists'));
    }

    public function create()
    {
        return view('wishlists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
        ]);

        Wishlist::create($request->all());

        return redirect()->route('wishlists.index')
                         ->with('success', 'تمت إضافة المنتج إلى المفضلة.');
    }

    public function show($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        return view('wishlists.show', compact('wishlist'));
    }

    public function edit($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        return view('wishlists.edit', compact('wishlist'));
    }

    public function update(Request $request, $id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->update($request->all());

        return redirect()->route('wishlists.index')
                         ->with('success', 'تم تحديث المفضلة.');
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();

        return redirect()->route('wishlists.index')
                         ->with('success', 'تم حذف المنتج من المفضلة.');
    }
}
