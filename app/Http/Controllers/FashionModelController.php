<?php

namespace App\Http\Controllers;

use App\Models\FashionModel;
use Illuminate\Http\Request;

class FashionModelController extends Controller
{
    public function index(Request $request)
{
    if (!in_array(auth()->user()->role, ['designer', 'supplier','admin','customer'])) {
        abort(403, 'غير مصرح لك بعرض العارضات');
    }

    $query = FashionModel::query();

    if ($request->filled('country')) {
        $query->where('country', $request->country);
    }

    if ($request->filled('age')) {
        $query->where('age', $request->age);
    }

    $fashionModels = $query->latest()->paginate(10);

    $countries = FashionModel::select('country')->distinct()->pluck('country')->filter();

    return view('fashion_models.index', compact('fashionModels', 'countries'));
}

    

    public function create()
    {
        return view('fashion_models.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'age' => 'nullable|integer|min:10|max:100',
        'email' => 'nullable|email|max:255',
        'bio' => 'nullable|string',
        'instagram' => 'nullable|url',
        'country' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        'extra_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
    ]);

    $data = $request->only(['name', 'age', 'email', 'bio', 'instagram', 'country']);

    // الصورة الأساسية
    if ($request->hasFile('image')) {
        $filename = time() . '_' . $request->file('image')->getClientOriginalName();
        $data['image'] = $request->file('image')->storeAs('fashion_models', $filename, 'public');
    }

    $model = FashionModel::create($data);

    // صور إضافية
    if ($request->hasFile('extra_images')) {
        foreach ($request->file('extra_images') as $img) {
            $filename = time() . '_' . $img->getClientOriginalName();
            $path = $img->storeAs('fashion_models/gallery', $filename, 'public');

            $model->images()->create(['image_path' => $path]);
        }
    }

    return redirect()->route('fashion-models.index')->with('success', 'تمت إضافة العارضة بنجاح');
}

    

 public function show(FashionModel $fashionModel)
{
    if (!in_array(auth()->user()->role, ['designer', 'supplier','admin','customer'])) {
        abort(403, 'غير مصرح لك بعرض تفاصيل العارضة');
    }

    return view('fashion_models.show', compact('fashionModel'));
}

    

   // عرض فورم التعديل
public function edit(FashionModel $fashionModel)
{
    return view('fashion_models.edit', compact('fashionModel'));
}

// حفظ التعديلات
public function update(Request $request, FashionModel $fashionModel)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'age' => 'nullable|integer|min:10|max:100',
        'email' => 'nullable|email|max:255',
        'bio' => 'nullable|string',
        'instagram' => 'nullable|url',
        'country' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $fashionModel->name = $request->name;
    $fashionModel->age = $request->age;
    $fashionModel->email = $request->email;
    $fashionModel->bio = $request->bio;
    $fashionModel->instagram = $request->instagram;
    $fashionModel->country = $request->country;

    if ($request->hasFile('image')) {
        $fashionModel->image = $request->file('image')->store('fashion_models', 'public');
    }

    $fashionModel->save();

    return redirect()->route('fashion-models.index')->with('success', 'تم تحديث العارضة بنجاح');
}


public function destroy(FashionModel $fashionModel)
{
    $fashionModel->delete();
    return redirect()->route('fashion-models.index')->with('success', 'تم حذف العارضة');
}
}
