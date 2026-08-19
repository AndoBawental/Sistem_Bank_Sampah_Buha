@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="container-fluid px-2 px-md-4 mt-3 mb-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            
            {{-- Header --}}
            <div class="d-flex align-items-center gap-2 mb-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h5 class="mb-0 fw-bold">✏️ Edit Pengguna</h5>
                    <small class="text-muted">{{ $user->name }} | {{ $user->email }}</small>
                </div>
            </div>

            {{-- Form --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 p-md-4">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" id="userForm">
                        @csrf
                        @method('PUT')
                        
                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   placeholder="Masukkan nama lengkap" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   placeholder="contoh@email.com" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Password --}}
                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">
                                        Password Baru <small class="text-muted fw-normal">(opsional)</small>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               name="password" 
                                               placeholder="Kosongkan jika tidak diubah"
                                               aria-label="Password baru">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1" aria-label="Lihat password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Minimal 6 karakter</small>
                                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">
                                        Konfirmasi Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               name="password_confirmation" 
                                               placeholder="Ulangi password baru"
                                               aria-label="Konfirmasi password baru">
                                        <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1" aria-label="Lihat password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">
                                Role <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role', $userRole) == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Info --}}
                        <div class="mb-4 p-2 bg-light rounded-3 small text-muted">
                            <i class="fas fa-clock me-1"></i> 
                            Terdaftar: {{ $user->created_at->format('d M Y') }}
                            @if($user->updated_at != $user->created_at)
                                | Diperbarui: {{ $user->updated_at->format('d M Y') }}
                            @endif
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="button" class="btn btn-primary w-100 w-sm-auto rounded-pill px-4" onclick="confirmSubmit()">
                                <i class="fas fa-save me-1"></i> Update
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100 w-sm-auto rounded-pill px-4">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media (max-width: 575.98px) {
        .card-body { padding: 1rem !important; }
        h5 { font-size: 1rem; }
        .form-label { font-size: 0.8rem; }
        .form-control, .form-select { font-size: 0.85rem; }
        .btn { font-size: 0.85rem; padding: 0.5rem 1rem; }
        .bg-light.rounded-3 { font-size: 0.7rem; }
    }
    
    .card { transition: box-shadow 0.2s ease; }
    .card:hover { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08) !important; }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }

    /* Agar tombol mata tetap sejajar */
    .input-group .btn {
        border-color: #ced4da;
    }
    .input-group .btn:hover {
        background-color: #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Konfirmasi sebelum update
    function confirmSubmit() {
        Swal.fire({
            title: 'Update Pengguna?',
            text: 'Apakah perubahan sudah benar?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Update',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('userForm').submit();
            }
        });
    }
</script>
@endpush