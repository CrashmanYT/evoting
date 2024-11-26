<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ActivityLog;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'voting_limit'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'voting_limit' => 'integer',
    ];

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
