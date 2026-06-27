<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProbeFileController extends Controller
{
    private const PREFIX = 'infra-probe/uploads';

    public function index(): View
    {
        if (! $this->diskConfigured()) {
            return view('files.index', [
                'files' => collect(),
                'diskName' => $this->diskName(),
                'storageUnavailable' => $this->diskName().' disk is not configured.',
            ]);
        }

        $disk = Storage::disk($this->diskName());
        $files = collect($disk->files(self::PREFIX))
            ->map(function (string $path) use ($disk): array {
                return [
                    'path' => $path,
                    'name' => basename($path),
                    'size' => rescue(fn (): int => $disk->size($path), 0, report: false),
                    'last_modified' => rescue(fn (): string => date('Y-m-d H:i:s', $disk->lastModified($path)), 'unknown', report: false),
                ];
            })
            ->sortByDesc('last_modified')
            ->values();

        return view('files.index', [
            'files' => $files,
            'diskName' => $this->diskName(),
            'storageUnavailable' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! $this->diskConfigured()) {
            return redirect()->route('files.index')->withErrors([
                'file' => $this->diskName().' disk is not configured.',
            ]);
        }

        $validated = $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $file = $validated['file'];
        $originalName = $file->getClientOriginalName();
        $safeName = preg_replace('/[^A-Za-z0-9._-]+/', '_', $originalName) ?: 'upload.bin';
        $path = self::PREFIX.'/'.Str::uuid().'_'.$safeName;

        Storage::disk($this->diskName())->put($path, $file->getContent());

        return redirect()->route('files.index')->with('status', 'ファイルをアップロードしました。');
    }

    public function download(Request $request): StreamedResponse
    {
        abort_unless($this->diskConfigured(), 503);

        $path = $this->validatedPath($request);

        return Storage::disk($this->diskName())->download($path, basename($path));
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (! $this->diskConfigured()) {
            return redirect()->route('files.index')->withErrors([
                'path' => $this->diskName().' disk is not configured.',
            ]);
        }

        $path = $this->validatedPath($request);

        Storage::disk($this->diskName())->delete($path);

        return redirect()->route('files.index')->with('status', 'ファイルを削除しました。');
    }

    private function validatedPath(Request $request): string
    {
        $path = $request->string('path')->toString();

        abort_unless(str_starts_with($path, self::PREFIX.'/'), 404);

        return $path;
    }

    private function diskName(): string
    {
        return (string) config('filesystems.default', 'local');
    }

    private function diskConfigured(): bool
    {
        if ($this->diskName() !== 's3') {
            return true;
        }

        return filled(config('filesystems.disks.s3.bucket'));
    }
}
