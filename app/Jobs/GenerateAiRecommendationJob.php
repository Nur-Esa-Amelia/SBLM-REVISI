<?php

namespace App\Jobs;

use App\Models\IkuPencapaian;
use App\Models\RekomendasiAi;
use App\Models\BuktiIku;
use App\Models\PengisianBukti;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateAiRecommendationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    protected $ikuPencapaianId;

    /**
     * Create a new job instance.
     */
    public function __construct($ikuPencapaianId)
    {
        $this->ikuPencapaianId = $ikuPencapaianId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $item = IkuPencapaian::with(['iku', 'prodi'])->find($this->ikuPencapaianId);
        if (!$item) {
            return;
        }

        // Ambil konfigurasi model aktif dari database
        $activeModel = \App\Models\GeminiModel::where('status', 'aktif')->first();

        if ($activeModel) {
            $apiKey = $activeModel->api_key;
            $model = $activeModel->model_id;
        } else {
            // Fallback ke .env jika tidak ada model yang aktif di database
            $apiKey = config('services.gemini.key');
            $model = config('services.gemini.model', 'gemini-2.5-flash');
        }

        // Jika sama sekali tidak ada API key
        if (!$apiKey) {
            RekomendasiAi::updateOrCreate(
                ['id_iku_pencapaian' => $item->id],
                ['rekomendasi' => 'Rekomendasi AI belum tersedia karena tidak ada Konfigurasi Model Gemini yang aktif. Silakan hubungi Admin Sistem.']
            );
            return;
        }

        // Catat aktivitas jika ini dipicu (Opsional, tergantung keperluan. Karena berjalan di background, mungkin user pembuat request sulit di-trace jika tidak di-pass di constructor. Kita lewati log aktivitas di job ini, biasanya di log di Controller jika ada aksi eksplisit)

        // prompt untuk AI
        $prodiName = $item->prodi ? $item->prodi->nama_prodi : 'Program Studi'; 
        $tahun = $item->tahun; 
        $namaIku = $item->iku ? $item->iku->nama_iku : 'Indikator'; 
        $deskripsi = $item->iku ? ($item->iku->deskripsi ?: 'Tidak ada deskripsi') : 'Tidak ada deskripsi'; 
        $target = $item->target . ($item->satuan === 'persen' ? '%' : '') . " (" . $item->objek . ")"; 
        $realisasi = round($item->realisasi) . " bukti valid"; 
        $status = $item->status;

        // Ambil semua jenis bukti yang wajib dilaporkan untuk IKU/IKT ini
        $buktiIkuList = BuktiIku::where('id_iku', $item->id_iku)->get();

        $sudahDiunggah = [];
        $belumDiunggah = [];

        foreach ($buktiIkuList as $bukti) {
            // Cari pengisian bukti untuk bukti ini, di tahun ini, oleh prodi ini
            $pengisians = PengisianBukti::with(['files'])
                ->where('id_bukti_iku', $bukti->id)
                ->where('tahun', $tahun)
                ->whereHas('user', function ($q) use ($item) {
                    $q->where('prodi_id', $item->id_prodi);
                })
                ->get();

            if ($pengisians->isEmpty()) {
                $belumDiunggah[] = "- **" . $bukti->nama_bukti . "**" . ($bukti->deskripsi ? " ({$bukti->deskripsi})" : "");
            } else {
                $details = [];
                foreach ($pengisians as $p) {
                    $fileCount = $p->files->count();
                    $statusPengisian = ucfirst($p->status);
                    $catatan = $p->catatan_validator ? ", Catatan Validator: \"{$p->catatan_validator}\"" : "";
                    $details[] = "Status: {$statusPengisian} ({$fileCount} berkas){$catatan}";
                }
                $sudahDiunggah[] = "- **" . $bukti->nama_bukti . "**" . ($bukti->deskripsi ? " ({$bukti->deskripsi})" : "") . " [" . implode('; ', $details) . "]";
            }
        }

        $prompt = "Anda adalah Asisten AI Sistem Early Warning IKU/IKT (Indikator Kinerja Utama) Perguruan Tinggi.\n";
        $prompt .= "Berikan analisis risiko dan rekomendasi perbaikan untuk indikator yang tidak tercapai berikut:\n\n";
        $prompt .= "### 1. Data IKU/IKT & Deskripsi\n";
        $prompt .= "- Nama IKU/IKT: " . $namaIku . "\n";
        $prompt .= "- Deskripsi: " . $deskripsi . "\n";
        $prompt .= "- Program Studi: " . $prodiName . "\n";
        $prompt .= "- Tahun Akademik: " . $tahun . "\n";
        $prompt .= "- Target: " . $target . "\n";
        $prompt .= "- Realisasi: " . $realisasi . "\n";
        $prompt .= "- Status: " . $status . " (Perlu Perhatian / Tidak Tercapai)\n\n";

        $prompt .= "### 2. Jenis Bukti yang Wajib Dilaporkan\n";
        if ($buktiIkuList->isEmpty()) {
            $prompt .= "(Belum didefinisikan untuk IKU/IKT ini)\n\n";
        } else {
            foreach ($buktiIkuList as $bukti) {
                $prompt .= "- **" . $bukti->nama_bukti . "**" . ($bukti->deskripsi ? ": " . $bukti->deskripsi : "") . "\n";
            }
            $prompt .= "\n";
        }

        $prompt .= "### 3. Perbandingan Bukti yang Sudah dan Belum Diunggah\n";
        if ($buktiIkuList->isEmpty()) {
            $prompt .= "(Tidak dapat dibandingkan karena jenis bukti belum didefinisikan)\n\n";
        } else {
            $prompt .= "**Bukti yang Sudah Diunggah:**\n";
            if (empty($sudahDiunggah)) {
                $prompt .= "- (Belum ada bukti yang diunggah)\n";
            } else {
                foreach ($sudahDiunggah as $s) {
                    $prompt .= $s . "\n";
                }
            }
            $prompt .= "\n**Bukti yang Belum Diunggah:**\n";
            if (empty($belumDiunggah)) {
                $prompt .= "- (Semua jenis bukti wajib sudah memiliki unggahan)\n";
            } else {
                foreach ($belumDiunggah as $b) {
                    $prompt .= $b . "\n";
                }
            }
            $prompt .= "\n";
        }

        $prompt .= "Tugas Anda:\n";
        $prompt .= "Berikan analisis terperinci yang mencakup tiga bagian berikut dengan sub-heading yang jelas:\n";
        $prompt .= "1. Prioritas Penanganan: Berikan prioritas penanganan (Tinggi / Sedang / Rendah) beserta alasan taktisnya.\n";
        $prompt .= "2. Analisis Risiko: Uraikan dampak buruk jika indikator ini terus-menerus tidak tercapai.\n";
        $prompt .= "3. Rekomendasi Perbaikan: Uraikan langkah-langkah konkret, strategis, dan realistis untuk meningkatkan capaian IKU/IKT tersebut.\n\n";
        $prompt .= "PENTING:\n";
        $prompt .= "- Analisislah perbandingan bukti yang sudah dan belum diunggah di atas secara mendalam. Rekomendasi perbaikan harus didasarkan pada kondisi nyata tersebut (misal: menyuruh mengunggah bukti yang belum ada, menindaklanjuti bukti yang ditolak/pending, dll.), sehingga rekomendasi yang dihasilkan sangat spesifik sesuai dengan kondisi nyata pada indikator tersebut dan tidak bersifat general/umum.\n";
        $prompt .= "- Jangan ulangi lagi bagian informasi data IKU/IKT, jenis bukti wajib, atau perbandingan bukti di jawaban Anda. Mulailah respon Anda langsung dengan heading/sub-heading untuk 3 poin analisis di atas.\n\n";
        $prompt .= "Sajikan jawaban Anda dalam Bahasa Indonesia yang formal, ringkas, terstruktur menggunakan format markdown (gunakan bullet points, sub-heading, dan cetak tebal).";

        // Mempersiapkan teks detail informasi untuk disimpan ke database dan ditampilkan di modal
        $headerText = "### Analisis Risiko dan Rekomendasi Perbaikan IKU/IKT {$namaIku}\n\n";
        $headerText .= "- **Nama IKU/IKT**: " . $namaIku . " (" . $deskripsi . ")\n";
        $headerText .= "- **Program Studi**: " . $prodiName . "\n";
        $headerText .= "- **Tahun Akademik**: " . $tahun . "\n";
        $headerText .= "- **Target**: " . $target . "\n";
        $headerText .= "- **Realisasi**: " . $realisasi . "\n";
        $headerText .= "- **Status**: " . $status . "\n\n";

        $recommendationText = 'Gagal menghubungi server Gemini API.';

        if ($apiKey) {
            try {
                $response = Http::timeout(60)->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $apiKey,
                    [
                        'contents' => [
                            [
                                 'parts' => [
                                     [
                                         'text' => $prompt
                                     ]
                                 ]
                            ]
                        ]
                    ]
                );

                if ($response->successful()) {
                    $result = $response->json();
                    $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal memproses rekomendasi AI.';
                    $recommendationText = $headerText . $aiText;
                } elseif ($response->status() === 429) {
                    $recommendationText = 'Rekomendasi AI sementara tidak tersedia karena kuota layanan Gemini telah tercapai. Silakan coba lagi setelah kuota API tersedia kembali.';
                } else {
                    Log::error('Gemini API request failed.', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                        'model' => $model,
                    ]);
                    $recommendationText = 'Rekomendasi AI sementara tidak dapat dibuat. Silakan coba lagi nanti.';
                }
            } catch (\Exception $e) {
                Log::error('Gemini API exception.', [
                    'message' => $e->getMessage()
                ]);
                $recommendationText = 'Rekomendasi AI sementara tidak dapat dibuat karena layanan sedang tidak tersedia. Silakan coba lagi nanti.';
            }
        } else {
            $recommendationText = 'Rekomendasi AI belum tersedia karena API Key Gemini belum dikonfigurasi.';
        }

        RekomendasiAi::updateOrCreate(
            ['id_iku_pencapaian' => $item->id],
            ['rekomendasi' => $recommendationText]
        );
    }
}
