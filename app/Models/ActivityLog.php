<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin; // added this line to import the Admin model

class ActivityLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'description'
    ];

    protected $casts = [
        'admin_id' => 'integer'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
