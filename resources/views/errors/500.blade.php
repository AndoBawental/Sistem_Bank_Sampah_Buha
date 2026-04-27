@extends('errors.layout')

@section('accent-color', '#e74a3b')
@section('accent-bg', 'rgba(231,74,59,.08)')
@section('title', '500 – Server Error')

@section('extra-styles')
<style>
    /* Subtle pulsing animation for the icon */
    .pulse {
        animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .7; transform: scale(.95); }
    }
</style>
@endsection

@section('content')
    <div class="badge">
        <svg width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 4a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 5zm0 5.5a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5z"/>
        </svg>
        HTTP 500
    </div>

    <div class="icon-wrap pulse" style="background: rgba(231,74,59,.08);">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#e74a3b">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
    </div>

    <div class="code" style="background: linear-gradient(135deg, #e74a3b, #c0392b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        500
    </div>

    <h1 class="title">Terjadi Kesalahan Server</h1>

    <p class="message">
        Server mengalami masalah saat memproses permintaan Anda.<br>
        Tim kami sudah diberitahu. Silakan coba lagi dalam beberapa saat.
    </p>

    <div class="actions">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Ke Dashboard
        </a>
        <button onclick="window.location.reload()" class="btn btn-primary" style="background:#e74a3b;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="23 4 23 10 17 10"/>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
            </svg>
            Coba Lagi
        </button>
    </div>

    {{-- Tampilkan detail error HANYA di environment lokal/development --}}
    @if(config('app.debug') && isset($exception))
        <hr class="divider">
        <details style="text-align:left; margin-top: 8px;">
            <summary style="font-size:13px; font-weight:600; color:#e74a3b; cursor:pointer; user-select:none; margin-bottom:12px;">
                Detail Error (Debug Mode)
            </summary>
            <div style="background:#fff5f5; border:1px solid #fecaca; border-radius:10px; padding:16px; font-size:12px; line-height:1.7; color:#5a5c69; overflow-x:auto;">
                <p><strong>Pesan:</strong> {{ $exception->getMessage() }}</p>
                <p><strong>File:</strong> {{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
                <pre style="margin-top:12px; white-space:pre-wrap; word-break:break-all; font-size:11px;">{{ $exception->getTraceAsString() }}</pre>
            </div>
        </details>
    @endif
@endsection