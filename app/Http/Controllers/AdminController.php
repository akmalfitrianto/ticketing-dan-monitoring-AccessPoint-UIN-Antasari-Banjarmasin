<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->withCount('tickets')
            ->latest()
            ->paginate(20);

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $unitKerjaList = UnitKerja::aktif()
            ->orderBy('nama')
            ->get();

        return view('admin.admins.create', compact('unitKerjaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'unit_kerja' => 'required|exists:unit_kerja,nama',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'admin';

        User::create($validated);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan!');
    }

    public function edit(User $admin)
    {
        // Ensure it's an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $unitKerjaList = UnitKerja::aktif()
            ->orderBy('nama')
            ->get();

        return view('admin.admins.edit', compact('admin', 'unitKerjaList'));
    }

    public function update(Request $request, User $admin)
    {
        // Ensure it's an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'unit_kerja' => 'required|exists:unit_kerja,nama',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin berhasil diupdate!');
    }

    public function destroy(User $admin)
    {
        // Ensure it's an admin
        if ($admin->role !== 'admin') {
            abort(404);
        }

        // Prevent deleting yourself
        if ($admin->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus akun Anda sendiri!');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin berhasil dihapus!');
    }
}