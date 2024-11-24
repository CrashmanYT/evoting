<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Participant;
use App\Models\Candidate;

class AdminController extends Controller
{
    // Menampilkan halaman pengaturan admin
    public function index()
    {
        // Hitung total peserta
        $totalParticipants = Participant::count();
        
        // Hitung yang sudah voting
        $totalVoted = Participant::where('voted', true)->count();
        
        // Hitung yang belum voting
        $totalNotVoted = $totalParticipants - $totalVoted;
        
        // Hitung persentase partisipasi
        $participationRate = $totalParticipants > 0 
            ? round(($totalVoted / $totalParticipants) * 100, 2) 
            : 0;

        // Ambil hasil voting per kandidat
        $votingResults = Candidate::withCount('votes')
            ->orderByDesc('votes_count')
            ->get()
            ->map(function ($candidate) use ($totalVoted) {
                $percentage = $totalVoted > 0 
                    ? round(($candidate->votes_count / $totalVoted) * 100, 2) 
                    : 0;
                    
                return [
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'votes_count' => $candidate->votes_count,
                    'percentage' => $percentage
                ];
            });

        $admin = Admin::first();
        return view('admin.dashboard', compact(
            'admin',
            'totalParticipants',
            'totalVoted',
            'totalNotVoted',
            'participationRate',
            'votingResults'
        ));
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
        $admin = Admin::first();
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:admins,email,' . $admin->id
        ]);

        $admin->update($request->only('name', 'email'));

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function settings() {
        $admin = Admin::first();
        return view('admin.settings', compact('admin'));
    }
}
