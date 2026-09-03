<?php

namespace App\Exports;

use App\Models\IkuPencapaian;
use App\Models\Pengaturan;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    protected $prodiId;
    protected $tahun;
    protected $prodiName;
    protected $settingsMap;

    public function __construct($prodiId, $tahun, $prodiName = 'Semua Program Studi')
    {
        $this->prodiId = $prodiId;
        $this->tahun = $tahun;
        $this->prodiName = $prodiName ?: 'Semua Program Studi';
        $this->settingsMap = Pengaturan::all()->keyBy('id_prodi');
    }

    public function collection(): Enumerable
    {
        $query = IkuPencapaian::with(['prodi', 'iku.kategori'])
            ->where('tahun', $this->tahun);

        if ($this->prodiId) {
            $query->where('id_prodi', $this->prodiId);
        }

        return $query->orderBy('id_prodi')->orderBy('id', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Program Studi',
            'Kategori IKU/IKT',
            'Nama Indikator IKU/IKT',
            'Target Sasaran',
            'Satuan',
            'Objek',
            'Realisasi (Valid)',
            'Capaian (%)',
            'Status'
        ];
    }

    public function map($item): array
    {
        static $no = 1;

        $targetVal = floatval($item->target);
        $settings = $this->settingsMap->get($item->id_prodi);
        $jml_mahasiswa = $settings ? $settings->jml_mahasiswa : 0;
        $jml_dosen = $settings ? $settings->jml_dosen : 0;

        if ($item->satuan === 'persen') {
            if ($item->objek === 'mahasiswa') {
                $targetNyata = ($targetVal / 100) * $jml_mahasiswa;
            } elseif ($item->objek === 'dosen') {
                $targetNyata = ($targetVal / 100) * $jml_dosen;
            } else {
                $targetNyata = $targetVal;
            }
        } else {
            $targetNyata = $targetVal;
        }

        if ($targetNyata > 0) {
            $persentase = min(round(($item->realisasi / $targetNyata) * 100), 100);
        } else {
            $persentase = $item->realisasi > 0 ? 100 : 0;
        }

        return [
            $no++,
            $item->prodi->nama_prodi ?? '-',
            $item->iku->kategori->nama_kategori ?? '-',
            $item->iku->nama_iku ?? '-',
            $item->target,
            $item->satuan === 'persen' ? '%' : $item->satuan,
            $item->objek,
            round($item->realisasi) . ' Bukti',
            $persentase . '%',
            $item->status
        ];
    }
    
    public function startCell(): string
    {
        return 'A4';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $sheet->setCellValue('A1', 'LAPORAN CAPAIAN INDIKATOR KINERJA UTAMA (IKU/IKT)');
                $sheet->setCellValue('A2', 'PROGRAM STUDI: ' . strtoupper($this->prodiName) . ' - TAHUN AKADEMIK ' . $this->tahun);
                
                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');
                
                $sheet->getStyle('A1:A2')->getFont()->setBold(true);
                $sheet->getStyle('A1')->getFont()->setSize(14);
                
                $sheet->getStyle('A4:J4')->getFont()->setBold(true);
                $sheet->getStyle('A4:J4')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF1F5F9');
                    
                foreach (range('A', 'J') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
