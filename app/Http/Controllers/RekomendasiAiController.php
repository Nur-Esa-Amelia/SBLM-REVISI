<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IkuPencapaian;
use App\Models\RekomendasiAi;
use App\Models\BuktiIku;
use App\Models\PengisianBukti;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RekomendasiAiController extends Controller
{
    /**
     * Dapatkan atau generate rekomendasi AI untuk IKU/IKT pencapaian yang bermasalah.
     *
     * @param \Illuminate\Support\Collection $warnings
     * @return \Illuminate\Support\Collection
     */
    public function getOrGenerate($warnings)
    {
        if ($warnings->isEmpty()) {
            return collect();
        }

        $hasTriggeredGeneration = false;

        foreach ($warnings as $item) {
            $rekomendasi = RekomendasiAi::where('id_iku_pencapaian', $item->id)->first();
            $needsGeneration = false;

            if ($rekomendasi) {
                // Jika data IKU/IKT pencapaian diubah/diupdate setelah rekomendasi di-generate,
                // generate ulang dengan data terbaru.
                $isFailedRecommendation = str_contains($rekomendasi->rekomendasi, 'sementara tidak dapat dibuat')
                    || str_contains($rekomendasi->rekomendasi, 'RESOURCE_EXHAUSTED')
                    || str_contains($rekomendasi->rekomendasi, 'Gagal menghubungi server Gemini API:')
                    || str_contains($rekomendasi->rekomendasi, 'sedang diproses'); // term placeholder

                if (($item->updated_at && $rekomendasi->updated_at && $item->updated_at->gt($rekomendasi->updated_at))
                    || $isFailedRecommendation) {
                    $needsGeneration = true;
                }
            } else {
                $needsGeneration = true;
            }

            if ($needsGeneration) {
                // Buat placeholder sementara
                RekomendasiAi::updateOrCreate(
                    ['id_iku_pencapaian' => $item->id],
                    ['rekomendasi' => 'Rekomendasi sedang diproses oleh AI... Silakan muat ulang halaman beberapa saat lagi.']
                );

                // Dispatch job ke background
                \App\Jobs\GenerateAiRecommendationJob::dispatch($item->id);
                $hasTriggeredGeneration = true;
            }
        }

        if ($hasTriggeredGeneration) {
            \App\Models\ActivityLog::log('Generate Rekomendasi AI', 'Rekomendasi AI', 'Sistem menggenerate ulang rekomendasi AI untuk indikator bermasalah');
        }

        $warningIds = $warnings->pluck('id')->toArray();
        return RekomendasiAi::with(['ikuPencapaian.iku', 'ikuPencapaian.prodi'])
            ->whereIn('id_iku_pencapaian', $warningIds)
            ->get();
    }
}
