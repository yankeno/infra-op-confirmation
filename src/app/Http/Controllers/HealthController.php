<?php

namespace App\Http\Controllers;

use App\Services\InfraProbeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function health(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function ready(InfraProbeService $probe): JsonResponse
    {
        $checks = $probe->readiness();
        $healthy = $probe->allHealthy($checks);

        return response()->json([
            'status' => $healthy ? 'ok' : 'failed',
            'checks' => $checks,
        ], $healthy ? 200 : 503);
    }

    public function whoami(Request $request): JsonResponse
    {
        return response()->json([
            'server' => [
                'id' => config('app.server_id') ?: gethostname() ?: 'unknown',
                'hostname' => gethostname() ?: 'unknown',
                'environment' => app()->environment(),
                'time' => now()->toISOString(),
            ],
            'request' => [
                'ip' => $request->ip(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'headers' => [
                    'host' => $request->header('host'),
                    'user-agent' => $request->header('user-agent'),
                    'x-forwarded-for' => $request->header('x-forwarded-for'),
                    'x-forwarded-proto' => $request->header('x-forwarded-proto'),
                    'x-forwarded-host' => $request->header('x-forwarded-host'),
                ],
            ],
        ]);
    }
}
