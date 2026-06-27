@extends('layouts.app', ['title' => 'Session | Infra Probe Board'])

@section('content')
    <h1>Redisセッション確認</h1>

    <section class="grid two">
        <article class="panel">
            <h2>セッション値</h2>
            <table class="kv">
                <tr>
                    <th>アクセス回数</th>
                    <td>{{ $count }}</td>
                </tr>
                <tr>
                    <th>最終アクセス</th>
                    <td>{{ $lastAccessedAt }}</td>
                </tr>
                <tr>
                    <th>表示名</th>
                    <td>{{ $displayName ?: '未設定' }}</td>
                </tr>
                <tr>
                    <th>セッションID</th>
                    <td><code>{{ $sessionId }}</code></td>
                </tr>
            </table>
        </article>

        <article class="panel">
            <h2>表示名を保存</h2>
            <form class="form" method="post" action="{{ route('session.update') }}">
                @csrf
                <label>
                    表示名
                    <input name="display_name" value="{{ old('display_name', $displayName) }}" maxlength="80">
                </label>
                <button class="button primary" type="submit">保存</button>
            </form>
        </article>
    </section>
@endsection
