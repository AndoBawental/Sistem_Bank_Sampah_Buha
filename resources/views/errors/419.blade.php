@extends('errors.layout')

@section('accent-color', '#f6c23e')
@section('accent-bg', 'rgba(246,194,62,.1)')
@section('title', '419 – Sesi Kedaluwarsa')

@section('content')
    <div class="badge">
        <svg width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 4a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 5zm0 5.5a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5z"/>
        </svg>
        HTTP 419
    </div>

    <div class="icon-wrap" style="background: rgba(246,194,62,.1);">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#f6c23e">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="12 6 12 12 16 14"/>
        </svg>
    </div>

    <div class="code" style="background: linear-gradient(135deg, #f6c23e, #dda20a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        419
    </div>

    <h1 class="title">Sesi Kedaluwarsa</h1>

    <p class="message">
        Halaman ini sudah kedaluwarsa karena Anda terlalu lama tidak aktif.<br>
        Silakan muat ulang halaman dan coba lagi.
    </p>

    <div class="actions">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali
        </a>
        <button onclick="window.location.reload()" class="btn btn-primary" style="background:#f6c23e; color:#856404;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="23 4 23 10 17 10"/>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
            </svg>
            Muat Ulang
        </button>
    </div>
@endsection