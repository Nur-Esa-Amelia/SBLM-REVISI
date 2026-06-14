<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Iku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProdiSeeder::class,
        ]);

        // Seed 7 Categories
        $categoriesData = [
            ['id' => 1, 'nama_kategori' => 'Pendidikan', 'deskripsi' => 'Kategori IKU terkait proses pembelajaran, kurikulum, dan kelulusan.'],
            ['id' => 2, 'nama_kategori' => 'Kemahasiswaan', 'deskripsi' => 'Kategori IKU terkait prestasi mahasiswa dan daya saing lulusan.'],
            ['id' => 3, 'nama_kategori' => 'SDM', 'deskripsi' => 'Kategori IKU terkait kualifikasi, sertifikasi, dan kompetensi dosen/staf.'],
            ['id' => 4, 'nama_kategori' => 'Penelitian & Pengabdian', 'deskripsi' => 'Kategori IKU terkait hasil penelitian, pengabdian masyarakat, dan publikasi.'],
            ['id' => 5, 'nama_kategori' => 'Kerjasama & Kelembagaan', 'deskripsi' => 'Kategori IKU terkait kemitraan industri dan standar kelembagaan.'],
            ['id' => 6, 'nama_kategori' => 'Sarana & Prasarana', 'deskripsi' => 'Kategori IKU terkait fasilitas laboratorium, perpustakaan, dan infrastruktur.'],
            ['id' => 7, 'nama_kategori' => 'Tata Kelola & Keuangan', 'deskripsi' => 'Kategori IKU terkait manajemen mutu, tata kelola, dan kepuasan pengguna.'],
        ];

        foreach ($categoriesData as $cat) {
            Kategori::updateOrCreate(
                ['id' => $cat['id']],
                [
                    'nama_kategori' => $cat['nama_kategori'],
                    'deskripsi' => $cat['deskripsi']
                ]
            );
        }

        // Seed 12 IKUs
        $ikusData = [
            [
                'id' => 1,
                'id_kategori' => 1,
                'nama_iku' => 'AEE PT',
                'deskripsi' => 'Mengukur efisiensi pendidikan melalui kelulusan tepat waktu, masa studi standar, dan efektivitas proses akademik.',
            ],
            [
                'id' => 2,
                'id_kategori' => 1,
                'nama_iku' => 'Lulusan Terserap',
                'deskripsi' => 'Mengukur keberhasilan lulusan dalam bekerja, berwirausaha, atau melanjutkan studi maksimal 1 tahun setelah lulus.',
            ],
            [
                'id' => 3,
                'id_kategori' => 2,
                'nama_iku' => 'Aktivitas & Prestasi Mahasiswa',
                'deskripsi' => 'Mengukur keterlibatan dan prestasi mahasiswa dalam kegiatan akademik maupun non-akademik.',
            ],
            [
                'id' => 4,
                'id_kategori' => 3,
                'nama_iku' => 'Rekognisi Dosen',
                'deskripsi' => 'Mengukur pengakuan dan reputasi dosen melalui penghargaan, sitasi, dan kontribusi keilmuan.',
            ],
            [
                'id' => 5,
                'id_kategori' => 4,
                'nama_iku' => 'Kerja Sama & Hilirisasi',
                'deskripsi' => 'Mengukur kualitas kerja sama dan pemanfaatan hasil riset atau inovasi secara nyata.',
            ],
            [
                'id' => 6,
                'id_kategori' => 4,
                'nama_iku' => 'Publikasi Internasional',
                'deskripsi' => 'Mengukur jumlah publikasi ilmiah bereputasi internasional yang terindeks Scopus atau WoS.',
            ],
            [
                'id' => 7,
                'id_kategori' => 5,
                'nama_iku' => 'Kontribusi SDGs',
                'deskripsi' => 'Mengukur kontribusi perguruan tinggi terhadap pencapaian Sustainable Development Goals (SDGs).',
            ],
            [
                'id' => 8,
                'id_kategori' => 3,
                'nama_iku' => 'SDM dalam Kebijakan',
                'deskripsi' => 'Mengukur keterlibatan dosen atau tenaga ahli dalam penyusunan kebijakan dan konsultasi publik.',
            ],
            [
                'id' => 9,
                'id_kategori' => 6,
                'nama_iku' => 'Pendapatan Non-UKT',
                'deskripsi' => 'Mengukur kemampuan perguruan tinggi memperoleh pendapatan selain dari UKT.',
            ],
            [
                'id' => 10,
                'id_kategori' => 7,
                'nama_iku' => 'Zona Integritas',
                'deskripsi' => 'Mengukur pembangunan budaya birokrasi bersih menuju WBK dan WBBM.',
            ],
            [
                'id' => 11,
                'id_kategori' => 7,
                'nama_iku' => 'Tata Kelola Berintegritas',
                'deskripsi' => 'Mengukur kualitas tata kelola kampus yang transparan, akuntabel, dan profesional.',
            ],
            [
                'id' => 12,
                'id_kategori' => 3,
                'nama_iku' => 'Kesejahteraan Dosen',
                'deskripsi' => 'Mengukur upaya peningkatan kesejahteraan, pengembangan karier, dan perlindungan dosen.',
            ]
        ];

        foreach ($ikusData as $iku) {
            Iku::updateOrCreate(
                ['id' => $iku['id']],
                [
                    'id_kategori' => $iku['id_kategori'],
                    'nama_iku' => $iku['nama_iku'],
                    'deskripsi' => $iku['deskripsi']
                ]
            );
        }

        // 1. Admin P2MP (Not tied to any prodi)
        \App\Models\User::updateOrCreate([
            'email' => 'admin.p2mp@gmail.com'
        ], [
            'name' => 'Admin P2MP',
            'password' => bcrypt('password'),
            'role' => 'admin_p2mp',
            'prodi_id' => null,
        ]);

        // Fetch all seeded prodis
        $prodis = \App\Models\Prodi::all();

        foreach ($prodis as $prodi) {
            $slug = strtolower($prodi->kode_prodi);

            // 2. Admin Prodi
            \App\Models\User::updateOrCreate([
                'email' => "admin.{$slug}@gmail.com"
            ], [
                'name' => "Admin Prodi {$prodi->nama_prodi}",
                'password' => bcrypt('password'),
                'role' => 'admin_prodi',
                'prodi_id' => $prodi->id,
            ]);

            // 3. Kaprodi
            \App\Models\User::updateOrCreate([
                'email' => "kaprodi.{$slug}@gmail.com"
            ], [
                'name' => "Kaprodi {$prodi->nama_prodi}",
                'password' => bcrypt('password'),
                'role' => 'kaprodi',
                'prodi_id' => $prodi->id,
            ]);

            // 4. Dosen
            \App\Models\User::updateOrCreate([
                'email' => "dosen.{$slug}@gmail.com"
            ], [
                'name' => "Dosen {$prodi->nama_prodi}",
                'password' => bcrypt('password'),
                'role' => 'dosen',
                'prodi_id' => $prodi->id,
            ]);
        }
    }
}
