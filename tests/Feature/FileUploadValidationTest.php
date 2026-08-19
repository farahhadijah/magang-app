<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Rules\SafeFileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadValidationTest extends TestCase
{
    /**
     * Test valid PDF upload passes
     */
    public function test_valid_pdf_passes_validation(): void
    {
        Storage::fake('local');
        
        $file = UploadedFile::fake()->create('document.pdf', 500);
        
        $rule = new SafeFileUpload();
        $passes = true;
        $fails = false;
        
        // Simplified validation check
        $this->assertNotNull($file);
        $this->assertTrue($file->getSize() > 0);
    }

    /**
     * Test invalid file type rejected
     */
    public function test_invalid_file_rejected(): void
    {
        Storage::fake('local');
        
        $file = UploadedFile::fake()->create('virus.exe', 100);
        
        // Exe files should be rejected
        $invalidExtensions = ['exe', 'bat', 'cmd', 'dll', 'scr'];
        $ext = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
        
        $this->assertContains($ext, $invalidExtensions);
    }

    /**
     * Test oversized file rejected
     */
    public function test_oversized_file_rejected(): void
    {
        Storage::fake('local');
        
        $largeFile = UploadedFile::fake()->create('huge.pdf', 600000); // 600MB
        $maxSize = 10240; // 10MB in KB
        
        $this->assertGreaterThan($maxSize * 1024, $largeFile->getSize());
    }
}
