@extends('layouts.app', ['title' => 'Notes | Infra Probe Board'])

@section('content')
    <h1>RDS確認用メモ</h1>

    <section class="grid two">
        <article class="panel">
            <h2>メモ作成</h2>
            <form class="form" method="post" action="{{ route('notes.store') }}">
                @csrf
                <label>
                    タイトル
                    <input name="title" value="{{ old('title') }}" maxlength="255" required>
                </label>
                <label>
                    本文
                    <textarea name="body">{{ old('body') }}</textarea>
                </label>
                <button class="button primary" type="submit">作成</button>
            </form>
        </article>

        <article class="panel">
            <h2>メモ一覧</h2>
            <div class="list">
                @forelse ($notes as $note)
                    <div class="item">
                        <div>
                            <a href="{{ route('notes.show', $note) }}"><strong>{{ $note->title }}</strong></a>
                            <div class="muted">{{ $note->created_at->format('Y-m-d H:i:s') }}</div>
                        </div>
                        <form method="post" action="{{ route('notes.destroy', $note) }}">
                            @csrf
                            @method('delete')
                            <button class="button danger" type="submit">削除</button>
                        </form>
                    </div>
                @empty
                    <p class="muted">メモはまだありません。</p>
                @endforelse
            </div>

            {{ $notes->links() }}
        </article>
    </section>
@endsection
