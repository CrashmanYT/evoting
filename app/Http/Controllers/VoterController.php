<?php

namespace App\Http\Controllers;

use App\Imports\VotersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VoterController extends Controller
{
    public function index()
    {
        return view('admin.voters.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new VotersImport, $request->file('file'));
            return redirect()->back()->with('success', 'Data pemilih berhasil diimpor!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
