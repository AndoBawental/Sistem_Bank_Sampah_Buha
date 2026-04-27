@extends('errors.layout')

@section('accent-color', '#e74a3b')
@section('accent-bg', 'rgba(231,74,59,.08)')
@section('title', '403 – Akses Ditolak')

@section('content')
    <div class="badge">
        <svg width="10" height="10" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 4a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 5zm0 5.5a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5z"/>
        </svg>
        HTTP 403
    </div>

    <div class="icon-wrap" style="background: rgba(231,74,59,.08);">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:#e74a3b">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            <circle cx="12" cy="16" r="1" fill="currentColor"/>
        </svg>
    </div>

    <div class="code" style="background: linear-gradient(135deg, #e74a3b, #c0392b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        403
    </div>

    <h1 class="title">Akses Ditolak</h1>

    <p class="message">
        Anda tidak memiliki izin untuk mengakses halaman ini.<br>
        Pastikan Anda login dengan akun yang memiliki hak akses yang sesuai.
    </p>

    <div class="actions">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-primary" style="background:#e74a3b;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Ke Dashboard
        </a>
    </div>
@endsection