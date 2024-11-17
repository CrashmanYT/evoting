<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Storage;
use function Laravel\Prompts\alert;

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
//        @dd($request);
        $request->validate([
            'no_urut' => 'required',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'photo_url' => 'nullable|image'
        ]);
//        @dd($request->all());

        $fileName = null;
        if ($request->hasFile('photo_url')) {
            $image = $request->file('photo_url');

            // Ensure directory exists
            if (!Storage::disk('public')->exists('candidates')) {
                Storage::disk('public')->makeDirectory('candidates');
            }

            // Clean file name and ensure uniqueness
            $originalName = $image->getClientOriginalName();


            if (!$originalName) return back()->withErrors(['photo_url' => 'Failed to upload image.']);

            $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $fileName = time() . '_' . uniqid() . '_' . $cleanName . '.' . $extension;


            if (empty($fileName)) return back()->withErrors(['photo_url' => 'Failed to upload image.']);

            // Store the file
            try {
                Storage::disk('public')->put('candidates/' . $fileName, file_get_contents($image));
            } catch (\Exception $e) {

                return back()->withErrors(['photo_url' => 'Failed to upload image.']);
            }
        }

        $candidateData = [
            'no_urut' => $request->no_urut,
            'name' => $request->name,
            'description' => $request->description,
            'photo_url' => $fileName
        ];

        Candidate::create($candidateData);

        return redirect()->route('dashboard.candidates')->with('success', 'Kandidat berhasil ditambahkan.');
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

        return response()->json(['success' => true]);
    }
}
