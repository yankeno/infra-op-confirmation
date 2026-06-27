@extends('layouts.app', ['title' => 'Dashboard | Infra Probe Board'])

@section('content')
    <h1>インフラ状態ダッシュボード</h1>

    <section class="grid" style="margin-bottom: 16px;">
        @foreach ($checks as $check)
            <article class="panel">
                <h2>{{ strtoupper($check['name']) }}</h2>
                <div class="status">
                    <span class="dot {{ $check['ok'] ? 'ok' : '' }}"></span>
                    {{ $check['ok'] ? 'OK' : 'FAILED' }}
                </div>
                <p class="muted">{{ $check['message'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="grid two">
        <article class="panel">
            <h2>アプリサーバ</h2>
            <table class="kv">
                @foreach ($server as $key => $value)
                    <tr>
                        <th>{{ $key }}</th>
                        <td><code>{{ $value }}</code></td>
                    </tr>
                @endforeach
            </table>
        </article>

        <article class="panel">
            <h2>リクエスト情報</h2>
            <table class="kv">
                @foreach ($requestInfo as $key => $value)
                    <tr>
                        <th>{{ $key }}</th>
                        <td>{{ $value ?: 'none' }}</td>
                    </tr>
                @endforeach
            </table>
        </article>
    </section>
@endsection
