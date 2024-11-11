<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Vote;

class RealTimeResultController extends Controller
{
    // Menampilkan hasil voting secara real-time
    public function index()
    {
        // Mengambil data kandidat beserta jumlah vote masing-masing
        $results = Candidate::withCount('votes')->get()->map(function ($candidate) {
            return [
                'name' => $candidate->name,
                'vote_count' => $candidate->votes_count
            ];
        });

        return view('results.index', compact('results'));
    }

    // Mengambil data hasil voting untuk refresh secara real-time via API (optional)
    public function apiResults()
    {
        $results = Candidate::withCount('votes')->get()->map(function ($candidate) {
            return [
                'name' => $candidate->name,
                'vote_count' => $candidate->votes_count
            ];
        });

        return response()->json($results);
    }
}
