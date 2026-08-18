<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IkuPencapaian;
use App\Models\RekomendasiAi;
use App\Models\BuktiIku;
use App\Models\PengisianBukti;
use Illuminate\Support\Facades\Http;

class RekomendasiAiController extends Controller
{
    /**
     * Dapatkan atau generate rekomendasi AI untuk IKU pencapaian yang bermasalah.
     *
     * @param \Illuminate\Support\Collection $warnings
     * @return \Illuminate\Support\Collection
     */
    public function getOrGenerate($warnings)
    {
        if ($warnings->isEmpty()) {
            return collect();
        }

        $apiKey = env('GEMINI_API_KEY');

        foreach ($warnings as $item) {
            $rekomendasi = RekomendasiAi::where('id_iku_pencapaian', $item->id)->first();
            if ($rekomendasi) {
                // Jika data IKU pencapaian diubah/diupdate setelah rekomendasi di-generate,
                // hapus rekomendasi lama agar di-generate ulang dengan data terbaru.
                if ($item->updated_at && $rekomendasi->created_at && $item->updated_at->gt($rekomendasi->created_at)) {
                    $rekomendasi->delete();
                } else {
                    continue;
                }
            }

            // prompt untuk AI
            $prodiName = $item->prodi ? $item->prodi->nama_prodi : 'Program Studi'; 
            $tahun = $item->tahun; 
            $namaIku = $item->iku ? $item->iku->nama_iku : 'Indikator'; 
            $deskripsi = $item->iku ? ($item->iku->deskripsi ?: 'Tidak ada deskripsi') : 'Tidak ada deskripsi'; 
            $target = $item->target . ($item->satuan === 'persen' ? '%' : '') . " (" . $item->objek . ")"; 
            $realisasi = round($item->realisasi) . " bukti valid"; 
            $status = $item->status;

            // Ambil semua jenis bukti yang wajib dilaporkan untuk IKU ini
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

            $prompt = "Anda adalah Asisten AI Sistem Early Warning IKU (Indikator Kinerja Utama) Perguruan Tinggi.\n";
            $prompt .= "Berikan analisis risiko dan rekomendasi perbaikan untuk indikator yang tidak tercapai berikut:\n\n";
            $prompt .= "### 1. Data IKU & Deskripsi\n";
            $prompt .= "- Nama IKU: " . $namaIku . "\n";
            $prompt .= "- Deskripsi: " . $deskripsi . "\n";
            $prompt .= "- Program Studi: " . $prodiName . "\n";
            $prompt .= "- Tahun Akademik: " . $tahun . "\n";
            $prompt .= "- Target: " . $target . "\n";
            $prompt .= "- Realisasi: " . $realisasi . "\n";
            $prompt .= "- Status: " . $status . " (Perlu Perhatian / Tidak Tercapai)\n\n";

            $prompt .= "### 2. Jenis Bukti yang Wajib Dilaporkan\n";
            if ($buktiIkuList->isEmpty()) {
                $prompt .= "(Belum didefinisikan untuk IKU ini)\n\n";
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
            $prompt .= "3. Rekomendasi Perbaikan: Uraikan langkah-langkah konkret, strategis, dan realistis untuk meningkatkan capaian IKU tersebut.\n\n";
            $prompt .= "PENTING:\n";
            $prompt .= "- Analisislah perbandingan bukti yang sudah dan belum diunggah di atas secara mendalam. Rekomendasi perbaikan harus didasarkan pada kondisi nyata tersebut (misal: menyuruh mengunggah bukti yang belum ada, menindaklanjuti bukti yang ditolak/pending, dll.), sehingga rekomendasi yang dihasilkan sangat spesifik sesuai dengan kondisi nyata pada indikator tersebut dan tidak bersifat general/umum.\n";
            $prompt .= "- Jangan ulangi lagi bagian informasi data IKU, jenis bukti wajib, atau perbandingan bukti di jawaban Anda. Mulailah respon Anda langsung dengan heading/sub-heading untuk 3 poin analisis di atas.\n\n";
            $prompt .= "Sajikan jawaban Anda dalam Bahasa Indonesia yang formal, ringkas, terstruktur menggunakan format markdown (gunakan bullet points, sub-heading, dan cetak tebal).";

            // Mempersiapkan teks detail informasi untuk disimpan ke database dan ditampilkan di modal
            $headerText = "### Analisis Risiko dan Rekomendasi Perbaikan IKU {$namaIku}\n\n";
            $headerText .= "- **Nama IKU**: " . $namaIku . " (" . $deskripsi . ")\n";
            $headerText .= "- **Program Studi**: " . $prodiName . "\n";
            $headerText .= "- **Tahun Akademik**: " . $tahun . "\n";
            $headerText .= "- **Target**: " . $target . "\n";
            $headerText .= "- **Realisasi**: " . $realisasi . "\n";
            $headerText .= "- **Status**: " . $status . "\n\n";

            $recommendationText = 'Gagal menghubungi server Gemini API.';

            if ($apiKey) {
                try {
                    $response = Http::timeout(30)->post(
                        'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $apiKey,
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
                    } else {
                        $recommendationText = 'Gagal menghubungi server Gemini API: ' . $response->body();
                    }
                } catch (\Exception $e) {
                    $recommendationText = 'Terjadi kesalahan saat memproses rekomendasi AI: ' . $e->getMessage();
                }
            } else {
                $recommendationText = 'API Key Gemini (GEMINI_API_KEY) belum dikonfigurasi di file .env.';
            }

            RekomendasiAi::create([
                'id_iku_pencapaian' => $item->id,
                'rekomendasi' => $recommendationText
            ]);
        }

        $warningIds = $warnings->pluck('id')->toArray();
        return RekomendasiAi::with(['ikuPencapaian.iku', 'ikuPencapaian.prodi'])
            ->whereIn('id_iku_pencapaian', $warningIds)
            ->get();
    }
}
