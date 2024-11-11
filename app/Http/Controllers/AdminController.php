<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    // Menampilkan halaman pengaturan admin
    public function index()
    {
        $admin = Admin::first();
        return view('admin.dashboard', compact('admin'));
    }

    // Mengupdate batas voting
    public function updateVotingLimit(Request $request)
    {
        $request->validate([
            'voting_limit' => 'required|integer|min:1'
        ]);

        $admin = Admin::first();
        $admin->update(['voting_limit' => $request->voting_limit]);

        return redirect()->back()->with('success', 'Batas voting berhasil diperbarui.');
    }

    // Mengelola admin (contoh update profil admin)
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email,' . auth()->id()
        ]);

        $admin = Admin::first();
        $admin->update($request->only('name', 'email'));

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function settings() {
        $admin = Admin::first();
        return view('admin.settings', compact('admin'));
    }
}
