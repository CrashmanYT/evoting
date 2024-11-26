<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Participant;
use App\Models\Candidate;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Menampilkan halaman pengaturan admin
    public function index()
    {
        // Hitung total peserta
        $totalParticipants = Participant::count();
        $totalVoted = Participant::where('voted', true)->count();
        $totalNotVoted = $totalParticipants - $totalVoted;
        
        // Hitung tingkat partisipasi
        $participationRate = $totalParticipants > 0 
            ? round(($totalVoted / $totalParticipants) * 100, 1)
            : 0;

        // Ambil hasil voting
        $votingResults = Candidate::select('candidates.name', DB::raw('COUNT(votes.id) as votes_count'))
            ->leftJoin('votes', 'candidates.id', '=', 'votes.candidate_id')
            ->groupBy('candidates.id', 'candidates.name')
            ->orderByDesc('votes_count')
            ->get()
            ->map(function($result) use ($totalVoted) {
                return [
                    'name' => $result->name,
                    'votes_count' => $result->votes_count,
                    'percentage' => $totalVoted > 0 ? round(($result->votes_count / $totalVoted) * 100, 1) : 0
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
