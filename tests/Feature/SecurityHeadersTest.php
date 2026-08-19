<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_security_headers_present(): void
    {
        $response = $this->get('/');

        $this->assertTrue($response->headers->has('X-Frame-Options'));
        $this->assertEquals('DENY', $response->headers->get('X-Frame-Options'));
    }

    public function test_mime_type_protection(): void
    {
        $response = $this->get('/');
        
        $this->assertTrue($response->headers->has('X-Content-Type-Options'));
        $this->assertEquals('nosniff', $response->headers->get('X-Content-Type-Options'));
    }
}
