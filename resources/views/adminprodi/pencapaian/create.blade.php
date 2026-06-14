@extends('adminprodi.layouts.app')

@section('title', 'Tambah Target IKU Tahunan - Admin Prodi')
@section('page_title', 'Tambah Target IKU')
@section('page_subtitle', 'Tentukan parameter pencapaian target IKU tahunan baru')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">Form Target IKU - Tahun {{ $tahun }}</h3>

    <form action="{{ route('adminprodi.pencapaian.store') }}" method="POST" class="form-layout-container">
        @csrf

        <input type="hidden" name="tahun" value="{{ $tahun }}">

        <!-- IKU Select -->
        <div class="form-group-custom">
            <label for="id_iku" class="form-label-custom">Pilih Indikator IKU (Yang belum diatur tahun ini)</label>
            <select id="id_iku" name="id_iku" class="form-select-custom @error('id_iku') is-invalid @enderror" required>
                <option value="">-- Pilih Indikator --</option>
                @foreach($iku as $item)
                    <option value="{{ $item->id }}" {{ old('id_iku') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_iku }} (Default: {{ $item->target ?? '-' }})
                    </option>
                @endforeach
            </select>
            @error('id_iku')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Target Value -->
            <div class="form-group-custom">
                <label for="target" class="form-label-custom">Nilai Target (Angka)</label>
                <input type="text" 
                       id="target" 
                       name="target" 
                       value="{{ old('target') }}" 
                       placeholder="Contoh: 80 atau 5" 
                       class="form-input-custom @error('target') is-invalid @enderror" 
                       required>
                <small style="color: #64748b; font-size: 0.72rem; margin-top: 2px;">Masukkan nilai numeriknya saja.</small>
                @error('target')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>

            <!-- Satuan -->
            <div class="form-group-custom">
                <label for="satuan" class="form-label-custom">Satuan Target</label>
                <select id="satuan" name="satuan" class="form-select-custom @error('satuan') is-invalid @enderror" required>
                    <option value="persen" {{ old('satuan') == 'persen' ? 'selected' : '' }}>Persen (%)</option>
                    <option value="angka" {{ old('satuan') == 'angka' ? 'selected' : '' }}>Angka (Absolut)</option>
                </select>
                @error('satuan')
                    <span class="form-error-custom">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Objek Target -->
        <div class="form-group-custom">
            <label for="objek" class="form-label-custom">Objek Sasaran / Pembagi</label>
            <select id="objek" name="objek" class="form-select-custom @error('objek') is-invalid @enderror" required>
                <option value="mahasiswa" {{ old('objek', 'mahasiswa') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa (menggunakan data profil jumlah mahasiswa)</option>
                <option value="dosen" {{ old('objek') == 'dosen' ? 'selected' : '' }}>Dosen (menggunakan data profil jumlah dosen)</option>
                <option value="lainnya" {{ old('objek') == 'lainnya' ? 'selected' : '' }}>Lainnya / Tidak Ada Pembagi</option>
            </select>
            <small style="color: #64748b; font-size: 0.72rem; margin-top: 2px;">Jika memilih persen, target nyata akan dihitung dengan rumus: <code>(target / 100) * total mahasiswa/dosen</code>.</small>
            @error('objek')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Keterangan -->
        <div class="form-group-custom">
            <label for="keterangan" class="form-label-custom">Keterangan / Catatan Tambahan</label>
            <textarea id="keterangan" 
                      name="keterangan" 
                      rows="3" 
                      placeholder="Masukkan catatan pendukung pencapaian target ini..." 
                      class="form-input-custom @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="form-footer-actions">
            <a href="{{ route('adminprodi.pencapaian.index', ['tahun' => $tahun]) }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Target</button>
        </div>
    </form>
</div>
@endsection
