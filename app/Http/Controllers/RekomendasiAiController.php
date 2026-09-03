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
                    || str_contains($rekomendasi->rekomendasi, 'Gagal menghubungi server Gemini API')
                    || str_contains($rekomendasi->rekomendasi, 'sedang diproses')
                    || str_contains($rekomendasi->rekomendasi, 'Rekomendasi belum di-generate')
                    || str_contains($rekomendasi->rekomendasi, 'Layanan AI sedang tidak tersedia')
                    || str_contains($rekomendasi->rekomendasi, 'Rekomendasi AI belum tersedia');

                if (($item->updated_at && $rekomendasi->updated_at && $item->updated_at->gt($rekomendasi->updated_at))
                    || $isFailedRecommendation) {
                    $needsGeneration = true;
                }
            } else {
                $needsGeneration = true;
            }

            if ($needsGeneration) {
                // Buat placeholder yang menyatakan rekomendasi belum digenerate
                RekomendasiAi::updateOrCreate(
                    ['id_iku_pencapaian' => $item->id],
                    ['rekomendasi' => 'Rekomendasi belum di-generate. Silakan klik tombol **Generate AI Sekarang** di bawah ini untuk memulai analisis.']
                );
            }
        }

        $warningIds = $warnings->pluck('id')->toArray();
        return RekomendasiAi::with(['ikuPencapaian.iku', 'ikuPencapaian.prodi'])
            ->whereIn('id_iku_pencapaian', $warningIds)
            ->get();
    }

    /**
     * Generate rekomendasi AI secara on-demand via AJAX
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateAjax($id)
    {
        try {
            $item = IkuPencapaian::findOrFail($id);
            
            $rekomendasi = RekomendasiAi::where('id_iku_pencapaian', $item->id)->first();
            $needsGeneration = false;

            if ($rekomendasi) {
                // Cek apakah rekomendasi yang tersimpan adalah placeholder atau gagal
                $isFailedRecommendation = str_contains($rekomendasi->rekomendasi, 'sementara tidak dapat dibuat')
                    || str_contains($rekomendasi->rekomendasi, 'RESOURCE_EXHAUSTED')
                    || str_contains($rekomendasi->rekomendasi, 'Gagal menghubungi server Gemini API')
                    || str_contains($rekomendasi->rekomendasi, 'sedang diproses')
                    || str_contains($rekomendasi->rekomendasi, 'Rekomendasi belum di-generate')
                    || str_contains($rekomendasi->rekomendasi, 'Layanan AI sedang tidak tersedia');

                // Generate ulang jika data pencapaian berubah setelah rekomendasi dibuat, ATAU jika gagal/placeholder
                if (($item->updated_at && $rekomendasi->updated_at && $item->updated_at->gt($rekomendasi->updated_at))
                    || $isFailedRecommendation) {
                    $needsGeneration = true;
                }
            } else {
                $needsGeneration = true;
            }

            if ($needsGeneration) {
                // Dispatch secara sinkron agar langsung ditunggu hasilnya dari Gemini
                \App\Jobs\GenerateAiRecommendationJob::dispatchSync($item->id);
                // Ambil ulang hasil setelah dispatch selesai
                $rekomendasi = RekomendasiAi::where('id_iku_pencapaian', $item->id)->first();
            }
            
            if ($rekomendasi) {
                return response()->json([
                    'status' => 'success',
                    'rekomendasi' => $rekomendasi->rekomendasi
                ]);
            }
            
            return response()->json(['status' => 'error', 'message' => 'Gagal generate rekomendasi.'], 500);
        } catch (\Exception $e) {
            Log::error('AJAX AI Generate Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
