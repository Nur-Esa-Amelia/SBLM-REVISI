@extends($layout)

@section('title', 'Profil Saya - Sistem Early Warning IKU/IKT')

@section('content')
<div style="display: flex; justify-content: center; align-items: flex-start; padding-top: 10px;">
    <div class="card" style="width: 100%; max-width: 600px; display: flex; flex-direction: column; gap: 24px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); border: 1px solid #1e293b; background-color: var(--bg-surface, #0f172a); padding: 32px; border-radius: 16px;">
        <div style="display: flex; flex-direction: column; align-items: center; gap: 14px; border-bottom: 1px solid var(--border, #1e293b); padding-bottom: 24px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(37, 99, 235, 0.1); border: 2px solid rgba(37, 99, 235, 0.2); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.75rem; text-transform: uppercase;">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary, #ffffff); margin: 0;">{{ auth()->user()->name }}</h3>
            <span class="badge-custom badge-blue" style="text-transform: uppercase; font-size: 0.72rem; padding: 4px 10px;">
                {{ str_replace('_', ' ', auth()->user()->role) }}
            </span>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 18px;">
            @csrf
            @method('PUT')

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="name" style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary, #64748b); text-transform: uppercase; letter-spacing: 0.05em;">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-input" style="width: 100%; font-size: 0.95rem; font-weight: 600; padding: 12px 16px; background-color: var(--bg-base, #090d16); color: var(--text-primary, #ffffff); border: 1px solid var(--border, #1e293b); border-radius: 10px;" required>
                @error('name')
                    <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label for="email" style="font-size: 0.75rem; font-weight: 700; color: var(--text-secondary, #64748b); text-transform: uppercase; letter-spacing: 0.05em;">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-input" style="width: 100%; font-size: 0.95rem; font-weight: 600; padding: 12px 16px; background-color: var(--bg-base, #090d16); color: var(--text-primary, #ffffff); border: 1px solid var(--border, #1e293b); border-radius: 10px;" required>
                @error('email')
                    <span style="color: #ef4444; font-size: 0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <span style="font-size: 0.72rem; font-weight: 700; color: var(--text-secondary, #64748b); text-transform: uppercase; letter-spacing: 0.05em;">Program Studi</span>
                <div style="font-size: 0.95rem; color: var(--text-secondary, #94a3b8); font-weight: 600; padding: 12px 16px; background-color: var(--bg-surface2, #0f172a); border: 1px solid var(--border, #1e293b); border-radius: 10px; cursor: not-allowed; opacity: 0.8;">
                    {{ auth()->user()->prodi?->nama_prodi ?? 'Umum / P2MP' }}
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-weight: 600; font-size: 0.95rem;">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
