<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProbeFileTest extends TestCase
{
    public function test_file_can_be_uploaded_listed_and_deleted_on_default_disk(): void
    {
        config(['filesystems.default' => 'local']);

        Storage::fake('local');

        $this->post('/files', [
            'file' => UploadedFile::fake()->createWithContent('probe.txt', 'storage probe'),
        ])->assertRedirect(route('files.index'));

        $paths = Storage::disk('local')->allFiles('infra-probe/uploads');

        $this->assertCount(1, $paths);
        Storage::disk('local')->assertExists($paths[0]);

        $this->get('/files')
            ->assertOk()
            ->assertSee('probe.txt');

        $this->delete(route('files.destroy'), [
            'path' => $paths[0],
        ])->assertRedirect(route('files.index'));

        Storage::disk('local')->assertMissing($paths[0]);
    }
}
