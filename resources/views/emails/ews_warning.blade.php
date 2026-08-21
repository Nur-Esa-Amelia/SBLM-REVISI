<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EWS Alert - Ketercapaian IKU/IKT</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
            color: #1f2937;
        }
        .alert-card {
            background-color: #fff1f2;
            border-left: 4px solid #f43f5e;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .alert-card-title {
            font-weight: 700;
            color: #9f1239;
            margin-bottom: 6px;
            font-size: 15px;
        }
        .alert-card-text {
            color: #be123c;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .info-table th {
            text-align: left;
            padding: 10px 12px;
            background-color: #f9fafb;
            color: #4b5563;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            color: #374151;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #d97706;
            border: 1px solid #fcd34d;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fca5a5;
        }
        .btn {
            display: block;
            width: 200px;
            margin: 30px auto 10px auto;
            text-align: center;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(124, 58, 237, 0.2);
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SISTEM EARLY WARNING IKU/IKT</h1>
            <p>Peringatan Kinerja Ketercapaian Indikator</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="alert-card">
                <div class="alert-card-title">Terdeteksi Indikator Kinerja Utama (IKU/IKT) Bermasalah</div>
                <p class="alert-card-text">
                    Sistem Early Warning System (EWS) mendeteksi bahwa indikator di bawah ini memerlukan perhatian segera karena belum memenuhi target yang ditetapkan untuk tahun akademik {{ $pencapaian->tahun }}.
                </p>
            </div>

            <!-- Detail Table -->
            <table class="info-table">
                <thead>
                    <tr>
                        <th colspan="2">Detail Indikator</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 35%; font-weight: 600;">Program Studi</td>
                        <td>{{ $pencapaian->prodi ? $pencapaian->prodi->nama_prodi : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Indikator IKU/IKT</td>
                        <td><strong>{{ $pencapaian->iku ? $pencapaian->iku->nama_iku : '-' }}</strong></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Deskripsi IKU/IKT</td>
                        <td style="font-size: 13px; color: #4b5563;">{{ $pencapaian->iku ? $pencapaian->iku->deskripsi : '-' }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Target</td>
                        <td>{{ $pencapaian->target }} {{ $pencapaian->satuan === 'persen' ? '%' : 'berkas' }} (Objek: {{ ucfirst($pencapaian->objek) }})</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Realisasi Saat Ini</td>
                        <td>{{ $pencapaian->realisasi }} berkas valid</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Tahun Evaluasi</td>
                        <td>{{ $pencapaian->tahun }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Status EWS</td>
                        <td>
                            @if($pencapaian->status === 'Perlu Perhatian')
                                <span class="badge badge-warning">Perlu Perhatian</span>
                            @else
                                <span class="badge badge-danger">Tidak Tercapai</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <p style="font-size: 14px; line-height: 1.6; color: #4b5563;">
                Mohon untuk segera berkoordinasi dengan dosen penanggung jawab indikator tersebut guna mengunggah bukti tambahan atau memperbaiki bukti yang tidak valid agar status pencapaian IKU/IKT ini dapat kembali terpenuhi.
            </p>

            <!-- CTA Button -->
            <a href="{{ url('/adminprodi/pencapaian?tahun=' . $pencapaian->tahun) }}" class="btn">Buka Dashboard IKU/IKT</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Email ini dikirimkan secara otomatis oleh Sistem Early Warning IKU/IKT.</p>
            <p>&copy; {{ date('Y') }} Tim Penjaminan Mutu &ndash; Universitas</p>
        </div>
    </div>
</body>
</html>
