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
        [
            'id' => 1,
            'nama_kategori' => 'Pendidikan',
            'deskripsi' => 'Kategori IKU yang berkaitan dengan penyelenggaraan pendidikan tinggi, efektivitas proses pembelajaran, kurikulum, kelulusan tepat waktu, masa studi sesuai standar, angka efisiensi edukasi perguruan tinggi (AEE PT), penyerapan lulusan, lulusan bekerja, berwirausaha, melanjutkan studi, serta kesesuaian kompetensi lulusan dengan kebutuhan dunia kerja dan industri.'
        ],
        [
            'id' => 2,
            'nama_kategori' => 'Kemahasiswaan',
            'deskripsi' => 'Kategori IKU yang berkaitan dengan aktivitas, partisipasi, dan prestasi mahasiswa, meliputi organisasi kemahasiswaan, kompetisi akademik maupun non-akademik, penelitian mahasiswa, pengabdian kepada masyarakat, kewirausahaan, pertukaran pelajar, pengembangan soft skills, kepemimpinan, kreativitas, inovasi, serta pencapaian prestasi di tingkat nasional maupun internasional.'
        ],
        [
            'id' => 3,
            'nama_kategori' => 'SDM',
            'deskripsi' => 'Kategori IKU yang berkaitan dengan sumber daya manusia perguruan tinggi, khususnya dosen, meliputi rekognisi dosen, penghargaan, kepakaran, sitasi ilmiah, keterlibatan dalam penyusunan kebijakan, konsultasi pemerintah, kontribusi kepada industri, pengembangan karier, peningkatan kompetensi, perlindungan, serta kesejahteraan dosen.'
        ],
        [
            'id' => 4,
            'nama_kategori' => 'Penelitian & Pengabdian',
            'deskripsi' => 'Kategori IKU yang berkaitan dengan penelitian, pengabdian kepada masyarakat, kerja sama dengan industri, pemerintah, sekolah, dan mitra lainnya, hilirisasi hasil penelitian dan inovasi, pemanfaatan hasil riset, publikasi ilmiah internasional, jurnal bereputasi, Scopus, Web of Science (WoS), serta peningkatan reputasi akademik perguruan tinggi.'
        ],
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
            'deskripsi' => 'Mengukur efisiensi pendidikan di perguruan tinggi melalui kelulusan tepat waktu, masa studi sesuai standar, efektivitas proses akademik, penurunan angka drop out (DO), pengurangan keterlambatan kelulusan, serta keberhasilan mahasiswa menyelesaikan studi sesuai kurikulum.',
        ],
        [
            'id' => 2,
            'id_kategori' => 1,
            'nama_iku' => 'Lulusan Terserap',
            'deskripsi' => 'Mengukur keberhasilan lulusan yang bekerja, berwirausaha, atau melanjutkan studi paling lama satu tahun setelah lulus, serta kesesuaian kompetensi lulusan dengan kebutuhan dunia kerja, industri, dan masyarakat.',
        ],
        [
            'id' => 3,
            'id_kategori' => 2,
            'nama_iku' => 'Aktivitas & Prestasi Mahasiswa',
            'deskripsi' => 'Mengukur keterlibatan mahasiswa dalam kegiatan di luar program studi seperti kompetisi, organisasi, pengabdian kepada masyarakat, penelitian, pertukaran pelajar, kewirausahaan, serta prestasi akademik dan non-akademik.',
        ],
        [
            'id' => 4,
            'id_kategori' => 3,
            'nama_iku' => 'Rekognisi Dosen',
            'deskripsi' => 'Mengukur pengakuan terhadap dosen melalui penghargaan nasional maupun internasional, kepakaran, sitasi ilmiah, publikasi, inovasi, hasil penelitian yang dimanfaatkan masyarakat, serta kontribusi dalam pengembangan ilmu pengetahuan.',
        ],
        [
            'id' => 5,
            'id_kategori' => 4,
            'nama_iku' => 'Kerja Sama & Hilirisasi',
            'deskripsi' => 'Mengukur kualitas kerja sama perguruan tinggi dengan industri, pemerintah, sekolah, lembaga, dunia usaha, dan mitra lainnya, serta hilirisasi hasil penelitian atau inovasi agar memberikan manfaat nyata bagi masyarakat dan dunia industri.',
        ],
        [
            'id' => 6,
            'id_kategori' => 4,
            'nama_iku' => 'Publikasi Internasional',
            'deskripsi' => 'Mengukur jumlah dan kualitas publikasi ilmiah internasional bereputasi yang terindeks Scopus atau Web of Science (WoS), sebagai indikator reputasi akademik dan kualitas penelitian perguruan tinggi.',
        ],
        [
            'id' => 7,
            'id_kategori' => 3,
            'nama_iku' => 'SDM dalam Kebijakan',
            'deskripsi' => 'Mengukur keterlibatan dosen atau tenaga ahli perguruan tinggi dalam penyusunan kebijakan, konsultasi pemerintah, dunia industri, lembaga nasional maupun daerah, serta kontribusi keilmuan dalam pengambilan keputusan publik.',
        ],
        [
            'id' => 8,
            'id_kategori' => 3,
            'nama_iku' => 'Kesejahteraan Dosen',
            'deskripsi' => 'Mengukur upaya perguruan tinggi dalam meningkatkan kesejahteraan dosen melalui pengembangan karier, perlindungan, penghargaan, peningkatan kompetensi, serta perencanaan kesejahteraan dosen secara berkelanjutan.',
        ],
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
