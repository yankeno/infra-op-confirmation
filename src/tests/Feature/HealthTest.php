<?php

namespace Tests\Feature;

use Tests\TestCase;

class HealthTest extends TestCase
{
    public function test_health_returns_ok(): void
    {
        $this->getJson('/health')
            ->assertOk()
            ->assertExactJson([
                'status' => 'ok',
            ]);
    }

    public function test_whoami_returns_server_and_request_information(): void
    {
        $this->getJson('/whoami', [
            'X-Forwarded-For' => '203.0.113.10',
            'X-Forwarded-Proto' => 'https',
        ])
            ->assertOk()
            ->assertJsonPath('request.headers.x-forwarded-for', '203.0.113.10')
            ->assertJsonPath('request.headers.x-forwarded-proto', 'https');
    }
}
