<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $table = 'candidates';

    protected $fillable = [
        'no_urut',
        'name',
        'description',
        'photo_url'
    ];

    // Relasi dengan tabel votes (satu kandidat bisa mendapatkan banyak suara)
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
