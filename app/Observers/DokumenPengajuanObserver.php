<?php

namespace App\Observers;

use App\Models\DokumenPengajuan;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class DokumenPengajuanObserver
{
    /**
     * Track file upload saat created
     */
    public function created(DokumenPengajuan $dokumen): void
    {
        $this->logAudit($dokumen, 'file_upload', 'success');
    }

    /**
     * Track file changes saat updated
     */
    public function updated(DokumenPengajuan $dokumen): void
    {
        // Jika path_file berubah = file baru di-upload
        if ($dokumen->wasChanged('path_file')) {
            $this->logAudit($dokumen, 'file_update', 'success');
        }
    }

    /**
     * Track file deletion
     */
    public function deleted(DokumenPengajuan $dokumen): void
    {
        $this->logAudit($dokumen, 'file_delete', 'success');
    }

    /**
     * Log ke audit_logs table
     */
    private function logAudit(DokumenPengajuan $dokumen, string $action, string $status): void
    {
        try {
            // Get file info dari path
            $filePath = storage_path('app/' . $dokumen->path_file);
            $fileName = basename($dokumen->path_file);
            $fileSize = 0;
            $mimeType = null;

            // Get file size & mime type jika file ada
            if (file_exists($filePath)) {
                $fileSize = filesize($filePath);
                $mimeType = mime_content_type($filePath) ?: 'unknown';
            }

            // Get user info
            $userId = auth()->id();
            $ipAddress = Request::ip();
            $userAgent = Request::header('User-Agent');

            // Log to audit_logs
            AuditLog::create([
                'user_id' => $userId,
                'action' => $action,
                'status' => $status,
                'file_name' => $fileName,
                'file_type' => $mimeType,
                'file_size' => $fileSize,
                'stored_path' => $dokumen->path_file,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'metadata' => [
                    'jenis_dokumen' => $dokumen->jenis_dokumen,
                    'status_verifikasi' => $dokumen->status_verifikasi,
                    'id_pengajuan_pkl' => $dokumen->id_pengajuan_pkl,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('AuditLog Error: ' . $e->getMessage());
        }
    }
}