<?php

namespace Tests\Feature;

use App\Models\Note;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_note_can_be_created_listed_shown_and_deleted(): void
    {
        $createResponse = $this->post('/notes', [
            'title' => 'RDS connection memo',
            'body' => 'Created from feature test.',
        ]);

        $note = Note::query()->firstOrFail();

        $createResponse->assertRedirect(route('notes.show', $note));
        $this->assertDatabaseHas('notes', [
            'title' => 'RDS connection memo',
        ]);

        $this->get('/notes')
            ->assertOk()
            ->assertSee('RDS connection memo');

        $this->get(route('notes.show', $note))
            ->assertOk()
            ->assertSee('Created from feature test.');

        $this->delete(route('notes.destroy', $note))
            ->assertRedirect(route('notes.index'));

        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
        ]);
    }
}
