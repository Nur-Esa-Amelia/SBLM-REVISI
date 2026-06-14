@extends('adminprodi.layouts.app')

@section('title', 'Edit Jenis Bukti - Admin Prodi')
@section('page_title', 'Edit Jenis Bukti')
@section('page_subtitle', 'Perbarui klasifikasi dokumen bukti pengisian IKU')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">Form Edit Jenis Bukti IKU</h3>

    <form action="{{ route('adminprodi.bukti.update', $bukti->id) }}" method="POST" class="form-layout-container">
        @csrf
        @method('PUT')

        <!-- IKU Select -->
        <div class="form-group-custom">
            <label for="id_iku" class="form-label-custom">Pilih IKU Terkait</label>
            <select id="id_iku" name="id_iku" class="form-select-custom @error('id_iku') is-invalid @enderror" required>
                <option value="">-- Pilih Indikator IKU --</option>
                @foreach($iku as $item)
                    <option value="{{ $item->id }}" {{ old('id_iku', $bukti->id_iku) == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_iku }} (Kategori: {{ $item->kategori->nama_kategori }})
                    </option>
                @endforeach
            </select>
            @error('id_iku')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Nama Bukti -->
        <div class="form-group-custom">
            <label for="nama_bukti" class="form-label-custom">Nama Jenis Bukti Dokumen</label>
            <input type="text" 
                   id="nama_bukti" 
                   name="nama_bukti" 
                   value="{{ old('nama_bukti', $bukti->nama_bukti) }}" 
                   placeholder="Contoh: Sertifikat Juara Mahasiswa, SK Rektor, URL Publikasi Scopus" 
                   class="form-input-custom @error('nama_bukti') is-invalid @enderror" 
                   required>
            @error('nama_bukti')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="form-group-custom">
            <label for="deskripsi" class="form-label-custom">Keterangan / Deskripsi Bukti</label>
            <textarea id="deskripsi" 
                      name="deskripsi" 
                      rows="4" 
                      placeholder="Masukkan penjelasan format file (PDF/JPG), link drive, atau instruksi pengisian dokumen..." 
                      class="form-input-custom @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $bukti->deskripsi) }}</textarea>
            @error('deskripsi')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="form-footer-actions">
            <a href="{{ route('adminprodi.bukti.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Perbarui Jenis Bukti</button>
        </div>
    </form>
</div>
@endsection
