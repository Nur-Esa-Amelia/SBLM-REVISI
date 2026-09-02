@extends('adminsistem.layouts.app')

@section('title', 'Log Aktivitas Pengguna - Sistem Early Warning IKU/IKT')
@section('page_title', 'Aktivitas Pengguna')
@section('page_subtitle', 'Pantau riwayat aktivitas yang dilakukan oleh semua pengguna sistem')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Action Header & Filters Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <!-- Filters Form encompassing header and filters -->
        <form action="{{ route('adminsistem.aktivitas.index') }}" method="GET" style="display: flex; flex-direction: column; gap: 20px;">
            
            <!-- Header Row with Search -->
            <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 16px; flex-wrap: wrap;">
                <div>
                    <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">Riwayat Aktivitas</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">Data log seluruh aktivitas pengguna di sistem</p>
                </div>
                
                <!-- Search Box (Aligned Right) -->
                <div style="flex: 1; max-width: 350px;">
                    <div class="search-wrapper">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nama / Aktivitas / Modul..." class="form-input-custom">
                        <button type="submit" class="btn-search" title="Cari">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        <a href="{{ route('adminsistem.aktivitas.index') }}" class="btn-reset" title="Reset Pencarian">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters Row -->
            <div class="filter-row-custom">
                <!-- Role Filter -->
                <div class="filter-item-custom">
                    <label for="role" class="form-label-custom">Filter Role</label>
                    <select name="role" id="role" onchange="this.form.submit()" class="form-select-custom">
                        <option value="">Semua Role</option>
                        <option value="admin_sistem" {{ request('role') == 'admin_sistem' ? 'selected' : '' }}>Admin Sistem</option>
                        <option value="admin_p2mp" {{ request('role') == 'admin_p2mp' ? 'selected' : '' }}>Admin P2MP</option>
                        <option value="admin_prodi" {{ request('role') == 'admin_prodi' ? 'selected' : '' }}>Admin Prodi</option>
                        <option value="kaprodi" {{ request('role') == 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                        <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                    </select>
                </div>

                <!-- Modul Filter -->
                <div class="filter-item-custom">
                    <label for="module" class="form-label-custom">Filter Modul</label>
                    <select name="module" id="module" onchange="this.form.submit()" class="form-select-custom">
                        <option value="">Semua Modul</option>
                        @foreach($modules as $mod)
                            <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date Filters -->
                <div class="filter-item-custom">
                    <label for="date_from" class="form-label-custom">Dari Tanggal</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-input-custom" onchange="this.form.submit()">
                </div>
                
                <div class="filter-item-custom">
                    <label for="date_to" class="form-label-custom">Sampai Tanggal</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-input-custom" onchange="this.form.submit()">
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Modul</th>
                        <th style="max-width: 300px;">Keterangan</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td style="white-space: nowrap; font-size: 0.85rem; color: var(--text-muted);">
                                {{ $log->created_at->format('d M Y') }}<br>
                                <strong>{{ $log->created_at->format('H:i') }}</strong>
                            </td>
                            <td>
                                @if($log->user)
                                    <div style="font-weight: 600; color: var(--text-primary);">{{ $log->user->name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: capitalize;">
                                        {{ str_replace('_', ' ', $log->user->role) }}
                                    </div>
                                @else
                                    <span style="font-style: italic; color: var(--text-muted);">User terhapus / Sistem</span>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight: 500;">{{ $log->activity }}</span>
                            </td>
                            <td>
                                <span class="badge-custom badge-blue">{{ $log->module }}</span>
                            </td>
                            <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem;" title="{{ $log->description }}">
                                {{ $log->description }}
                            </td>
                            <td style="text-align: center;">
                                <button type="button" onclick="openDetailModal('{{ $log->created_at->format('d F Y, H:i:s') }}', '{{ $log->user ? htmlspecialchars(addslashes($log->user->name)) : 'Sistem' }}', '{{ $log->user ? str_replace('_', ' ', $log->user->role) : '-' }}', '{{ htmlspecialchars(addslashes($log->activity)) }}', '{{ htmlspecialchars(addslashes($log->module)) }}', '{{ htmlspecialchars(addslashes($log->description)) }}', '{{ $log->ip_address }}', '{{ $log->user_agent }}')" class="btn-action-edit" title="Detail Aktivitas">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px; color: var(--text-muted);">
                                <svg style="width: 32px; height: 32px; margin: 0 auto 12px; color: var(--text-muted); display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Tidak ditemukan riwayat aktivitas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #1e293b; background-color: rgba(15, 23, 42, 0.2);">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2>Detail Aktivitas</h2>
            <button class="btn-close" onclick="closeDetailModal()">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-body" style="padding: 24px;">
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; gap: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                    <div style="flex: 1; font-weight: 600; color: var(--text-muted);">Waktu</div>
                    <div style="flex: 2; font-weight: 500;" id="detail_waktu"></div>
                </div>
                <div style="display: flex; gap: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                    <div style="flex: 1; font-weight: 600; color: var(--text-muted);">User</div>
                    <div style="flex: 2; font-weight: 500;" id="detail_user"></div>
                </div>
                <div style="display: flex; gap: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                    <div style="flex: 1; font-weight: 600; color: var(--text-muted);">Role</div>
                    <div style="flex: 2; font-weight: 500; text-transform: capitalize;" id="detail_role"></div>
                </div>
                <div style="display: flex; gap: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                    <div style="flex: 1; font-weight: 600; color: var(--text-muted);">Aktivitas</div>
                    <div style="flex: 2; font-weight: 500;" id="detail_aktivitas"></div>
                </div>
                <div style="display: flex; gap: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                    <div style="flex: 1; font-weight: 600; color: var(--text-muted);">Modul</div>
                    <div style="flex: 2; font-weight: 500;"><span class="badge-custom badge-blue" id="detail_modul"></span></div>
                </div>
                <div style="display: flex; gap: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                    <div style="flex: 1; font-weight: 600; color: var(--text-muted);">Keterangan Lengkap</div>
                    <div style="flex: 2; font-weight: 500; background-color: var(--bg-surface); padding: 12px; border-radius: 8px;" id="detail_keterangan"></div>
                </div>
                <div style="display: flex; gap: 12px; border-bottom: 1px solid var(--border); padding-bottom: 12px;">
                    <div style="flex: 1; font-weight: 600; color: var(--text-muted);">IP Address</div>
                    <div style="flex: 2; font-weight: 500; font-family: monospace;" id="detail_ip"></div>
                </div>
            </div>
            
            <div style="display: flex; justify-content: flex-end; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openDetailModal(waktu, user, role, aktivitas, modul, keterangan, ip, userAgent) {
        document.getElementById('detail_waktu').innerText = waktu;
        document.getElementById('detail_user').innerText = user;
        document.getElementById('detail_role').innerText = role;
        document.getElementById('detail_aktivitas').innerText = aktivitas;
        document.getElementById('detail_modul').innerText = modul;
        document.getElementById('detail_keterangan').innerText = keterangan;
        document.getElementById('detail_ip').innerText = ip;
        
        document.getElementById('detailModal').classList.add('show');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.remove('show');
    }
</script>
@endsection
