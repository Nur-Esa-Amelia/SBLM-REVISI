<?php
\App\Models\User::updateOrCreate([
    'email' => 'admin.sistem@gmail.com'
], [
    'name' => 'Admin Sistem',
    'password' => bcrypt('password'),
    'role' => 'admin_sistem',
    'prodi_id' => null,
]);
echo "\n\n=== AKUN ADMIN SISTEM BERHASIL DIBUAT ===\n\n";
