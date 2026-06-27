<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Infra Probe Board' }}</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f5f7f8;
            --panel: #ffffff;
            --text: #172026;
            --muted: #66737c;
            --line: #d9e0e4;
            --ok: #147d4f;
            --bad: #b42318;
            --accent: #245d7a;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.5;
        }
        a { color: var(--accent); text-decoration: none; }
        a:hover { text-decoration: underline; }
        .shell { max-width: 1120px; margin: 0 auto; padding: 24px; }
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }
        .brand { font-size: 20px; font-weight: 700; color: var(--text); }
        .nav { display: flex; flex-wrap: wrap; gap: 8px; }
        .nav a, .button {
            display: inline-flex;
            align-items: center;
            min-height: 36px;
            padding: 7px 12px;
            border: 1px solid var(--line);
            border-radius: 6px;
            background: var(--panel);
            color: var(--text);
            font: inherit;
            cursor: pointer;
        }
        .nav a:hover, .button:hover { border-color: #9fb1bb; text-decoration: none; }
        .button.primary { background: var(--accent); color: #fff; border-color: var(--accent); }
        .button.danger { color: var(--bad); }
        h1 { margin: 0 0 16px; font-size: 28px; line-height: 1.2; }
        h2 { margin: 0 0 12px; font-size: 18px; }
        .grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
        .grid.two { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 18px;
        }
        .status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
        }
        .dot { width: 10px; height: 10px; border-radius: 999px; background: var(--bad); }
        .dot.ok { background: var(--ok); }
        .muted { color: var(--muted); }
        .kv { width: 100%; border-collapse: collapse; }
        .kv th, .kv td {
            padding: 9px 0;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: top;
            overflow-wrap: anywhere;
        }
        .kv th { width: 180px; color: var(--muted); font-weight: 600; }
        .flash {
            margin-bottom: 16px;
            padding: 12px 14px;
            border: 1px solid #b8dcc9;
            border-radius: 6px;
            background: #edf8f1;
            color: #145c37;
        }
        .errors {
            margin-bottom: 16px;
            padding: 12px 14px;
            border: 1px solid #f3b6b0;
            border-radius: 6px;
            background: #fff1f0;
            color: var(--bad);
        }
        .form { display: grid; gap: 12px; }
        label { display: grid; gap: 6px; font-weight: 700; }
        input, textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 10px 12px;
            font: inherit;
            background: #fff;
        }
        textarea { min-height: 120px; resize: vertical; }
        .list { display: grid; gap: 10px; }
        .item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid var(--line);
        }
        .actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        code {
            padding: 2px 5px;
            border-radius: 4px;
            background: #eef2f4;
            font-size: 0.92em;
        }
        @media (max-width: 760px) {
            .shell { padding: 16px; }
            .topbar { align-items: flex-start; flex-direction: column; }
            .grid, .grid.two { grid-template-columns: 1fr; }
            .item { align-items: flex-start; flex-direction: column; }
            .kv th { width: 120px; }
        }
    </style>
</head>
<body>
    <div class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('dashboard') }}">Infra Probe Board</a>
            <nav class="nav" aria-label="main">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('notes.index') }}">Notes</a>
                <a href="{{ route('files.index') }}">Files</a>
                <a href="{{ route('session.show') }}">Session</a>
                <a href="{{ route('health') }}">Health</a>
                <a href="{{ route('whoami') }}">Whoami</a>
            </nav>
        </header>

        @if (session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
