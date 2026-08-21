@extends('adminp2mp.layouts.app')

@section('title', 'Edit User - Sistem Early Warning IKU/IKT')
@section('page_title', 'Perbarui Pengguna')
@section('page_subtitle', 'Edit informasi detail akun pengguna')

@section('content')
<div class="form-layout-container">
    <!-- Back Link -->
   
    <!-- Form Card -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border);">
            <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">Edit Informasi Pengguna</h3>
            <span class="badge-custom badge-cyan">ID: #{{ $user->id }}</span>
        </div>

        <form action="{{ route('adminp2mp.users.update', $user->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="form-group-custom">
                <label for="name" class="form-label-custom">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="form-input-custom">
                @error('name')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group-custom">
                <label for="email" class="form-label-custom">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="form-input-custom">
                @error('email')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group-custom">
                <label for="password" class="form-label-custom">Kata Sandi Baru <span style="font-size: 0.65rem; color: var(--text-muted); text-transform: none;">(opsional)</span></label>
                <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="form-input-custom">
                <p style="font-size: 0.65rem; color: var(--text-muted); margin-top: 4px;">Biarkan kosong untuk mempertahankan password lama.</p>
                @error('password')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Role Select -->
            <div class="form-group-custom">
                <label for="role" class="form-label-custom">Hak Akses / Role</label>
                <select name="role" id="role" required class="form-select-custom">
                    @foreach($roles as $key => $value)
                        <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                    <option value="" disabled>Pilih Program Studi</option>
                    @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ old('prodi_id', $user->prodi_id) == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }} ({{ $p->kode_prodi }})</option>
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
                    Perbarui User
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

