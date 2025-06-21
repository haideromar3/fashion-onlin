<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function destroy($id)
    {
        $image = ProductImage::findOrFail($id);

        // حذف من التخزين
        Storage::disk('public')->delete($image->image_path);

        // حذف من قاعدة البيانات
        $image->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح.');
    }
}
