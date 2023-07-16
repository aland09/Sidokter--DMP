<?php

namespace App\Exports;

use App\Models\DetailDokumen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;

class DetailDokumenExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
{

    public function startCell(): string
    {
        return 'A2';
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DetailDokumen::select(
                        'dokumen_id',
                        'kode_klasifikasi',
                        'uraian',
                        'tanggal_surat',
                        'jumlah_satuan',
                        'keterangan',
                        'jenis_naskah_dinas',
                        'no_surat',
                        'pejabat_penandatangan',
                        'unit_pengolah',
                        'kurun_waktu',
                        'no_box',
                        'tkt_perk'
                    )->get();;
    }

    public function registerEvents(): array {
        
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:M1');
                $sheet->setCellValue('A1', "DAFTAR ISI BERKAS");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:M1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'KODE BERKAS',
            'KODE KLASIFIKASI',
            'URAIAN',
            'TANGGAL SURAT',
            'JUMLAH SATUAN',
            'KETERANGAN',
            'JENIS NASKAH DINAS',
            'NO. SURAT',
            'PEJABAT PENANDATANGAN',
            'UNIT PENGOLAH',
            'KURUN WAKTU',
            'NO. BOX',
            'TKT. PERK',
        ];
    }
}
