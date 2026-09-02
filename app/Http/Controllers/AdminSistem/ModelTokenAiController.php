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
        return view('adminsistem.model_ai.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ActivityLog::log('Mengubah pengaturan sistem', 'Model & Token AI', 'Menyimpan konfigurasi model dan token AI');
        // Placeholder for future logic
        return redirect()->back()->with('success', 'Konfigurasi Model & Token AI berhasil disimpan.');
    }
}
