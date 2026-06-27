<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SessionProbeController extends Controller
{
    public function show(Request $request): View
    {
        $count = (int) $request->session()->get('probe_access_count', 0) + 1;

        $request->session()->put('probe_access_count', $count);
        $request->session()->put('probe_last_accessed_at', now()->toISOString());

        return view('session.show', [
            'count' => $count,
            'lastAccessedAt' => $request->session()->get('probe_last_accessed_at'),
            'displayName' => $request->session()->get('probe_display_name', ''),
            'sessionId' => $request->session()->getId(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['nullable', 'string', 'max:80'],
        ]);

        $request->session()->put('probe_display_name', $validated['display_name'] ?? '');

        return redirect()->route('session.show')->with('status', 'セッションの表示名を保存しました。');
    }
}
