@extends('adminprodi.layouts.app')

@section('title', 'Pengaturan System - Admin Prodi')
@section('page_title', 'Pengaturan System')
@section('page_subtitle', 'Kelola rentang tahun akademik, tahun aktif, dan data profil mahasiswa/dosen prodi')

@section('content')
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">Profil & Parameter Program Studi</h3>

    <form action="{{ route('adminprodi.pengaturan.store') }}" method="POST" class="form-layout-container">
        @csrf

        @if(auth()->user()->role === 'admin_p2mp')
            <input type="hidden" name="prodi_id" value="{{ $selectedProdiId }}">
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Tahun Mulai -->
            <div class="form-group-custom">
                <label for="tahun_mulai" class="form-label-custom">Tahun Mulai Rentang Sistem</label>
                <input type="number" 
                       id="tahun_mulai" 
                       name="tahun_mulai" 
                       value="{{ old('tahun_mulai', $pengaturan->tahun_mulai) }}" 
                       placeholder="Contoh: 2026" 
                       class="form-input-custom @error('tahun_mulai') is-invalid @enderror" 
                       required>
                @error('tahun_mulai')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tahun Selesai -->
            <div class="form-group-custom">
                <label for="tahun_selesai" class="form-label-custom">Tahun Selesai Rentang Sistem</label>
                <input type="number" 
                       id="tahun_selesai" 
                       name="tahun_selesai" 
                       value="{{ old('tahun_selesai', $pengaturan->tahun_selesai) }}" 
                       placeholder="Contoh: 2030" 
                       class="form-input-custom @error('tahun_selesai') is-invalid @enderror" 
                       required>
                @error('tahun_selesai')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>
        </div>

        @if(auth()->user()->role === 'admin_p2mp' && isset($prodis))
            <!-- Pilih Program Studi untuk Dikonfigurasi -->
            <div class="form-group-custom">
                <label for="prodi_id_select" class="form-label-custom">Pilih Program Studi untuk Dikonfigurasi</label>
                <select id="prodi_id_select" name="prodi_id_select" class="form-select-custom" onchange="window.location.href='{{ route('adminprodi.pengaturan.index') }}?prodi_id=' + this.value">
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ $selectedProdiId == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Tahun Aktif -->
        <div class="form-group-custom">
            <label for="tahun_aktif" class="form-label-custom">Tahun Akademik Aktif Saat Ini</label>
            <input type="number" 
                   id="tahun_aktif" 
                   name="tahun_aktif" 
                   value="{{ old('tahun_aktif', $pengaturan->tahun_aktif) }}" 
                   placeholder="Contoh: 2026" 
                   class="form-input-custom @error('tahun_aktif') is-invalid @enderror" 
                   required>
            <small style="color: #64748b; font-size: 0.75rem; margin-top: 2px;">Tahun aktif digunakan sebagai default filter monitoring di seluruh dashboard dosen dan pimpinan.</small>
            @error('tahun_aktif')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Jumlah Mahasiswa -->
            <div class="form-group-custom">
                <label for="jml_mahasiswa" class="form-label-custom">Total Mahasiswa Aktif</label>
                <input type="number" 
                       id="jml_mahasiswa" 
                       name="jml_mahasiswa" 
                       value="{{ old('jml_mahasiswa', $pengaturan->jml_mahasiswa) }}" 
                       placeholder="Contoh: 350" 
                       class="form-input-custom @error('jml_mahasiswa') is-invalid @enderror" 
                       required>
                <small style="color: #64748b; font-size: 0.75rem; margin-top: 2px;">Digunakan untuk menghitung persentase target IKU berbasis mahasiswa.</small>
                @error('jml_mahasiswa')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Jumlah Dosen -->
            <div class="form-group-custom">
                <label for="jml_dosen" class="form-label-custom">Total Dosen Aktif</label>
                <input type="number" 
                       id="jml_dosen" 
                       name="jml_dosen" 
                       value="{{ old('jml_dosen', $pengaturan->jml_dosen) }}" 
                       placeholder="Contoh: 15" 
                       class="form-input-custom @error('jml_dosen') is-invalid @enderror" 
                       required>
                <small style="color: #64748b; font-size: 0.75rem; margin-top: 2px;">Digunakan untuk menghitung persentase target IKU berbasis dosen.</small>
                @error('jml_dosen')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Submit actions -->
        <div class="form-footer-actions">
            <button type="submit" class="btn btn-primary">Simpan Konfigurasi</button>
        </div>
    </form>
</div>
@endsection
