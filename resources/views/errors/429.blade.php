@extends('errors.layout')

@section('accent-color', '#fd7e14')
@section('accent-bg', 'rgba(253,126,20,.08)')
@section('title', '429 – Terlalu Banyak Permintaan')

@section('content')
    <div class="badge">
        <svg width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 4a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 5zm0 5.5a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5z"/>
        </svg>
        HTTP 429
    </div>

    <div class="icon-wrap" style="background: rgba(253,126,20,.08);">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#fd7e14">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
        </svg>
    </div>

    <div class="code" style="background: linear-gradient(135deg, #fd7e14, #e65c00); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        429
    </div>

    <h1 class="title">Terlalu Banyak Permintaan</h1>

    <p class="message">
        Anda telah melakukan terlalu banyak permintaan dalam waktu singkat.<br>
        Tunggu beberapa saat sebelum mencoba lagi.
    </p>

    <div class="actions">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Ke Dashboard
        </a>
        <button onclick="window.location.reload()" class="btn btn-primary" style="background:#fd7e14;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <polyline points="23 4 23 10 17 10"/>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
            </svg>
            Coba Lagi
        </button>
    </div>
@endsection