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
        $admin = Admin::first();
        $voting_limit = $admin->voting_limit;

        // Cek NIS terlebih dahulu
        $participant = Participant::where('nis', $request->nis)->first();
        
        if (!$participant) {
            return back()
                ->withInput()
                ->with('error', 'NIS tidak terdaftar dalam sistem.')
                ->with('showErrorModal', true);
        }

        // Cek status voting
        if ($participant->voted) {
            return back()
                ->withInput()
                ->with('error', 'Maaf, Anda sudah melakukan voting sebelumnya. Silakan meninggalkan ruang voting.')
                ->with('showErrorModal', true);
        }
        
        $request->validate([
            'nis' => 'required|exists:participants,nis',
            'candidate_ids' => [
                'required',
                'array',
                'size:' . $voting_limit,  // Harus memilih sesuai voting_limit
                function ($attribute, $value, $fail) {
                    if (count(array_unique($value)) !== count($value)) {
                        $fail('Tidak boleh memilih kandidat yang sama lebih dari satu kali.');
                    }
                },
            ],
            'candidate_ids.*' => 'exists:candidates,id',
        ], [
            'candidate_ids.required' => 'Anda harus memilih kandidat.',
            'candidate_ids.size' => 'Anda harus memilih tepat ' . $voting_limit . ' kandidat.',
            'nis.required' => 'NIS wajib diisi.',
            'nis.exists' => 'NIS tidak terdaftar dalam sistem.',
            'candidate_ids.*.exists' => 'Salah satu kandidat yang dipilih tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            // Simpan vote untuk setiap kandidat yang dipilih
            foreach ($request->candidate_ids as $candidateId) {
                Vote::create([
                    'participant_id' => $participant->id,
                    'candidate_id' => $candidateId
                ]);
            }

            // Update status voting peserta
            $participant->update(['voted' => true]);

            DB::commit();
            
            // Redirect kembali ke halaman voting dengan pesan sukses
            return redirect()
                ->route('vote')
                ->with('success', 'Terima kasih! Suara Anda telah berhasil disimpan. Silakan meninggalkan ruang voting.')
                ->with('showModal', true);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in voting process', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses voting. Silakan coba lagi.')
                ->with('showErrorModal', true);
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
