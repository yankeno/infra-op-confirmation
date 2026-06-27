<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class InfraProbeService
{
    /**
     * @return array{name: string, ok: bool, message: string}
     */
    public function checkDatabase(): array
    {
        try {
            DB::select('select 1');

            return $this->result('db', true, 'connected');
        } catch (Throwable $exception) {
            return $this->result('db', false, $exception->getMessage());
        }
    }

    /**
     * @return array{name: string, ok: bool, message: string}
     */
    public function checkRedis(): array
    {
        try {
            Redis::connection()->ping();

            return $this->result('redis', true, 'connected');
        } catch (Throwable $exception) {
            return $this->result('redis', false, $exception->getMessage());
        }
    }

    /**
     * @return array{name: string, ok: bool, message: string}
     */
    public function checkStorage(): array
    {
        $path = 'infra-probe/health/'.Str::uuid().'.txt';
        $diskName = $this->diskName();

        if (! $this->diskConfigured()) {
            return $this->result('storage', false, $diskName.' disk is not configured');
        }

        try {
            $disk = Storage::disk($diskName);
            $content = now()->toISOString();

            if ($disk->put($path, $content) !== true) {
                return $this->result('storage', false, $diskName.' write failed');
            }

            if ($disk->get($path) !== $content) {
                return $this->result('storage', false, $diskName.' read mismatch');
            }

            if ($disk->delete($path) !== true) {
                return $this->result('storage', false, $diskName.' delete failed');
            }

            return $this->result('storage', true, $diskName.' read/write/delete ok');
        } catch (Throwable $exception) {
            return $this->result('storage', false, $exception->getMessage());
        } finally {
            try {
                Storage::disk($diskName)->delete($path);
            } catch (Throwable) {
                //
            }
        }
    }

    /**
     * @return array{db: array{name: string, ok: bool, message: string}, storage: array{name: string, ok: bool, message: string}, redis: array{name: string, ok: bool, message: string}}
     */
    public function readiness(): array
    {
        return [
            'db' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'redis' => $this->checkRedis(),
        ];
    }

    /**
     * @param  array<string, array{name: string, ok: bool, message: string}>  $checks
     */
    public function allHealthy(array $checks): bool
    {
        foreach ($checks as $check) {
            if ($check['ok'] !== true) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array{name: string, ok: bool, message: string}
     */
    private function result(string $name, bool $ok, string $message): array
    {
        return [
            'name' => $name,
            'ok' => $ok,
            'message' => $message,
        ];
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
