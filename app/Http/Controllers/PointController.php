<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index()
    {
        $points = Point::latest()->paginate(10);
        return view('points.index', compact('points'));
    }

    public function create()
    {
        return view('points.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer',
        ]);

        Point::create($request->all());

        return redirect()->route('points.index')
                         ->with('success', 'تم إضافة نقاط المستخدم.');
    }

    public function show($id)
    {
        $point = Point::findOrFail($id);
        return view('points.show', compact('point'));
    }

    public function edit($id)
    {
        $point = Point::findOrFail($id);
        return view('points.edit', compact('point'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'points' => 'required|integer',
        ]);

        $point = Point::findOrFail($id);
        $point->update($request->all());

        return redirect()->route('points.index')
                         ->with('success', 'تم تحديث نقاط المستخدم.');
    }

    public function destroy($id)
    {
        $point = Point::findOrFail($id);
        $point->delete();

        return redirect()->route('points.index')
                         ->with('success', 'تم حذف سجل النقاط.');
    }
}
