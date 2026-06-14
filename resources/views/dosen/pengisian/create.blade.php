@extends('dosen.layouts.app')

@section('title', 'Unggah Bukti IKU - Dosen')
@section('page_title', 'Unggah Bukti IKU')
@section('page_subtitle', 'Kirim berkas bukti pemenuhan Indikator Kinerja Utama Anda')

@section('content')
<div class="card" style="max-width: 650px; margin: 0 auto;">
    <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid #1e293b; padding-bottom: 12px;">Form Unggah Bukti Kinerja - Tahun {{ $tahunAktif }}</h3>

    <form action="{{ route('dosen.pengisian.store') }}" method="POST" enctype="multipart/form-data" class="form-layout-container">
        @csrf

        <!-- IKU Select -->
        <div class="form-group-custom">
            <label for="id_iku" class="form-label-custom">Pilih Indikator IKU Ditugaskan</label>
            <select id="id_iku" name="id_iku" class="form-select-custom @error('id_iku') is-invalid @enderror" required>
                <option value="">-- Pilih Indikator --</option>
                @foreach($ikus as $item)
                    <option value="{{ $item->id }}" {{ old('id_iku', request('id_iku')) == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_iku }}
                    </option>
                @endforeach
            </select>
            @error('id_iku')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Jenis Bukti Select -->
        <div class="form-group-custom">
            <label for="id_bukti_iku" class="form-label-custom">Pilih Jenis Bukti Dokumen</label>
            <select id="id_bukti_iku" name="id_bukti_iku" class="form-select-custom @error('id_bukti_iku') is-invalid @enderror" required>
                <option value="">-- Pilih Jenis Bukti --</option>
                @foreach($buktiIku as $bukti)
                    <option value="{{ $bukti->id }}" data-iku-id="{{ $bukti->id_iku }}" {{ old('id_bukti_iku') == $bukti->id ? 'selected' : '' }}>
                        {{ $bukti->nama_bukti }}
                    </option>
                @endforeach
            </select>
            <small style="color: #64748b; font-size: 0.75rem; margin-top: 2px;">Pilihan jenis bukti disesuaikan secara otomatis berdasarkan IKU yang Anda pilih di atas.</small>
            @error('id_bukti_iku')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Berkas Bukti (Dynamic Cards) -->
        <div class="form-group-custom">
            <label class="form-label-custom">Unggah Berkas Bukti</label>
            <div id="file-inputs-container" style="display: flex; flex-direction: column; gap: 16px;">
                <div class="file-input-card" style="background-color: rgba(30, 41, 59, 0.25); border: 1px solid #1e293b; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 12px; position: relative;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="file-number-label" style="font-size: 0.8rem; font-weight: 700; color: #10b981;">Berkas Bukti #1</span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="form-label-custom" style="font-size: 0.72rem; color: #94a3b8; font-weight: 600;">Pilih Berkas</label>
                        <input type="file" name="files[]" class="form-input-custom file-selector-input" required>
                    </div>
                    
                    <div class="keterangan-file-group" style="display: none; flex-direction: column; gap: 6px;">
                        <label class="form-label-custom" style="font-size: 0.72rem; color: #94a3b8; font-weight: 600;">Keterangan Berkas</label>
                        <textarea name="keterangan_files[]" rows="2" placeholder="Tulis keterangan spesifik untuk berkas ini (contoh: Link Google Drive, Judul Dokumen, dll)..." class="form-input-custom file-keterangan-input"></textarea>
                    </div>
                </div>
            </div>
            
            <button type="button" id="add-file-btn" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.75rem; align-self: flex-start; margin-top: 12px; display: none;">
                <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                Tambah File Lainnya
            </button>
            
            <small style="color: #64748b; font-size: 0.75rem; display: block; margin-top: 12px;">Format berkas yang didukung: <strong>PDF, JPG, JPEG, PNG, ZIP, DOC, DOCX</strong>. Maksimal ukuran per file: <strong>10 MB</strong>.</small>
            @error('files')
                <span class="form-error-custom">{{ $message }}</span>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="form-footer-actions">
            <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Unggah Berkas Bukti</button>
        </div>
    </form>
</div>

<!-- Dynamic Select & File Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ikuSelect = document.getElementById('id_iku');
        const buktiSelect = document.getElementById('id_bukti_iku');
        
        // Cache all seeded options from server
        const originalBuktiOptions = Array.from(buktiSelect.querySelectorAll('option')).filter(opt => opt.value !== '');
        
        function updateBuktiOptions() {
            const selectedIkuId = ikuSelect.value;
            
            // Clear current options except the placeholder
            buktiSelect.innerHTML = '<option value="">-- Pilih Jenis Bukti --</option>';
            
            if (selectedIkuId) {
                // Filter matching options
                const filtered = originalBuktiOptions.filter(opt => opt.getAttribute('data-iku-id') === selectedIkuId);
                
                if (filtered.length > 0) {
                    filtered.forEach(opt => buktiSelect.appendChild(opt.cloneNode(true)));
                } else {
                    const noOpt = document.createElement('option');
                    noOpt.value = "";
                    noOpt.disabled = true;
                    noOpt.textContent = "Belum ada jenis bukti yang dikonfigurasi untuk IKU ini oleh Admin Prodi";
                    buktiSelect.appendChild(noOpt);
                }
            }
        }
        
        ikuSelect.addEventListener('change', updateBuktiOptions);
        
        // Initial setup for default values (preselected on error redirect or dashboard shortcut)
        if (ikuSelect.value) {
            updateBuktiOptions();
            const oldBuktiId = "{{ old('id_bukti_iku') }}";
            if (oldBuktiId) {
                buktiSelect.value = oldBuktiId;
            }
        }

        // Dynamic File Upload UI
        const fileInputsContainer = document.getElementById('file-inputs-container');
        const addFileBtn = document.getElementById('add-file-btn');

        function updateLabels() {
            const cards = fileInputsContainer.querySelectorAll('.file-input-card');
            cards.forEach((card, index) => {
                const label = card.querySelector('.file-number-label');
                if (label) {
                    label.textContent = `Berkas Bukti #${index + 1}`;
                }
            });
        }

        function checkFileVisibility() {
            let hasFile = false;
            const cards = fileInputsContainer.querySelectorAll('.file-input-card');
            
            cards.forEach(card => {
                const fileInput = card.querySelector('.file-selector-input');
                const ketGroup = card.querySelector('.keterangan-file-group');
                
                if (fileInput.files && fileInput.files.length > 0) {
                    hasFile = true;
                    if (ketGroup) {
                        ketGroup.style.display = 'flex';
                    }
                } else {
                    if (ketGroup) {
                        ketGroup.style.display = 'none';
                    }
                }
            });

            if (hasFile) {
                addFileBtn.style.display = 'inline-flex';
            } else {
                addFileBtn.style.display = 'none';
            }
        }

        // Handle change on file inputs
        fileInputsContainer.addEventListener('change', (e) => {
            if (e.target.classList.contains('file-selector-input')) {
                checkFileVisibility();
            }
        });

        // Add file button click handler
        addFileBtn.addEventListener('click', () => {
            const nextIdx = fileInputsContainer.querySelectorAll('.file-input-card').length + 1;
            const newCard = document.createElement('div');
            newCard.className = 'file-input-card';
            newCard.style.cssText = 'background-color: rgba(30, 41, 59, 0.25); border: 1px solid #1e293b; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 12px; position: relative; margin-top: 12px;';

            newCard.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="file-number-label" style="font-size: 0.8rem; font-weight: 700; color: #10b981;">Berkas Bukti #${nextIdx}</span>
                    <button type="button" class="btn-action-delete remove-file-btn" style="padding: 12px; height: auto; border-radius: 10px;" title="Hapus File">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="form-label-custom" style="font-size: 0.72rem; color: #94a3b8; font-weight: 600;">Pilih Berkas</label>
                    <input type="file" name="files[]" class="form-input-custom file-selector-input" required>
                </div>
                
                <div class="keterangan-file-group" style="display: none; flex-direction: column; gap: 6px;">
                    <label class="form-label-custom" style="font-size: 0.72rem; color: #94a3b8; font-weight: 600;">Keterangan Berkas</label>
                    <textarea name="keterangan_files[]" rows="2" placeholder="Tulis keterangan spesifik untuk berkas ini (contoh: Link Google Drive, Judul Dokumen, dll)..." class="form-input-custom file-keterangan-input"></textarea>
                </div>
            `;

            fileInputsContainer.appendChild(newCard);
            updateLabels();
        });

        // Remove file row handler
        fileInputsContainer.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.remove-file-btn');
            if (removeBtn) {
                const card = removeBtn.closest('.file-input-card');
                if (card) {
                    card.remove();
                    updateLabels();
                    checkFileVisibility();
                }
            }
        });

        // Run check initial visibility
        checkFileVisibility();
    });
</script>
@endsection
