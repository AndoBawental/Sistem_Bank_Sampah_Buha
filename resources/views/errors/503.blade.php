@extends('errors.layout')

@section('accent-color', '#1cc88a')
@section('accent-bg', 'rgba(28,200,138,.08)')
@section('title', '503 – Dalam Pemeliharaan')

@section('extra-styles')
<style>
    .gear-wrap {
        position: relative;
        display: inline-block;
        width: 64px;
        height: 64px;
        margin-bottom: 24px;
    }
    .gear {
        position: absolute;
        animation: spin 4s linear infinite;
    }
    .gear-sm {
        width: 28px; height: 28px;
        bottom: 0; right: 0;
        animation-direction: reverse;
        animation-duration: 3s;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
    <div class="badge">
        <svg width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 4a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 5zm0 5.5a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5z"/>
        </svg>
        HTTP 503
    </div>

    <div class="gear-wrap">
        {{-- Gear besar --}}
        <svg class="gear" width="48" height="48" fill="none" stroke="#1cc88a" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
        </svg>
        {{-- Gear kecil --}}
        <svg class="gear gear-sm" width="28" height="28" fill="none" stroke="#b7b9cc" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="3"/>
            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
        </svg>
    </div>

    <div class="code">503</div>

    <h1 class="title">Sedang dalam Pemeliharaan</h1>

    <p class="message">
        Sistem sedang dalam proses pemeliharaan untuk meningkatkan layanan.<br>
        Mohon coba kembali dalam beberapa saat. Terima kasih atas kesabaran Anda.
    </p>

    @if(isset($exception) && $exception->getMessage())
        <p style="font-size:13px; color:#1cc88a; font-weight:600; margin-bottom: 24px;">
            {{ $exception->getMessage() }}
        </p>
    @endif

    <div class="actions">
        <button onclick="window.location.reload()" class="btn btn-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="23 4 23 10 17 10"/>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
            </svg>
            Coba Lagi
        </button>
    </div>
@endsection