<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ps = \App\Models\IkuPencapaian::select('id_prodi', 'tahun')->distinct()->get();
foreach($ps as $p) {
    echo "Syncing prodi {$p->id_prodi} for {$p->tahun}\n";
    \App\Models\IkuPencapaian::calculateAndSync($p->id_prodi, $p->tahun);
}
$p = \App\Models\IkuPencapaian::find(17);
echo 'after sync - target nyata: ' . $p->targetNyata() . ' - status: ' . $p->status . "\n";
