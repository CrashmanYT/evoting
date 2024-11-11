<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    // Tentukan nama tabel (jika tidak sesuai dengan penamaan default)
    protected $table = 'participants';

    // Tentukan kolom-kolom yang bisa diisi
    protected $fillable = [
        'nis',
        'name',
        'class',
        'voted'
    ];

    // Relasi dengan tabel votes (satu peserta bisa memiliki satu suara)
    public function vote()
    {
        return $this->hasOne(Vote::class);
    }
}
