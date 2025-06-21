<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vacancy;
use Auth;

class VacancyController extends Controller
{
    public function index()
    {
        $vacancies = Vacancy::latest()->get();
        if(Auth::user()->role == 'admin')
        return view('admin.vacancies.index', compact('vacancies'));
        return view('vacancies.index', compact('vacancies'));
    }

    public function create()
    {
        return view('admin.vacancies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        Vacancy::create($request->only('title', 'description'));

        return redirect()->route('admin.vacancies.index')->with('success', 'تم إنشاء الشاغر بنجاح');
    }
public function edit(Vacancy $vacancy)
{
    return view('admin.vacancies.edit', compact('vacancy'));
}

public function update(Request $request, Vacancy $vacancy)
{
    $request->validate([
        'title' => 'required',
        'description' => 'required',
    ]);

    $vacancy->update($request->only('title', 'description'));

    return redirect()->route('admin.vacancies.index')->with('success', 'تم تعديل الشاغر بنجاح');
}

    public function show(Vacancy $vacancy)
    {
        if(Auth::user()->role == 'admin')
        return view('admin.vacancies.show', compact('vacancy'));
        return view('vacancies.show', compact('vacancy'));
    }

    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return redirect()->route('admin.vacancies.index')->with('success', 'تم حذف الشاغر بنجاح');
    }
}
