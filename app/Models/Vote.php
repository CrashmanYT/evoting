<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'votes';

    protected $fillable = [
        'participant_id',
        'candidate_id'
    ];

    // Relasi dengan peserta (satu suara hanya milik satu peserta)
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    // Relasi dengan kandidat (satu suara hanya untuk satu kandidat)
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
