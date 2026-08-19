<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    echo "Mengirim email uji coba ke nuresaamelia04@gmail.com...\n";
    Mail::raw('Ini adalah email uji coba dari Sistem EWS IKU untuk memverifikasi konfigurasi SMTP Gmail.', function ($message) {
        $message->to('nuresaamelia04@gmail.com')
                ->subject('EWS SMTP Connection Test');
    });
    echo "Sukses: Email berhasil terkirim!\n";
} catch (\Exception $e) {
    echo "Gagal: Terjadi kesalahan saat mengirim email:\n";
    echo $e->getMessage() . "\n";
}
