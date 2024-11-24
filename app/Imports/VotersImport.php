<?php

namespace App\Imports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;

class VotersImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        try {
            return new Participant([
                'nis' => $row['nis'],
                'name' => $row['nama'],
                'class' => $row['kelas'],
                'voted' => false,
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating participant:', [
                'row' => $row,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function rules(): array
    {
        return [
            'nis' => 'required|unique:participants,nis',
            'nama' => 'required',
            'kelas' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nis.required' => 'Kolom NIS wajib diisi',
            'nis.unique' => 'NIS sudah terdaftar',
            'nama.required' => 'Kolom Nama wajib diisi',
            'kelas.required' => 'Kolom Kelas wajib diisi',
        ];
    }
}
