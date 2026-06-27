@extends($layout)

@section('title', 'Profil Saya - Sistem Early Warning IKU')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Melihat informasi akun Anda yang terdaftar di sistem')

@section('content')
<div style="display: flex; justify-content: center; align-items: flex-start; padding-top: 10px;">
    <div class="card" style="width: 100%; max-width: 600px; display: flex; flex-direction: column; gap: 24px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); border: 1px solid #1e293b; background-color: #0f172a; padding: 32px; border-radius: 16px;">
        <div style="display: flex; flex-direction: column; align-items: center; gap: 14px; border-bottom: 1px solid #1e293b; padding-bottom: 24px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background-color: rgba(37, 99, 235, 0.1); border: 2px solid rgba(37, 99, 235, 0.2); color: #3b82f6; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.75rem; text-transform: uppercase;">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #ffffff; margin: 0;">{{ auth()->user()->name }}</h3>
            <span class="badge-custom badge-blue" style="text-transform: uppercase; font-size: 0.72rem; padding: 4px 10px;">
                {{ str_replace('_', ' ', auth()->user()->role) }}
            </span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 18px;">
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <span style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Nama Lengkap</span>
                <div style="font-size: 0.9rem; color: #cbd5e1; font-weight: 600; padding: 12px 16px; background-color: #090d16; border: 1px solid #1e293b; border-radius: 10px;">
                    {{ auth()->user()->name }}
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <span style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Alamat Email</span>
                <div style="font-size: 0.9rem; color: #cbd5e1; font-weight: 600; padding: 12px 16px; background-color: #090d16; border: 1px solid #1e293b; border-radius: 10px;">
                    {{ auth()->user()->email }}
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <span style="font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">Program Studi</span>
                <div style="font-size: 0.9rem; color: #cbd5e1; font-weight: 600; padding: 12px 16px; background-color: #090d16; border: 1px solid #1e293b; border-radius: 10px;">
                    {{ auth()->user()->prodi?->nama_prodi ?? 'Umum / P2MP' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
