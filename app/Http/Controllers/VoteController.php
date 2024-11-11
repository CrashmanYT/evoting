<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Admin;
use App\Models\Participant;
use App\Models\Candidate;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    // Fungsi untuk menampilkan halaman voting
    public function index()
    {
        $admin = Admin::first();
        $voting_limit = $admin->voting_limit;
        $candidates = Candidate::all();
        return view('voting.vote', compact('candidates', 'voting_limit'));
    }

    // Fungsi untuk memproses voting
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|exists:participants,nis',
            'candidate_ids' => 'required|array',           // Pastikan kandidat dipilih dalam bentuk array
            'candidate_ids.*' => 'exists:candidates,id',   // Setiap kandidat harus valid
        ]);

        $participant = Participant::where('nis', $request->nis)->first();
        $admin = Admin::first(); // Ambil admin untuk mendapatkan `voting_limit`

        // Cek apakah jumlah kandidat yang dipilih melebihi batas voting
        if (count($request->candidate_ids) > $admin->voting_limit) {
            return redirect()->back()->with('error', 'Anda hanya dapat memilih maksimal ' . $admin->voting_limit . ' kandidat.');
        }

        // Cek apakah peserta sudah melakukan voting
        if ($participant->voted) {
            return redirect()->back()->with('error', 'Anda sudah melakukan voting.');
        }

        // Simpan setiap suara untuk kandidat yang dipilih
        foreach ($request->candidate_ids as $candidate_id) {
            Vote::create([
                'participant_id' => $participant->id,
                'candidate_id' => $candidate_id
            ]);
    }

    // Tandai peserta sebagai sudah melakukan voting
    $participant->update(['voted' => true]);

    return redirect()->route('results')->with('success', 'Voting berhasil.');
}

}
