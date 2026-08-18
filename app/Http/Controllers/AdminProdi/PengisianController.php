<?php

namespace App\Http\Controllers\AdminProdi;

use App\Http\Controllers\Controller;
use App\Models\Iku;
use App\Models\BuktiIku;
use App\Models\Pengaturan;
use App\Models\PengisianBukti;
use App\Models\FileIsiBukti;
use App\Models\IkuPencapaian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengisianController extends Controller
{
    /**
     * Tampilkan form untuk mengunggah bukti IKU yang tidak memerlukan penugasan.
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'kaprodi') {
            abort(403, 'Akses khusus Kaprodi.');
        }

        $prodiId = $user->prodi_id;
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        
        $tahunAktif = $settings?->tahun_aktif ?? date('Y');
        
        // Ambil seluruh IKU yang memiliki target di program studi ini untuk tahun akademik aktif
        $ikus = Iku::whereIn('id', function ($query) use ($prodiId, $tahunAktif) {
            $query->select('id_iku')
                ->from('iku_pencapaian')
                ->where('id_prodi', $prodiId)
                ->where('tahun', $tahunAktif);
        })->get();
        
        // Ambil semua dokumen bukti yang memungkinkan untuk IKU tersebut
        $buktiIku = BuktiIku::whereIn('id_iku', $ikus->pluck('id'))->get();

        return view('adminprodi.pengisian.create', compact('ikus', 'buktiIku', 'tahunAktif', 'settings'));
    }

    /**
     * Simpan dokumen bukti yang diunggah oleh Kaprodi ke storage dan database.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'kaprodi') {
            abort(403, 'Akses khusus Kaprodi.');
        }

        $prodiId = $user->prodi_id;
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        
        $tahunAktif = $settings?->tahun_aktif ?? date('Y');

        $request->validate([
            'id_iku' => 'required|exists:iku,id',
            'id_bukti_iku' => 'required|exists:bukti_iku,id',
            'keterangan_files' => 'nullable|array',
            'keterangan_files.*' => 'nullable|string',
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png,zip,doc,docx|max:10240', // 10MB Max per file
        ], [
            'files.required' => 'Wajib memilih minimal satu berkas bukti.',
            'files.*.mimes' => 'Format berkas bukti harus berupa pdf, jpg, jpeg, png, zip, doc, atau docx.',
            'files.*.max' => 'Ukuran berkas bukti maksimal adalah 10 MB.',
        ]);

        // Keamanan: pastikan IKU tersebut dikonfigurasi target pencapaiannya untuk prodi ini
        $hasTarget = IkuPencapaian::where('id_prodi', $prodiId)
            ->where('id_iku', $request->id_iku)
            ->where('tahun', $tahunAktif)
            ->exists();

        if (!$hasTarget) {
            return redirect()->back()->withErrors(['id_iku' => 'Indikator IKU ini belum dikonfigurasi targetnya untuk program studi Anda pada tahun akademik aktif ini.'])->withInput();
        }

        $keterangans = $request->input('keterangan_files', []);
        $globalKeterangan = implode("; ", array_filter($keterangans));

        // Buat data pengajuan pengisian bukti
        $pengisian = PengisianBukti::create([
            'id_iku' => $request->id_iku,
            'id_bukti_iku' => $request->id_bukti_iku,
            'id_user' => $user->id,
            'tahun' => $tahunAktif,
            'keterangan' => $globalKeterangan ?: null,
            'status' => 'pending',
        ]);

        // Pastikan direktori uploads lokal sudah ada
        $uploadPath = public_path('uploads/bukti');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Proses berkas
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = 'bukti_' . time() . '_' . Str::random(10) . '.' . $extension;

                $file->move($uploadPath, $filename);

                FileIsiBukti::create([
                    'id_pengisian_bukti' => $pengisian->id,
                    'file_bukti' => 'uploads/bukti/' . $filename,
                    'nama_file' => $originalName,
                    'keterangan' => $keterangans[$key] ?? null,
                ]);
            }
        }

        // Jalankan sinkronisasi realisasi
        IkuPencapaian::calculateAndSync($prodiId, $tahunAktif);

        return redirect()->route('adminprodi.bukti-dosen')->with('success', 'Bukti IKU berhasil diunggah oleh Kaprodi dan sedang menunggu validasi P2MP.');
    }

    /**
     * Tampilkan form untuk mengedit bukti IKU yang diunggah oleh Kaprodi.
     */
    public function edit($id)
    {
        $user = auth()->user();
        if ($user->role !== 'kaprodi') {
            abort(403, 'Akses khusus Kaprodi.');
        }

        $pengisian = PengisianBukti::with('files')->where('id_user', $user->id)->findOrFail($id);

        if ($pengisian->status !== 'invalid') {
            return redirect()->route('adminprodi.bukti-dosen')->with('error', 'Hanya berkas dengan status "Perlu Perbaikan" yang dapat diedit.');
        }

        $prodiId = $user->prodi_id;
        $settings = Pengaturan::where('id_prodi', $prodiId)->first();
        $tahunAktif = $pengisian->tahun;

        // Ambil seluruh IKU yang memiliki target di program studi ini untuk tahun tersebut
        $ikus = Iku::whereIn('id', function ($query) use ($prodiId, $tahunAktif) {
            $query->select('id_iku')
                ->from('iku_pencapaian')
                ->where('id_prodi', $prodiId)
                ->where('tahun', $tahunAktif);
        })->get();

        $buktiIku = BuktiIku::whereIn('id_iku', $ikus->pluck('id'))->get();

        return view('adminprodi.pengisian.edit', compact('pengisian', 'ikus', 'buktiIku', 'tahunAktif', 'settings'));
    }

    /**
     * Perbarui bukti dukung di storage dan database untuk Kaprodi.
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->role !== 'kaprodi') {
            abort(403, 'Akses khusus Kaprodi.');
        }

        $pengisian = PengisianBukti::where('id_user', $user->id)->findOrFail($id);

        if ($pengisian->status !== 'invalid') {
            return redirect()->route('adminprodi.bukti-dosen')->with('error', 'Hanya berkas dengan status "Perlu Perbaikan" yang dapat diedit.');
        }

        $request->validate([
            'id_iku' => 'required|exists:iku,id',
            'id_bukti_iku' => 'required|exists:bukti_iku,id',
            'existing_keterangan' => 'nullable|array',
            'existing_keterangan.*' => 'nullable|string',
            'deleted_files' => 'nullable|array',
            'deleted_files.*' => 'integer',
            'keterangan_files' => 'nullable|array',
            'keterangan_files.*' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png,zip,doc,docx|max:10240', // 10MB Max per file
        ], [
            'files.*.mimes' => 'Format berkas bukti harus berupa pdf, jpg, jpeg, png, zip, doc, atau docx.',
            'files.*.max' => 'Ukuran berkas bukti maksimal adalah 10 MB.',
        ]);

        // Keamanan: pastikan IKU tersebut dikonfigurasi target pencapaiannya untuk prodi ini
        $hasTarget = IkuPencapaian::where('id_prodi', $user->prodi_id)
            ->where('id_iku', $request->id_iku)
            ->where('tahun', $pengisian->tahun)
            ->exists();

        if (!$hasTarget) {
            return redirect()->back()->withErrors(['id_iku' => 'Indikator IKU ini belum dikonfigurasi targetnya untuk program studi Anda pada tahun akademik pengajuan ini.'])->withInput();
        }

        // 1. Perbarui deskripsi berkas yang sudah ada
        if ($request->has('existing_keterangan')) {
            foreach ($request->input('existing_keterangan') as $fileId => $ket) {
                $fileIsi = FileIsiBukti::where('id_pengisian_bukti', $pengisian->id)->find($fileId);
                if ($fileIsi) {
                    $fileIsi->update(['keterangan' => $ket]);
                }
            }
        }

        // 2. Hapus berkas lama jika diminta
        if ($request->has('deleted_files')) {
            foreach ($request->input('deleted_files') as $fileId) {
                $fileIsi = FileIsiBukti::where('id_pengisian_bukti', $pengisian->id)->find($fileId);
                if ($fileIsi) {
                    $filePath = public_path($fileIsi->file_bukti);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                    $fileIsi->delete();
                }
            }
        }

        // 3. Proses berkas baru
        $keterangans = $request->input('keterangan_files', []);
        if ($request->hasFile('files')) {
            $uploadPath = public_path('uploads/bukti');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($request->file('files') as $key => $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = 'bukti_' . time() . '_' . Str::random(10) . '.' . $extension;

                $file->move($uploadPath, $filename);

                FileIsiBukti::create([
                    'id_pengisian_bukti' => $pengisian->id,
                    'file_bukti' => 'uploads/bukti/' . $filename,
                    'nama_file' => $originalName,
                    'keterangan' => $keterangans[$key] ?? null,
                ]);
            }
        }

        // 4. Pastikan setidaknya satu berkas tersisa!
        $remainingCount = FileIsiBukti::where('id_pengisian_bukti', $pengisian->id)->count();
        if ($remainingCount === 0) {
            return redirect()->back()->withErrors(['files' => 'Wajib menyisakan minimal satu berkas bukti.'])->withInput();
        }

        // 5. Perbarui keterangan global untuk kompatibilitas data lama
        $allFiles = FileIsiBukti::where('id_pengisian_bukti', $pengisian->id)->get();
        $summaryKeterangan = $allFiles->map(function ($f) {
            return $f->keterangan;
        })->filter()->implode("; ");

        // Perbarui data pengisian bukti
        $pengisian->update([
            'id_iku' => $request->id_iku,
            'id_bukti_iku' => $request->id_bukti_iku,
            'keterangan' => $summaryKeterangan ?: null,
            'status' => 'pending', // Reset status pengajuan menjadi pending!
            'catatan_validator' => null, // Hapus catatan validator!
        ]);

        // Jalankan sinkronisasi realisasi
        IkuPencapaian::calculateAndSync($user->prodi_id, $pengisian->tahun);

        return redirect()->route('adminprodi.bukti-dosen')->with('success', 'Bukti IKU berhasil diperbarui oleh Kaprodi dan sedang menunggu validasi ulang.');
    }
}
