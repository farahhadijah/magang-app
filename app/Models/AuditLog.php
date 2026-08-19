<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    
    protected $fillable = [
        'user_id',
        'action',
        'status',
        'file_name',
        'file_type',
        'file_size',
        'stored_path',
        'ip_address',
        'user_agent',
        'reason',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}