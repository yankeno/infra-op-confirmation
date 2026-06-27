<?php

namespace Tests\Feature;

use Tests\TestCase;

class SessionProbeTest extends TestCase
{
    public function test_session_counter_increments_between_requests(): void
    {
        $this->get('/session')
            ->assertOk()
            ->assertSee('アクセス回数')
            ->assertSee('1');

        $this->get('/session')
            ->assertOk()
            ->assertSee('2');
    }

    public function test_display_name_is_stored_in_session(): void
    {
        $this->post('/session', [
            'display_name' => 'Tokyo operator',
        ])->assertRedirect(route('session.show'));

        $this->get('/session')
            ->assertOk()
            ->assertSee('Tokyo operator');
    }
}
