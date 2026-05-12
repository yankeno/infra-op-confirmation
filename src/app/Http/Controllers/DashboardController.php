<?php

namespace App\Http\Controllers;

use App\Services\InfraProbeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, InfraProbeService $probe): View
    {
        return view('dashboard', [
            'checks' => $probe->readiness(),
            'server' => [
                'id' => config('app.server_id') ?: gethostname() ?: 'unknown',
                'hostname' => gethostname() ?: 'unknown',
                'environment' => app()->environment(),
                'time' => now()->toISOString(),
            ],
            'requestInfo' => [
                'ip' => $request->ip(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
                'x_forwarded_for' => $request->header('x-forwarded-for'),
                'x_forwarded_proto' => $request->header('x-forwarded-proto'),
                'x_forwarded_host' => $request->header('x-forwarded-host'),
            ],
        ]);
    }
}
