<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IkuPencapaian;
use App\Models\RekomendasiAi;
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
            $exists = RekomendasiAi::where('id_iku_pencapaian', $item->id)->exists();
            if ($exists) {
                continue;
            }

            // prompt untuk AI
            $prodiName = $item->prodi ? $item->prodi->nama_prodi : 'Program Studi'; 
            $tahun = $item->tahun; 
            $namaIku = $item->iku ? $item->iku->nama_iku : 'Indikator'; 
            $deskripsi = $item->iku ? ($item->iku->deskripsi ?: 'Tidak ada deskripsi') : 'Tidak ada deskripsi'; 
            $target = $item->target . ($item->satuan === 'persen' ? '%' : '') . " (" . $item->objek . ")"; 
            $realisasi = round($item->realisasi) . " bukti valid"; 
            $status = $item->status;

            $prompt = "Anda adalah Asisten AI Sistem Early Warning IKU (Indikator Kinerja Utama) Perguruan Tinggi.\n";
            $prompt .= "Berikan analisis risiko dan rekomendasi perbaikan untuk indikator yang tidak tercapai berikut:\n\n";
            $prompt .= "- Nama IKU: " . $namaIku . "\n";
            $prompt .= "- Deskripsi: " . $deskripsi . "\n";
            $prompt .= "- Program Studi: " . $prodiName . "\n";
            $prompt .= "- Tahun Akademik: " . $tahun . "\n";
            $prompt .= "- Target: " . $target . "\n";
            $prompt .= "- Realisasi: " . $realisasi . "\n";
            $prompt .= "- Status: " . $status . " (Belum Tercapai / Berisiko Tidak Tercapai)\n\n";

            $prompt .= "Tugas Anda:\n";
            $prompt .= "Berikan analisis terperinci yang mencakup tiga bagian berikut dengan sub-heading yang jelas:\n";
            $prompt .= "1. Prioritas Penanganan: Berikan prioritas penanganan (Tinggi / Sedang / Rendah) beserta alasan taktisnya.\n";
            $prompt .= "2. Analisis Risiko: Uraikan dampak buruk jika indikator ini terus-menerus tidak tercapai.\n";
            $prompt .= "3. Rekomendasi Perbaikan: Uraikan langkah-langkah konkret, strategis, dan realistis untuk meningkatkan capaian IKU tersebut.\n\n";
            $prompt .= "Sajikan jawaban Anda dalam Bahasa Indonesia yang formal, ringkas, terstruktur menggunakan format markdown (gunakan bullet points, sub-heading, dan cetak tebal).";

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
                        $recommendationText = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Gagal memproses rekomendasi AI.';
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
