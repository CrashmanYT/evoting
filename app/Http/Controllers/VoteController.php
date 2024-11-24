<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Admin;
use App\Models\Participant;
use App\Models\Candidate;
use App\Imports\VotersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $admin = Admin::first(); // Ambil admin untuk mendapatkan `voting_limit`
        
        $request->validate([
            'nis' => 'required|exists:participants,nis',
            'candidate_ids' => 'required|array|size:' . $admin->voting_limit,  // Harus memilih sesuai voting_limit
            'candidate_ids.*' => 'exists:candidates,id',   // Setiap kandidat harus valid
        ], [
            'candidate_ids.size' => 'Anda harus memilih tepat ' . $admin->voting_limit . ' kandidat.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.exists' => 'NIS tidak terdaftar dalam sistem.',
        ]);

        $participant = Participant::where('nis', $request->nis)->first();

        // Cek apakah peserta sudah melakukan voting
        if ($participant->voted) {
            return back()
                ->withInput()
                ->with('error', 'Maaf, Anda sudah melakukan voting sebelumnya.');
        }

        // Cek apakah ada kandidat yang duplikat
        if (count(array_unique($request->candidate_ids)) !== $admin->voting_limit) {
            return back()
                ->withInput()
                ->with('error', 'Anda tidak boleh memilih kandidat yang sama lebih dari satu kali.');
        }

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Simpan setiap suara untuk kandidat yang dipilih
            foreach ($request->candidate_ids as $candidate_id) {
                Vote::create([
                    'participant_id' => $participant->id,
                    'candidate_id' => $candidate_id
                ]);
            }

            // Update status voted peserta
            $participant->update(['voted' => true]);

            // Commit transaksi
            DB::commit();

            return redirect()
                ->route('results')
                ->with('success', 'Terima kasih! Suara Anda telah berhasil disimpan.');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            
            Log::error('Voting error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan suara Anda. Silakan coba lagi.');
        }
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'filepicker' => 'required|file|mimes:xlsx,xls|max:2048'
            ], [
                'filepicker.required' => 'File Excel wajib diunggah.',
                'filepicker.file' => 'Upload harus berupa file.',
                'filepicker.mimes' => 'File harus berformat Excel (.xlsx atau .xls).',
                'filepicker.max' => 'Ukuran file tidak boleh lebih dari 2MB.'
            ]);

            if (!$request->hasFile('filepicker')) {
                return back()->with('error', 'File Excel tidak ditemukan.');
            }

            $file = $request->file('filepicker');
            
            if (!$file->isValid()) {
                return back()->with('error', 'File tidak valid atau rusak.');
            }

            DB::beginTransaction();
            try {
                Excel::import(new VotersImport, $file);
                DB::commit();
                return back()->with('success', 'Data peserta berhasil diimpor!');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Excel import error: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunggah file.');
        }
    }
}
