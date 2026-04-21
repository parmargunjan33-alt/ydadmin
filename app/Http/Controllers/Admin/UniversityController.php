<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::paginate(15);
        return view('admin.universities.index', compact('universities'));
    }

    public function create()
    {
        return view('admin.universities.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'city' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
            'display_order' => 'required|integer',
        ]);

        University::create($validated);
        return redirect()->route('admin.universities.index')->with('success', 'University created successfully!');
    }

    public function edit(University $university)
    {
        return view('admin.universities.form', compact('university'));
    }

    public function update(Request $request, University $university)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'city' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
            'display_order' => 'required|integer',
        ]);

        $university->update($validated);
        return redirect()->route('admin.universities.index')->with('success', 'University updated successfully!');
    }

    public function destroy(University $university)
    {
        $university->delete();
        return redirect()->route('admin.universities.index')->with('success', 'University deleted successfully!');
    }
}
