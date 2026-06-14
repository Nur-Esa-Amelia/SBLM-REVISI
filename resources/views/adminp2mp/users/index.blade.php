@extends('adminp2mp.layouts.app')

@section('title', 'Kelola User - Sistem Early Warning IKU')
@section('page_title', 'Kelola Pengguna')
@section('page_subtitle', 'Kelola hak akses dan asosiasi program studi pengguna')

@section('content')
<div style="display: flex; flex-direction: column; gap: 24px;">
    <!-- Action Header & Filters Card -->
    <div class="card" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div>
                <h3 style="font-size: 0.95rem; font-weight: 700; color: #ffffff;">Daftar Pengguna</h3>
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">Kelola hak akses dan asosiasi program studi pengguna aplikasi.</p>
            </div>
            <div>
                <a href="{{ route('adminp2mp.users.create') }}" class="btn btn-primary">
                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Tambah User Baru
                </a>
            </div>
        </div>

        <!-- Filters Form -->
        <form action="{{ route('adminp2mp.users.index') }}" method="GET" class="filter-row-custom">
            <!-- Search -->
            <div class="filter-item-custom" style="flex: 2;">
                <label for="search" class="form-label-custom">Cari Pengguna</label>
                <div class="filter-input-search-wrapper">
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Nama / Email..." class="form-input-custom filter-input-search">
                    <div class="filter-input-search-icon">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Role Filter -->
            <div class="filter-item-custom">
                <label for="role" class="form-label-custom">Filter Role</label>
                <select name="role" id="role" onchange="this.form.submit()" class="form-select-custom">
                    <option value="">Semua Role</option>
                    @foreach($roles as $key => $value)
                        <option value="{{ $key }}" {{ $role == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Prodi Filter -->
            <div class="filter-item-custom">
                <label for="prodi_id" class="form-label-custom">Filter Program Studi</label>
                <select name="prodi_id" id="prodi_id" onchange="this.form.submit()" class="form-select-custom">
                    <option value="">Semua Prodi / Tanpa Prodi</option>
                    @foreach($prodis as $p)
                        <option value="{{ $p->id }}" {{ $prodiId == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
            
            @if($search || $role || $prodiId)
                <div style="width: 100%; display: flex; justify-content: flex-end; margin-top: -8px;">
                    <a href="{{ route('adminp2mp.users.index') }}" style="font-size: 0.75rem; font-weight: 600; color: #f43f5e; text-decoration: none;">
                        Hapus Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Hak Akses / Role</th>
                        <th>Program Studi</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <!-- Name -->
                            <td style="font-weight: 700; color: #ffffff;">
                                {{ $user->name }}
                            </td>
                            <!-- Email -->
                            <td>
                                {{ $user->email }}
                            </td>
                            <!-- Role Badge -->
                            <td>
                                @if($user->role === 'admin_p2mp')
                                    <span class="badge-custom badge-purple">Admin P2MP</span>
                                @elseif($user->role === 'admin_prodi')
                                    <span class="badge-custom badge-blue">Admin Prodi</span>
                                @elseif($user->role === 'kaprodi')
                                    <span class="badge-custom badge-cyan">Kaprodi</span>
                                @else
                                    <span class="badge-custom badge-green">Dosen</span>
                                @endif
                            </td>
                            <!-- Program Studi -->
                            <td>
                                @if($user->prodi)
                                    <span style="font-weight: 600; color: #38bdf8; display: block;">{{ $user->prodi->nama_prodi }}</span>
                                    <span style="font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 700;">{{ $user->prodi->kode_prodi }}</span>
                                @else
                                    <span style="font-size: 0.75rem; color: #64748b; font-style: italic;">Tidak Terkait Prodi</span>
                                @endif
                            </td>
                            <!-- Actions -->
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 8px; justify-content: flex-end;">
                                    <!-- Edit Link -->
                                    <a href="{{ route('adminp2mp.users.edit', $user->id) }}" class="btn-action-edit" title="Edit User">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('adminp2mp.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')" style="display: inline-flex;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete" title="Hapus User">
                                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="btn-action-delete" style="opacity: 0.35; cursor: not-allowed;" title="Anda tidak dapat menghapus akun sendiri">
                                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 48px; color: #64748b;">
                                <svg style="width: 32px; height: 32px; margin: 0 auto 12px; color: #334155; display: block;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Tidak ditemukan data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div style="padding: 16px 20px; border-top: 1px solid #1e293b; background-color: rgba(15, 23, 42, 0.2);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

