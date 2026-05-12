@extends('layouts.app', ['title' => 'Files | Infra Probe Board'])

@section('content')
    <h1>ファイルストレージ確認</h1>

    <section class="grid two">
        <article class="panel">
            <h2>アップロード</h2>
            <p class="muted">現在のdisk: <code>{{ $diskName }}</code></p>
            @if ($storageUnavailable)
                <p class="muted">{{ $storageUnavailable }}</p>
            @endif
            <form class="form" method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                @csrf
                <label>
                    ファイル
                    <input type="file" name="file" required>
                </label>
                <button class="button primary" type="submit">アップロード</button>
            </form>
        </article>

        <article class="panel">
            <h2>ファイル一覧</h2>
            <div class="list">
                @forelse ($files as $file)
                    <div class="item">
                        <div>
                            <strong>{{ $file['name'] }}</strong>
                            <div class="muted">
                                {{ number_format($file['size']) }} bytes /
                                {{ $file['last_modified'] }}
                            </div>
                            <div><code>{{ $file['path'] }}</code></div>
                        </div>
                        <div class="actions">
                            <a class="button" href="{{ route('files.download', ['path' => $file['path']]) }}">ダウンロード</a>
                            <form method="post" action="{{ route('files.destroy') }}">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="path" value="{{ $file['path'] }}">
                                <button class="button danger" type="submit">削除</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="muted">ファイルはまだありません。</p>
                @endforelse
            </div>
        </article>
    </section>
@endsection
