<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class ApplicationController extends Controller
{
    
  public function store(Request $request, $vacancy_id)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'message' => 'nullable',
        'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
    ]);

    $path = $request->file('cv')->store('cvs', 'public');

    
    $new = new Application();
    $new->vacancy_id = $vacancy_id;
    $new->name = $request->name;
    $new->email = $request->email;
    $new->message = $request->message;
    $new->cv_path = $path;
    $new->save();

    return back()->with('success', 'تم التقديم بنجاح');
}

}
