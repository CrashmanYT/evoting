<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    // Fungsi untuk menampilkan daftar peserta di halaman admin
    public function index()
    {
        $participants = Participant::all();
        return view('admin.participants', compact('participants'));
    }

    // Fungsi untuk menambahkan peserta baru
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:participants,nis',
            'name' => 'required',
            'class' => 'required' // Validate the class column
        ]);

        Participant::create($request->all());

        return redirect()->back()->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function create()
    {
        return view('admin.participants.create');
    }

    public function edit(Participant $participant) {
        return view('admin.participants.edit', compact('participant'));
    }

    public function update(Request $request, Participant $participant) {

    }

    public function destroy(Participant $participant) {
        $participant->delete();

        return redirect()->back()->with('success', 'Peserta berhasil dihapus.');
    }
}
