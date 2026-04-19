<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = Admin::paginate(15);
        return view('admin.admin-users.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admin-users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin_users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        Admin::create($validated);
        return redirect()->route('admin.admin-users.index')->with('success', 'Admin user created successfully!');
    }

    public function edit(Admin $adminUser)
    {
        return view('admin.admin-users.edit', compact('adminUser'));
    }

    public function update(Request $request, Admin $adminUser)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin_users,email,' . $adminUser->id,
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validated['password'] ?? null) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $adminUser->update($validated);
        return redirect()->route('admin.admin-users.index')->with('success', 'Admin user updated successfully!');
    }

    public function destroy(Admin $adminUser)
    {
        $adminUser->delete();
        return redirect()->route('admin.admin-users.index')->with('success', 'Admin user deleted successfully!');
    }
}
