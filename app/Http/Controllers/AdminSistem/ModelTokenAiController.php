<?php

namespace App\Http\Controllers\AdminSistem;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ModelTokenAiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = \App\Models\GeminiModel::oldest()->get();
        return view('adminsistem.model_ai.index', compact('models'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'model_id' => 'required|string|max:255',
            'api_key' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        \App\Models\GeminiModel::create([
            'name' => $request->name,
            'model_id' => $request->model_id,
            'api_key' => $request->api_key,
            'status' => $request->status,
        ]);

        $this->resetAiStateAndFailedRecommendations();

        ActivityLog::log('Menambahkan model Gemini', 'Model & Token AI', "Menambahkan model '{$request->name}' dengan status {$request->status}");

        return redirect()->back()->with('success', 'Konfigurasi Model Gemini berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $model = \App\Models\GeminiModel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'model_id' => 'required|string|max:255',
            'api_key' => 'nullable|string', // Optional on edit
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $model->name = $request->name;
        $model->model_id = $request->model_id;
        $model->status = $request->status;

        if ($request->filled('api_key')) {
            $model->api_key = $request->api_key;
            ActivityLog::log('Mengubah model Gemini', 'Model & Token AI', "Mengubah model '{$request->name}' (termasuk pergantian API Key)");
        } else {
            ActivityLog::log('Mengubah model Gemini', 'Model & Token AI', "Mengubah data model '{$request->name}'");
        }

        $model->save();
        $this->resetAiStateAndFailedRecommendations();

        return redirect()->back()->with('success', 'Konfigurasi Model Gemini berhasil diperbarui.');
    }

    public function activate($id)
    {
        $model = \App\Models\GeminiModel::findOrFail($id);

        // Aktifkan yang dipilih
        $model->update(['status' => 'aktif']);
        $this->resetAiStateAndFailedRecommendations();

        ActivityLog::log('Mengaktifkan model Gemini', 'Model & Token AI', "Mengaktifkan model '{$model->name}' sebagai default");

        return redirect()->back()->with('success', "Model '{$model->name}' berhasil diaktifkan.");
    }

    public function destroy($id)
    {
        $model = \App\Models\GeminiModel::findOrFail($id);
        $name = $model->name;
        $model->delete();

        ActivityLog::log('Menghapus model Gemini', 'Model & Token AI', "Menghapus konfigurasi model '{$name}'");

        return redirect()->back()->with('success', 'Model Gemini berhasil dihapus.');
    }

    public function activateAll()
    {
        \App\Models\GeminiModel::query()->update(['status' => 'aktif']);
        $this->resetAiStateAndFailedRecommendations();
        
        ActivityLog::log('Mengaktifkan semua model Gemini', 'Model & Token AI', "Mengaktifkan semua model secara massal");
        
        return redirect()->back()->with('success', 'Semua Model berhasil diaktifkan.');
    }

    public function destroyAll()
    {
        \App\Models\GeminiModel::query()->delete();
        
        ActivityLog::log('Menghapus semua model Gemini', 'Model & Token AI', "Menghapus semua konfigurasi model secara massal");
        
        return redirect()->back()->with('success', 'Semua Model berhasil dihapus.');
    }

    /**
     * Clear cooldown and delete previous failed recommendation responses from DB
     */
    protected function resetAiStateAndFailedRecommendations()
    {
        \App\Models\GeminiModel::query()->update(['cooldown_until' => null]);
        \App\Models\RekomendasiAi::where('rekomendasi', 'LIKE', '%Layanan AI sedang tidak tersedia%')
            ->orWhere('rekomendasi', 'LIKE', '%RESOURCE_EXHAUSTED%')
            ->orWhere('rekomendasi', 'LIKE', '%Rekomendasi AI belum tersedia%')
            ->orWhere('rekomendasi', 'LIKE', '%Gagal%')
            ->delete();
    }
}
