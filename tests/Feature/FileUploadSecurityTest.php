<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FileUploadSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test audit log can be created
     */
    public function test_audit_log_created(): void
    {
        AuditLog::create([
            'user_id' => null,
            'action' => 'file_upload',
            'status' => 'success',
            'file_name' => 'test.pdf',
            'file_type' => 'application/pdf',
            'file_size' => 5000,
            'stored_path' => 'uploads/test.pdf',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test',
            'metadata' => ['jenis_dokumen' => 'KRS'],
        ]);

        $log = AuditLog::where('file_name', 'test.pdf')->first();
        
        $this->assertNotNull($log);
        $this->assertEquals('file_upload', $log->action);
        $this->assertEquals('success', $log->status);
    }

    /**
     * Test audit log captures metadata
     */
    public function test_audit_log_captures_metadata(): void
    {
        AuditLog::create([
            'action' => 'file_upload',
            'status' => 'success',
            'file_name' => 'doc.pdf',
            'file_size' => 2000,
            'stored_path' => 'uploads/doc.pdf',
            'metadata' => [
                'jenis_dokumen' => 'Pembayaran',
                'status_verifikasi' => 'pending'
            ],
        ]);

        $log = AuditLog::where('file_name', 'doc.pdf')->first();
        
        $this->assertEquals('Pembayaran', $log->metadata['jenis_dokumen']);
    }

    /**
     * Test audit log tracks different actions
     */
    public function test_audit_log_tracks_actions(): void
    {
        AuditLog::create([
            'action' => 'file_delete',
            'status' => 'success',
            'file_name' => 'old.pdf',
            'stored_path' => 'uploads/old.pdf',
        ]);

        $log = AuditLog::where('action', 'file_delete')->first();
        
        $this->assertNotNull($log);
        $this->assertEquals('success', $log->status);
    }
}