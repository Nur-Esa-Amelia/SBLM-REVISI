<div style="display: flex; flex-direction: column; gap: 20px;">
    <form action="{{ route('adminprodi.pengaturan.store') }}" method="POST" id="pengaturan-form-modal">
        @csrf

        @if(auth()->user()->role === 'admin_p2mp')
            <input type="hidden" name="prodi_id" value="{{ $selectedProdiId }}">
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <!-- Tahun Mulai -->
            <div class="form-group-custom" style="margin-bottom: 0;">
                <label for="tahun_mulai" class="form-label-custom">Tahun Mulai Rentang</label>
                <input type="number" 
                       id="tahun_mulai" 
                       name="tahun_mulai" 
                       value="{{ old('tahun_mulai', $pengaturan->tahun_mulai) }}" 
                       placeholder="Contoh: 2026" 
                       class="form-input-custom" 
                       required>
            </div>

            <!-- Tahun Selesai -->
            <div class="form-group-custom" style="margin-bottom: 0;">
                <label for="tahun_selesai" class="form-label-custom">Tahun Selesai Rentang</label>
                <input type="number" 
                       id="tahun_selesai" 
                       name="tahun_selesai" 
                       value="{{ old('tahun_selesai', $pengaturan->tahun_selesai) }}" 
                       placeholder="Contoh: 2030" 
                       class="form-input-custom" 
                       required>
            </div>
        </div>

        @if(auth()->user()->role === 'admin_p2mp' && isset($prodis))
            <!-- Pilih Program Studi untuk Dikonfigurasi -->
            <div class="form-group-custom" style="margin-bottom: 20px;">
                <label for="prodi_id_select" class="form-label-custom">Pilih Program Studi untuk Dikonfigurasi</label>
                <select id="prodi_id_select" class="form-select-custom" onchange="loadPengaturanModal(this.value)">
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ $selectedProdiId == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- Tahun Aktif -->
        <div class="form-group-custom" style="margin-bottom: 20px;">
            <label for="tahun_aktif" class="form-label-custom">Tahun Akademik Aktif Saat Ini</label>
            <input type="number" 
                   id="tahun_aktif" 
                   name="tahun_aktif" 
                   value="{{ old('tahun_aktif', $pengaturan->tahun_aktif) }}" 
                   placeholder="Contoh: 2026" 
                   class="form-input-custom" 
                   required>
            <small style="color: #64748b; font-size: 0.75rem; margin-top: 4px; display: block;">Tahun aktif digunakan sebagai default filter monitoring di seluruh dashboard.</small>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
            <!-- Jumlah Mahasiswa -->
            <div class="form-group-custom" style="margin-bottom: 0;">
                <label for="jml_mahasiswa" class="form-label-custom">Total Mahasiswa Aktif</label>
                <input type="number" 
                       id="jml_mahasiswa" 
                       name="jml_mahasiswa" 
                       value="{{ old('jml_mahasiswa', $pengaturan->jml_mahasiswa) }}" 
                       placeholder="Contoh: 350" 
                       class="form-input-custom" 
                       required>
            </div>

            <!-- Jumlah Dosen -->
            <div class="form-group-custom" style="margin-bottom: 0;">
                <label for="jml_dosen" class="form-label-custom">Total Dosen Aktif</label>
                <input type="number" 
                       id="jml_dosen" 
                       name="jml_dosen" 
                       value="{{ old('jml_dosen', $pengaturan->jml_dosen) }}" 
                       placeholder="Contoh: 15" 
                       class="form-input-custom" 
                       required>
            </div>
        </div>

        <!-- Submit actions -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border); padding-top: 20px;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('pengaturan-sistem-modal').style.display='none'">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Konfigurasi</button>
        </div>
    </form>
</div>
