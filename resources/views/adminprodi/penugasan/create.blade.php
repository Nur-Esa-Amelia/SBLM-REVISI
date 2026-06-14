@extends('adminprodi.layouts.app')

@section('title', 'Tugaskan Dosen - Admin Prodi')
@section('page_title', 'Tugaskan Dosen')
@section('page_subtitle', 'Pilih dosen dan IKU terkait untuk membuat penugasan pengisian baru')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">Form Penugasan Dosen - Tahun {{ $tahun }}</h3>

    <form action="{{ route('adminprodi.penugasan.store') }}" method="POST" class="form-layout-container">
        @csrf

        <input type="hidden" name="tahun" value="{{ $tahun }}">

        <!-- Dosen Select -->
        <div class="form-group-custom">
            <label for="id_user" class="form-label-custom">Pilih Dosen Penerima Tugas</label>
            <select id="id_user" name="id_user" class="form-select-custom @error('id_user') is-invalid @enderror" required>
                <option value="">-- Pilih Dosen --</option>
                @foreach($dosen as $d)
                    <option value="{{ $d->id }}" {{ old('id_user') == $d->id ? 'selected' : '' }}>
                        {{ $d->name }} ({{ $d->email }})
                    </option>
                @endforeach
            </select>
            @error('id_user')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- IKU Select -->
        <div class="form-group-custom">
            <label for="id_iku" class="form-label-custom">Pilih Indikator IKU</label>
            <select id="id_iku" name="id_iku" class="form-select-custom @error('id_iku') is-invalid @enderror" required>
                <option value="">-- Pilih Indikator IKU --</option>
                @foreach($iku as $item)
                    <option value="{{ $item->id }}" {{ old('id_iku') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_iku }} (Kategori: {{ $item->kategori->nama_kategori }})
                    </option>
                @endforeach
            </select>
            @error('id_iku')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="form-footer-actions">
            <a href="{{ route('adminprodi.penugasan.index', ['tahun' => $tahun]) }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Penugasan</button>
        </div>
    </form>
</div>
@endsection
