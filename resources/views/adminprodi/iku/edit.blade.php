@extends('adminprodi.layouts.app')

@section('title', 'Edit Data IKU - Admin Prodi')
@section('page_title', 'Edit Data IKU')
@section('page_subtitle', 'Perbarui detail data indikator kinerja utama')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">Form Edit Data IKU</h3>

    <form action="{{ route('adminprodi.iku.update', $iku->id) }}" method="POST" class="form-layout-container">
        @csrf
        @method('PUT')

        <!-- Kategori Select -->
        <div class="form-group-custom">
            <label for="id_kategori" class="form-label-custom">Kategori IKU</label>
            <select id="id_kategori" name="id_kategori" class="form-select-custom @error('id_kategori') is-invalid @enderror" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategori as $cat)
                    <option value="{{ $cat->id }}" {{ old('id_kategori', $iku->id_kategori) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->nama_kategori }}
                    </option>
                @endforeach
            </select>
            @error('id_kategori')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nama IKU -->
        <div class="form-group-custom">
            <label for="nama_iku" class="form-label-custom">Nama / Judul Indikator Kinerja Utama (IKU)</label>
            <input type="text" 
                   id="nama_iku" 
                   name="nama_iku" 
                   value="{{ old('nama_iku', $iku->nama_iku) }}" 
                   placeholder="Contoh: Rata-rata IPK lulusan" 
                   class="form-input-custom @error('nama_iku') is-invalid @enderror" 
                   required>
            @error('nama_iku')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="form-group-custom">
            <label for="deskripsi" class="form-label-custom">Deskripsi Lengkap IKU</label>
            <textarea id="deskripsi" 
                      name="deskripsi" 
                      rows="4" 
                      placeholder="Masukkan detail penjelasan, rumus, atau kriteria pemenuhan IKU ini..." 
                      class="form-input-custom @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $iku->deskripsi) }}</textarea>
            @error('deskripsi')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="form-footer-actions">
            <a href="{{ route('adminprodi.iku.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Perbarui IKU</button>
        </div>
    </form>
</div>
@endsection
