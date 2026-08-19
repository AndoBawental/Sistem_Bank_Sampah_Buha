@extends('errors.layout')

@section('accent-color', '#4e73df')
@section('accent-bg', 'rgba(78,115,223,.08)')
@section('title', '404 – Halaman Tidak Ditemukan')

@section('content')
    <div class="badge">
        <svg width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 4a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 5zm0 5.5a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5z"/>
        </svg>
        HTTP 404
    </div>

    <div class="icon-wrap" style="background: rgba(78,115,223,.08);">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#4e73df">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
            <path d="M8 11h6M11 8v6" stroke-linecap="round"/>
        </svg>
    </div>

    <div class="code" style="background: linear-gradient(135deg, #4e73df, #2e59d9); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        404
    </div>

    <h1 class="title">Halaman Tidak Ditemukan</h1>

    <p class="message">
        Halaman yang Anda cari tidak ada atau sudah dipindahkan.<br>
        Periksa kembali URL atau gunakan navigasi di bawah ini.
    </p>

    <div class="actions">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali
        </a>
       
    </div>
@endsection