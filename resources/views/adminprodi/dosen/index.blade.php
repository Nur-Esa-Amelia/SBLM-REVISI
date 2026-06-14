@extends('adminprodi.layouts.app')

@section('title', 'Dosen Program Studi - Admin Prodi')
@section('page_title', 'Data Dosen')
@section('page_subtitle', 'Melihat daftar dosen program studi ' . $prodiName . ' beserta tugas indikator IKU')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Filter Year Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
            <div>
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #ffffff;">Pilih Tahun Akademik</h3>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Tampilkan tugas dosen berdasarkan tahun akademik yang dipilih.</p>
            </div>
            
            <form action="{{ route('adminprodi.dosen') }}" method="GET" style="display: flex; align-items: flex-end; gap: 12px; width: 220px;">
                <div class="filter-item-custom" style="width: 100%;">
                    <select id="tahun" name="tahun" class="form-select-custom" onchange="this.form.submit()">
                        @foreach($tahunList as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Lecturers Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px;">
        @forelse($dosenList as $dosenItem)
            <div class="card" style="display: flex; flex-direction: column; gap: 16px; border-left: 4px solid #3b82f6;">
                <!-- Lecturer Header Profile -->
                <div style="display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">
                    <div style="width: 44px; height: 44px; border-radius: 50%; background-color: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; flex-shrink: 0;">
                        {{ substr($dosenItem->name, 0, 2) }}
                    </div>
                    <div style="min-width: 0;">
                        <h4 style="font-size: 0.95rem; font-weight: 700; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $dosenItem->name }}</h4>
                        <p style="font-size: 0.75rem; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $dosenItem->email }}</p>
                    </div>
                </div>

                <!-- Assignment List -->
                <div>
                    <h5 style="font-size: 0.8rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <span>Tugas IKU ({{ $tahun }})</span>
                        <span class="badge-custom badge-blue" style="font-size: 0.65rem; padding: 2px 6px;">{{ $dosenItem->assignments->count() }} Tugas</span>
                    </h5>

                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @forelse($dosenItem->assignments as $assign)
                            <div style="padding: 10px; background-color: #090d16; border: 1px solid #1e293b; border-radius: 8px; display: flex; flex-direction: column; gap: 6px;">
                                <div style="font-size: 0.8rem; font-weight: 600; color: #ffffff; line-height: 1.4;">
                                    {{ $assign->iku->nama_iku }}
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 6px;">
                                    <span style="font-size: 0.7rem; color: #64748b;">Kategori: {{ $assign->iku->kategori->nama_kategori }}</span>
                                    
                                    <!-- Proof status badge -->
                                    @if($assign->proof_status === 'valid')
                                        <span class="badge-custom badge-green" style="font-size: 0.65rem;">Valid</span>
                                    @elseif($assign->proof_status === 'invalid')
                                        <span class="badge-custom badge-rose" style="font-size: 0.65rem;">Minta Perbaikan</span>
                                    @elseif($assign->proof_status === 'pending')
                                        <span class="badge-custom badge-yellow" style="font-size: 0.65rem;">Awaiting Validasi</span>
                                    @else
                                        <span class="badge-custom" style="background-color: rgba(100, 116, 139, 0.1); border-color: rgba(100, 116, 139, 0.2); color: #94a3b8; font-size: 0.65rem;">Belum Unggah</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; color: #64748b; padding: 20px 10px; font-size: 0.8rem; background-color: rgba(15, 23, 42, 0.2); border: 1px dashed #1e293b; border-radius: 8px;">
                                Belum ada penugasan IKU untuk tahun {{ $tahun }}.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <div class="card" style="grid-column: 1 / -1; text-align: center; color: #64748b; padding: 48px;">
                <svg style="width: 36px; height: 36px; margin: 0 auto 12px; color: #334155; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Tidak ada data dosen yang terdaftar di program studi Anda.
            </div>
        @endforelse
    </div>
</div>
@endsection
