<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('role');  // استلام قيمة الدور
        
        $users = User::when($filter, function ($query) use ($filter) {
            $query->where('role', $filter);
        })->paginate(10);
    
        $counts = [
            'admin' => User::where('role', 'admin')->count(),
            'customer' => User::where('role', 'customer')->count(),
            'designer' => User::where('role', 'designer')->count(),
            'supplier' => User::where('role', 'supplier')->count(),
        ];
    
        return view('admin.users.index', compact('users', 'counts', 'filter'));
    }
    
    
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,customer,designer,supplier',
        ]);
    
        $user = User::findOrFail($id);
        $user->role = $request->input('role'); // نستخدم input وليس مباشرة
        $user->save();
    
        return redirect()->route('admin.users.index')
                         ->with('success', 'تم تحديث دور المستخدم بنجاح.');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
    
        if (auth()->id() === $user->id) {
            return back()->with('error', 'لا يمكنك حذف حسابك الشخصي.');
        }
    
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'لا يمكن حذف آخر مدير في النظام.');
            }
        }
    
        $user->delete();
    
        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم بنجاح.');
    }
    
    public function togglePostPermission($id)
{
    $user = User::findOrFail($id);
    $user->can_post_products = !$user->can_post_products;
    $user->save();

    return back()->with('success', 'تم تحديث صلاحية النشر.');
}



}
