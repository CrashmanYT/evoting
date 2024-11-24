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

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $participant = Participant::findOrFail($id);
            Log::info('Attempting to delete participant', [
                'id' => $id,
                'participant' => $participant->toArray()
            ]);
            
            // Check if participant can be deleted (add your conditions here)
            if ($participant->voted) {
                throw new \Exception('Tidak dapat menghapus peserta yang sudah melakukan voting.');
            }
            
            $participant->delete();
            
            DB::commit();
            Log::info('Participant deleted successfully', ['id' => $id]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peserta berhasil dihapus!'
                ]);
            }

            return back()->with('success', 'Peserta berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting participant', [
                'id' => $id,
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

    public function destroyAll()
    {
        try {
            DB::beginTransaction();
            
            // Check if any participant has voted
            $votedParticipants = Participant::where('voted', true)->count();
            if ($votedParticipants > 0) {
                throw new \Exception('Tidak dapat menghapus semua data karena ada peserta yang sudah melakukan voting.');
            }
            
            // Get total participants before deletion
            $totalParticipants = Participant::count();
            
            // Delete related votes first
            DB::table('votes')->whereIn('participant_id', function($query) {
                $query->select('id')->from('participants');
            })->delete();
            
            // Now delete all participants
            Participant::query()->delete();
            
            DB::commit();
            Log::info('All participants deleted successfully', [
                'total_deleted' => $totalParticipants
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Semua data peserta berhasil dihapus!'
                ]);
            }

            return back()->with('success', 'Semua data peserta berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting all participants', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
            
            return back()->with('error', 'Gagal menghapus semua data: ' . $e->getMessage());
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
