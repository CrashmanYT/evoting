<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Imports\VotersImport;

class ParticipantController extends Controller
{
    public function index()
    {
        $participants = Participant::all();
        return view('admin.participants', compact('participants'));
    }

    public function create()
    {
        return view('admin.participants.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nis' => 'required|unique:participants,nis',
            'name' => 'required',
            'class' => 'required',
        ]);

        $participant = new Participant();
        $participant->nis = $request->nis;
        $participant->name = $request->name;
        $participant->class = $request->class;
        $participant->voted = false;
        $participant->save();

        return back()->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $participant = Participant::findOrFail($id);
        return view('admin.participants.edit', compact('participant'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nis' => 'required|unique:participants,nis,' . $id,
            'name' => 'required',
            'class' => 'required',
        ]);

        $participant = Participant::findOrFail($id);
        $participant->nis = $request->nis;
        $participant->name = $request->name;
        $participant->class = $request->class;
        $participant->save();

        return back()->with('success', 'Data peserta berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $participant = Participant::findOrFail($id);
        $participant->delete();

        return back()->with('success', 'Peserta berhasil dihapus!');
    }

    public function import(Request $request)
    {
        // Validasi request
        $request->validate([
            'filepicker' => 'required|file|mimes:xlsx,xls|max:2048'
        ], [
            'filepicker.required' => 'File Excel tidak ditemukan.',
            'filepicker.file' => 'Upload harus berupa file.',
            'filepicker.mimes' => 'File harus berformat Excel (.xlsx atau .xls).',
            'filepicker.max' => 'Ukuran file maksimal 2MB.'
        ]);

        try {
            if (!$request->hasFile('filepicker')) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            $file = $request->file('filepicker');
            if (!$file->isValid()) {
                return back()->with('error', 'File tidak valid.');
            }

            DB::beginTransaction();
            
            $import = new VotersImport;
            $import->import($file);

            DB::commit();
            return back()->with('success', 'Data peserta berhasil diimpor!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             DB::rollBack();
             return back()->with('error', 'Validasi gagal: ' . implode(", ", array_map(function($failure) {
                 return "Baris {$failure->row()}: " . implode(', ', $failure->errors());
             }, $e->failures())));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Error:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
