@extends('adminprodi.layouts.app')

@section('title', 'Tambah Data IKU/IKT - Admin Prodi')
@section('page_title', 'Tambah Data IKU/IKT')
@section('page_subtitle', 'Masukkan indikator kinerja utama baru ke dalam sistem')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">Form Tambah Data IKU/IKT</h3>

    <form action="{{ route('adminprodi.iku.store') }}" method="POST" class="form-layout-container">
        @csrf

        <!-- Kategori Select -->
        <div class="form-group-custom">
            <label for="id_kategori" class="form-label-custom">Kategori IKU/IKT</label>
            <select id="id_kategori" name="id_kategori" class="form-select-custom @error('id_kategori') is-invalid @enderror" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategori as $cat)
                    <option value="{{ $cat->id }}" {{ old('id_kategori') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nama_kategori }}
                    </option>
                @endforeach
            </select>
            @error('id_kategori')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Kode IKU/IKT -->
        <div class="form-group-custom">
            <label for="kode_iku" class="form-label-custom">Kode IKU/IKT</label>
            <input type="text" 
                   id="kode_iku" 
                   name="kode_iku" 
                   value="{{ old('kode_iku') }}" 
                   placeholder="Contoh: IKU/IKT-1.1" 
                   class="form-input-custom @error('kode_iku') is-invalid @enderror" 
                   required>
            @error('kode_iku')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nama IKU/IKT -->
        <div class="form-group-custom">
            <label for="nama_iku" class="form-label-custom">Nama / Judul Indikator Kinerja Utama (IKU/IKT)</label>
            <input type="text" 
                   id="nama_iku" 
                   name="nama_iku" 
                   value="{{ old('nama_iku') }}" 
                   placeholder="Contoh: Rata-rata IPK lulusan" 
                   class="form-input-custom @error('nama_iku') is-invalid @enderror" 
                   required>
            @error('nama_iku')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="form-group-custom">
            <label for="deskripsi" class="form-label-custom">Deskripsi Lengkap IKU/IKT</label>
            <textarea id="deskripsi" 
                      name="deskripsi" 
                      rows="4" 
                      placeholder="Masukkan detail penjelasan, rumus, atau kriteria pemenuhan IKU/IKT ini..." 
                      class="form-input-custom @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="form-footer-actions">
            <a href="{{ route('adminprodi.iku.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan IKU/IKT</button>
        </div>
    </form>
</div>
@endsection
