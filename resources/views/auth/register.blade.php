@extends('layouts.auth')

@section('title', 'Daftar Akun Dosen - Sistem Early Warning IKU')

@section('content')
<div class="auth-card">
    <div class="text-center mb-6">
        <h1 class="auth-title">Daftar Akun Dosen</h1>
        <p class="auth-subtitle">Mulai monitoring pencapaian IKU Program Studi</p>
    </div>

    <!-- Multi-step Form -->
    <form action="{{ route('register') }}" method="POST" id="register-form">
        @csrf
        
        <!-- Hidden input for prodi_id -->
        <input type="hidden" name="prodi_id" id="prodi_id_input" value="{{ old('prodi_id') }}">

        <!-- STEP 1: SELECT PRODI -->
        <div id="step-1" class="{{ old('prodi_id') ? 'hidden' : '' }}">
            <h2 style="font-size: 0.75rem; font-weight: 600; color: #64748b; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.05em; text-align: center;">Pilih Program Studi Anda</h2>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($prodis as $prodi)
                    <button type="button" 
                            onclick="selectProdi('{{ $prodi->id }}', '{{ $prodi->nama_prodi }}')"
                            class="prodi-card">
                        <div class="prodi-icon-wrapper">
                            @if($prodi->kode_prodi == 'TEKKOM')
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @elseif($prodi->kode_prodi == 'TEKSIP')
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            @elseif($prodi->kode_prodi == 'TEKMES')
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            @elseif($prodi->kode_prodi == 'BISDIG')
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="prodi-info">
                            <h3 class="prodi-name">{{ $prodi->nama_prodi }}</h3>
                            <p class="prodi-code">{{ $prodi->kode_prodi }}</p>
                        </div>
                        <svg class="prodi-arrow" style="width: 20px; height: 20px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @endforeach
            </div>
            
            @error('prodi_id')
                <p class="form-error" style="text-align: center; margin-top: 12px;">{{ $message }}</p>
            @enderror
        </div>

        <!-- STEP 2: REGISTRATION FORM -->
        <div id="step-2" class="{{ old('prodi_id') ? '' : 'hidden' }}">
            <!-- Selected Prodi Display Card -->
            <div style="padding: 14px 20px; background-color: rgba(56, 189, 248, 0.08); border: 1px solid rgba(56, 189, 248, 0.2); border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 0.65rem; color: #38bdf8; font-weight: 700; uppercase tracking-wider; text-transform: uppercase;">Program Studi Terpilih</p>
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #ffffff; margin-top: 2px;" id="selected-prodi-display">
                        @if(old('prodi_id'))
                            {{ $prodis->firstWhere('id', old('prodi_id'))->nama_prodi ?? '' }}
                        @endif
                    </h4>
                </div>
                <button type="button" onclick="goBackToStep1()" style="font-size: 0.8rem; color: #94a3b8; text-decoration: underline; background: transparent; border: none; cursor: pointer; font-weight: 500; outline: none;">
                    Ubah
                </button>
            </div>

            <!-- Inputs -->
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Contoh: Dr. Budi Santoso" class="form-input">
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="name@domain.com" class="form-input">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" class="form-input">
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi" class="form-input">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-action btn-primary" style="margin-top: 12px;">
                    Daftar Sekarang
                </button>
            </div>
        </div>
    </form>

    <!-- Login Link Footer -->
    <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #242f47; text-align: center;">
        <p style="font-size: 0.85rem; color: #94a3b8;">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" style="color: #38bdf8; text-decoration: underline; font-weight: 600; transition: all 0.2s ease;">
                Login ke Sistem
            </a>
        </p>
    </div>
</div>

<script>
    function selectProdi(prodiId, prodiName) {
        document.getElementById('prodi_id_input').value = prodiId;
        document.getElementById('selected-prodi-display').innerText = prodiName;
        
        // Hide step 1 and show step 2 with smooth transition effect
        document.getElementById('step-1').classList.add('hidden');
        document.getElementById('step-2').classList.remove('hidden');
    }

    function goBackToStep1() {
        document.getElementById('prodi_id_input').value = '';
        
        // Show step 1 and hide step 2
        document.getElementById('step-1').classList.remove('hidden');
        document.getElementById('step-2').classList.add('hidden');
    }
</script>
@endsection
