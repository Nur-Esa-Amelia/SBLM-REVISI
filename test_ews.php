<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IkuPencapaian;
use App\Models\User;

// 1. Get a random/first IkuPencapaian record
$pencapaian = IkuPencapaian::first();
if (!$pencapaian) {
    echo "Gagal: Tidak ada data IkuPencapaian di database.\n";
    exit;
}

echo "Menggunakan IkuPencapaian ID: {$pencapaian->id}, Prodi: {$pencapaian->id_prodi}, Tahun: {$pencapaian->tahun}\n";
echo "Status Awal di DB: {$pencapaian->status}\n";

// Pastikan ada user Kaprodi & Admin Prodi untuk prodi ini agar email bisa dikirim
$recipients = User::where('prodi_id', $pencapaian->id_prodi)
    ->whereIn('role', ['kaprodi', 'admin_prodi'])
    ->get();

echo "Daftar penerima terdaftar untuk Prodi ini:\n";
foreach ($recipients as $u) {
    echo "- Name: {$u->name}, Email: {$u->email}, Role: {$u->role}\n";
}

if ($recipients->isEmpty()) {
    echo "Peringatan: Tidak ditemukan Kaprodi/Admin Prodi untuk Prodi ID {$pencapaian->id_prodi}. Email tidak akan terkirim.\n";
}

// 2. Set status to 'Tercapai' (safe status) to ensure a transition can happen
$pencapaian->update(['status' => 'Tercapai']);
echo "Status diubah sementara ke: Tercapai\n";

// 3. Set target very high or make it trigger warning
$originalTarget = $pencapaian->target;
$originalRealisasi = $pencapaian->realisasi;

$pencapaian->update(['target' => '999999', 'satuan' => 'angka', 'realisasi' => 0]);
echo "Target diubah sementara ke: 999999 (untuk memaksa status menjadi Tidak Tercapai)\n";

// 4. Run calculateAndSync
echo "Menjalankan IkuPencapaian::calculateAndSync...\n";
IkuPencapaian::calculateAndSync($pencapaian->id_prodi, $pencapaian->tahun);

// Reload record
$pencapaian->refresh();
echo "Status Baru setelah calculateAndSync: {$pencapaian->status}\n";

// 5. Restore original data
$pencapaian->update([
    'target' => $originalTarget,
    'realisasi' => $originalRealisasi,
]);
echo "Data asli berhasil dikembalikan.\n";
