@extends('adminp2mp.layouts.app')

@section('title', 'Tambah User Baru - Sistem Early Warning IKU')
@section('page_title', 'Tambah Pengguna Baru')
@section('page_subtitle', 'Tambah user baru dan tentukan hak akses peran pengguna')

@section('content')
<div class="form-layout-container">
    <!-- Back Link -->
    <div>
        <a href="{{ route('adminp2mp.users.index') }}" style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.75rem; font-weight: 600; color: #94a3b8; text-decoration: none; transition: all 0.2s ease;">
            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Daftar User
        </a>
    </div>

    <!-- Form Card -->
    <div class="card">
        <h3 style="font-size: 0.95rem; font-weight: 700; color: #ffffff; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #1e293b;">Form Registrasi Pengguna Baru</h3>

        <form action="{{ route('adminp2mp.users.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf

            <!-- Name -->
            <div class="form-group-custom">
                <label for="name" class="form-label-custom">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Dr. Budi Santoso" required class="form-input-custom">
                @error('name')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group-custom">
                <label for="email" class="form-label-custom">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="name@domain.com" required class="form-input-custom">
                @error('email')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group-custom">
                <label for="password" class="form-label-custom">Kata Sandi</label>
                <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required class="form-input-custom">
                @error('password')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Role Select -->
            <div class="form-group-custom">
                <label for="role" class="form-label-custom">Hak Akses / Role</label>
                <select name="role" id="role" required class="form-select-custom">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Hak Akses</option>
                    @foreach($roles as $key => $value)
                        <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
                @error('role')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Prodi Select -->
            <div id="prodi-select-wrapper" class="form-group-custom">
                <label for="prodi_id" class="form-label-custom">Program Studi</label>
                <select name="prodi_id" id="prodi_id" class="form-select-custom">
                    <option value="" disabled {{ old('prodi_id') ? '' : 'selected' }}>Pilih Program Studi</option>
                    @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }} ({{ $p->kode_prodi }})</option>
                    @endforeach
                </select>
                @error('prodi_id')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="form-footer-actions">
                <a href="{{ route('adminp2mp.users.index') }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const roleSelect = document.getElementById('role');
        const prodiWrapper = document.getElementById('prodi-select-wrapper');
        const prodiSelect = document.getElementById('prodi_id');

        const toggleProdiSelect = () => {
            const role = roleSelect.value;
            if (role === 'admin_p2mp') {
                prodiWrapper.style.display = 'none';
                prodiSelect.disabled = true;
                prodiSelect.value = '';
            } else {
                prodiWrapper.style.display = 'flex';
                prodiSelect.disabled = false;
            }
        };

        if (roleSelect) {
            roleSelect.addEventListener('change', toggleProdiSelect);
            toggleProdiSelect();
        }
    });
</script>
@endsection

