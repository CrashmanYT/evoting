<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    public function checkVoted($id)
    {
        try {
            $participant = Participant::findOrFail($id);
            return response()->json([
                'has_voted' => $participant->voted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Peserta tidak ditemukan'
            ], 404);
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            DB::beginTransaction();
            
            $participant = Participant::findOrFail($id);
            $forceDelete = $request->input('force_delete', false);
            
            if ($participant->voted && !$forceDelete) {
                throw new \Exception('Peserta ini sudah melakukan voting. Centang opsi force delete untuk menghapus paksa.');
            }
            
            if ($participant->voted) {
                // Hapus data voting terlebih dahulu
                DB::table('votes')->where('participant_id', $id)->delete();
            }
            
            $participant->delete();
            
            DB::commit();
            
            $message = $participant->voted 
                ? 'Berhasil menghapus paksa peserta beserta data voting.' 
                : 'Berhasil menghapus peserta.';
            
            Log::info('Participant deleted', [
                'participant_id' => $id,
                'force_delete' => $forceDelete,
                'had_voted' => $participant->voted
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting participant', [
                'participant_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            
            return back()->with('error', 'Gagal menghapus peserta: ' . $e->getMessage());
        }
    }

    public function destroyAll(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $forceDelete = $request->input('force_delete', false);
            
            if ($forceDelete) {
                // Get total count before deletion
                $totalParticipants = Participant::count();
                $votedCount = Participant::where('voted', true)->count();
                
                // Delete all votes first
                DB::table('votes')->delete();
                
                // Delete all participants
                Participant::query()->delete();
                
                DB::commit();
                
                $message = "Berhasil menghapus paksa semua data peserta ({$totalParticipants} peserta, termasuk {$votedCount} peserta yang sudah voting).";
                
                Log::info('All participants force deleted', [
                    'total_deleted' => $totalParticipants,
                    'voted_deleted' => $votedCount
                ]);
            } else {
                // Get participants that haven't voted
                $nonVotedParticipants = Participant::where('voted', false)->get();
                
                if ($nonVotedParticipants->isEmpty()) {
                    throw new \Exception('Tidak ada data peserta yang dapat dihapus karena semua peserta sudah melakukan voting.');
                }
                
                // Count participants before deletion
                $totalToDelete = $nonVotedParticipants->count();
                $totalVoted = Participant::where('voted', true)->count();
                
                // Delete non-voted participants
                Participant::where('voted', false)->delete();
                
                DB::commit();
                
                $message = "Berhasil menghapus {$totalToDelete} data peserta yang belum melakukan voting.";
                if ($totalVoted > 0) {
                    $message .= " {$totalVoted} peserta yang sudah melakukan voting tidak dihapus.";
                }
                
                Log::info('Non-voted participants deleted', [
                    'deleted_count' => $totalToDelete,
                    'remaining_voted' => $totalVoted
                ]);
            }

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting participants', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'force_delete' => $forceDelete
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            
            return back()->with('error', $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        Log::info('Import method called');
        
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
            Log::info('Request validation passed');
            
            if (!$request->hasFile('filepicker')) {
                Log::error('No file found in request');
                return back()->with('error', 'File tidak ditemukan.');
            }
            $file = $request->file('filepicker');
            Log::info('File details:', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'path' => $file->getPathname()
            ]);

            if (!$file->isValid()) {
                Log::error('Invalid file');
                return back()->with('error', 'File tidak valid.');
            }

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            Log::info('Created Xlsx reader');
            
            $spreadsheet = $reader->load($file->getPathname());
            Log::info('Loaded spreadsheet');
            
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            Log::info('Converted worksheet to array', ['row_count' => count($rows)]);
            // Remove header row
            $header = array_shift($rows);
            Log::info('Headers:', ['headers' => $header]);

            // Normalize header names
            $header = array_map('strtolower', $header);

            DB::beginTransaction();
            Log::info('Started database transaction');
            
            $successCount = 0;
            $errorRows = [];

            // Process each row
            foreach ($rows as $index => $row) {
                Log::info('Processing row', ['index' => $index, 'data' => $row]);
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    Log::info('Skipping empty row', ['index' => $index]);
                    continue;
                }

                $data = array_combine($header, $row);
                
                // Validate required fields
                if (empty($data['nis']) || empty($data['nama']) || empty($data['kelas'])) {
                    $errorRows[] = 'Baris ' . ($index + 2) . ': Data tidak lengkap';
                    Log::warning('Incomplete data', ['row' => $index + 2, 'data' => $data]);
                    continue;
                }

                // Check for existing NIS
                $existing = Participant::where('nis', $data['nis'])->first();
                if ($existing) {
                    $errorRows[] = 'Baris ' . ($index + 2) . ': NIS ' . $data['nis'] . ' sudah terdaftar';
                    Log::warning('Duplicate NIS', ['row' => $index + 2, 'nis' => $data['nis']]);
                    continue;
                }

                try {
                    // Create new participant
                    Participant::create([
                        'nis' => $data['nis'],
                        'name' => $data['nama'],
                        'class' => $data['kelas'],
                        'voted' => false
                    ]);
                    $successCount++;
                    Log::info('Created participant', ['row' => $index + 2, 'nis' => $data['nis']]);
                } catch (\Exception $e) {
                    $errorRows[] = 'Baris ' . ($index + 2) . ': ' . $e->getMessage();
                    Log::error('Error creating participant', [
                        'row' => $index + 2,
                        'error' => $e->getMessage(),
                        'data' => $data
                    ]);
                }
            }

            DB::commit();
            Log::info('Transaction committed', [
                'success_count' => $successCount,
                'error_count' => count($errorRows)
            ]);

            // Prepare response message
            $message = 'Berhasil mengimpor ' . $successCount . ' data peserta.';
            if (!empty($errorRows)) {
                $message .= "\nBeberapa data gagal diimpor:\n" . implode("\n", $errorRows);
                return back()->with('warning', $message);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
