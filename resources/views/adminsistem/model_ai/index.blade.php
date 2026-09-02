@extends('adminsistem.layouts.app')

@section('title', 'Kelola Model & Token AI - Sistem Early Warning IKU/IKT')
@section('page_title', 'Kelola Model & Token AI')
@section('page_subtitle', 'Konfigurasi integrasi model kecerdasan buatan')

@section('content')
<div class="card" style="display: flex; flex-direction: column; gap: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin: 0;">Konfigurasi AI</h3>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">Atur API Key dan model AI yang digunakan oleh sistem.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-box alert-success" role="alert" style="padding: 12px 16px; border-radius: 8px; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; display: flex; align-items: center; gap: 12px;">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span style="font-size: 0.85rem; font-weight: 500;">{{ session('success') }}</span>
        </div>
    @endif

    <div style="background-color: var(--bg-surface2); padding: 24px; border-radius: 12px; border: 1px solid var(--border);">
        <form action="{{ route('adminsistem.model_ai.store') }}" method="POST">
            @csrf
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div class="form-group-custom">
                    <label for="ai_provider" class="form-label-custom">Penyedia Layanan AI (Provider)</label>
                    <select id="ai_provider" name="ai_provider" class="form-select-custom">
                        <option value="gemini">Google Gemini</option>
                    </select>
                </div>

                <div class="form-group-custom">
                    <label for="ai_model" class="form-label-custom">Model AI</label>
                    <select id="ai_model" name="ai_model" class="form-select-custom">
                        <option value="gemini-1.5-flash">Gemini 1.5 Flash (Cepat & Efisien)</option>
                        <option value="gemini-1.5-pro">Gemini 1.5 Pro (Lebih Pintar)</option>
                    </select>
                </div>

                <div class="form-group-custom">
                    <label for="api_key" class="form-label-custom">API Key</label>
                    <input type="password" id="api_key" name="api_key" class="form-input-custom" placeholder="Masukkan API Key (misal: AIzaSy...)">
                    <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px; display: block;">Biarkan kosong jika ingin menggunakan key bawaan di konfigurasi server (.env).</span>
                </div>
                
                <div style="display: flex; justify-content: flex-end; margin-top: 10px;">
                    <button type="submit" class="btn btn-primary">
                        <svg style="width: 16px; height: 16px; margin-right: 6px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Simpan Konfigurasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
