<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('profiles.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('profiles.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        return view('profiles.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // ✅ تحقق من الحقول
        $data = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'website' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

          'current_password' => 'required_with:new_password|string',
'new_password' => 'nullable|string|min:6|confirmed',

        ]);

        // ✅ تحديث الاسم الكامل
        $fullName = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? ''));
        if (!empty($fullName)) {
            $user->name = $fullName;
        }

        // ✅ تحديث كلمة المرور إن وُجدت
  if ($request->filled('new_password') && $request->filled('current_password')) {
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة.']);
    }

    $user->password = Hash::make($request->new_password);
}


        $user->save();

        // ✅ تحديث الملف الشخصي
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('profile_images', 'public');
        }

        $profile->fill($data)->save();

        return back()->with('success', 'تم تحديث الملف الشخصي وكلمة المرور بنجاح.');
    }
}
