<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;

class CandidateController extends Controller
{
    // Menampilkan daftar kandidat
    public function index()
    {
        $candidates = Candidate::all();
        return view('admin.candidates', compact('candidates'));
    }

    // Menampilkan form untuk menambah kandidat baru
    public function create()
    {
        return view('admin.candidates.create');
    }

    // Menyimpan kandidat baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'photo_url' => 'nullable|url'
        ]);

        Candidate::create($request->all());

        return redirect()->route('candidates.index')->with('success', 'Kandidat berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit kandidat
    public function edit($id)
    {
        $candidate = Candidate::findOrFail($id);
        return view('admin.candidates.edit', compact('candidate'));
    }

    // Mengupdate data kandidat
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'photo_url' => 'nullable|url'
        ]);

        $candidate = Candidate::findOrFail($id);
        $candidate->update($request->all());

        return redirect()->route('candidates.index')->with('success', 'Kandidat berhasil diperbarui.');
    }

    // Menghapus kandidat
    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->delete();

        return redirect()->route('candidates.index')->with('success', 'Kandidat berhasil dihapus.');
    }
}
