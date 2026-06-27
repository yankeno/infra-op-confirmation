@extends('layouts.app', ['title' => $note->title.' | Infra Probe Board'])

@section('content')
    <h1>{{ $note->title }}</h1>

    <article class="panel">
        <table class="kv">
            <tr>
                <th>ID</th>
                <td>{{ $note->id }}</td>
            </tr>
            <tr>
                <th>作成日時</th>
                <td>{{ $note->created_at->format('Y-m-d H:i:s') }}</td>
            </tr>
            <tr>
                <th>更新日時</th>
                <td>{{ $note->updated_at->format('Y-m-d H:i:s') }}</td>
            </tr>
        </table>

        <div style="margin-top: 16px; white-space: pre-wrap;">{{ $note->body ?: '本文なし' }}</div>
    </article>

    <div class="actions" style="margin-top: 16px;">
        <a class="button" href="{{ route('notes.index') }}">一覧へ戻る</a>
        <form method="post" action="{{ route('notes.destroy', $note) }}">
            @csrf
            @method('delete')
            <button class="button danger" type="submit">削除</button>
        </form>
    </div>
@endsection
