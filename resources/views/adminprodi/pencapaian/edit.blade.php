@extends('adminprodi.layouts.app')

@section('title', 'Edit Target IKU Tahunan - Admin Prodi')
@section('page_title', 'Edit Target IKU')
@section('page_subtitle', 'Perbarui nilai atau parameter pencapaian target IKU')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">Form Edit Target IKU - Tahun {{ $pencapaian->tahun }}</h3>

    <form action="{{ route('adminprodi.pencapaian.update', $pencapaian->id) }}" method="POST" class="form-layout-container">
        @csrf
        @method('PUT')

        <!-- Readonly IKU Name -->
        <div class="form-group-custom">
            <label class="form-label-custom">Indikator IKU Terpilih</label>
            <input type="text" 
                   value="{{ $pencapaian->iku->nama_iku }}" 
                   class="form-input-custom" 
                   style="background-color: rgba(30, 41, 59, 0.4); border-color: #1e293b; color: #94a3b8;" 
                   readonly>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Target Value -->
            <div class="form-group-custom">
                <label for="target" class="form-label-custom">Nilai Target (Angka)</label>
                <input type="text" 
                       id="target" 
                       name="target" 
                       value="{{ old('target', $pencapaian->target) }}" 
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
                    <option value="persen" {{ old('satuan', $pencapaian->satuan) == 'persen' ? 'selected' : '' }}>Persen (%)</option>
                    <option value="angka" {{ old('satuan', $pencapaian->satuan) == 'angka' ? 'selected' : '' }}>Angka (Absolut)</option>
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
                <option value="mahasiswa" {{ old('objek', $pencapaian->objek) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa (menggunakan data profil jumlah mahasiswa)</option>
                <option value="dosen" {{ old('objek', $pencapaian->objek) == 'dosen' ? 'selected' : '' }}>Dosen (menggunakan data profil jumlah dosen)</option>
                <option value="lainnya" {{ old('objek', $pencapaian->objek) == 'lainnya' ? 'selected' : '' }}>Lainnya / Tidak Ada Pembagi</option>
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
                      class="form-input-custom @error('keterangan') is-invalid @enderror">{{ old('keterangan', $pencapaian->keterangan) }}</textarea>
            @error('keterangan')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="form-footer-actions">
            <a href="{{ route('adminprodi.pencapaian.index', ['tahun' => $pencapaian->tahun]) }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Perbarui Target</button>
        </div>
    </form>
</div>
@endsection
