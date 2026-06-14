@extends('layouts.auth')

@section('title', 'Login ke Sistem - Sistem Early Warning IKU')

@section('content')
<div class="auth-card">
    <div class="text-center mb-8">
        <!-- Arrow Back Button -->
        <a href="{{ route('home') }}" class="absolute top-6 left-6 text-gray-500 hover:text-white transition cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>

        <h1 class="auth-title">Login ke Sistem</h1>
        <p class="auth-subtitle">Masukkan akun anda untuk mengakses aplikasi</p>
    </div>

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div style="display: flex; flex-direction: column; gap: 4px;">
            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Masukkan Alamat Email" required class="form-input">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi" required class="form-input">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="checkbox-container">
                <input id="remember" name="remember" type="checkbox" class="checkbox-input">
                <label for="remember" class="checkbox-label">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-action btn-primary" style="margin-top: 12px;">
                Login ke Sistem
            </button>
        </div>
    </form>
</div>
@endsection
