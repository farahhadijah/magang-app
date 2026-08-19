<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {   
        $table->id();
        
        // User yang melakukan action
        $table->unsignedBigInteger('user_id')->nullable();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        
        // Action type
        $table->string('action'); // 'file_upload', 'file_delete', 'login', dll
        $table->string('status')->default('success'); // 'success', 'failed', 'blocked'
        
        // File details (jika ada)
        $table->string('file_name')->nullable();
        $table->string('file_type')->nullable();
        $table->integer('file_size')->nullable(); // in bytes
        $table->string('stored_path')->nullable();
        
        // Security details
        $table->string('ip_address')->nullable();
        $table->text('user_agent')->nullable();
        $table->text('reason')->nullable(); // Alasan jika blocked/failed
        
        // Metadata
        $table->json('metadata')->nullable();
        
        $table->timestamps();
        
        // Indexes untuk query cepat
        $table->index('user_id');
        $table->index('action');
        $table->index('status');
        $table->index('created_at');
    });
    }
};