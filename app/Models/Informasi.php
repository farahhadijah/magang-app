<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Informasi extends Model
{
    protected $table = 'informasi';

    protected $fillable = [
        'judul',
        'jenis',
        'target_role',
        'deskripsi',
        'file_path',
        'uploaded_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * User yang mengupload informasi.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scope untuk mengambil informasi yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope berdasarkan role.
     */
    public function scopeForRole($query, string $role)
    {
        return $query->where('target_role', $role);
    }
}