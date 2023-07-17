<?php

namespace App\Exports;

use App\Models\Dokumen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;

class DokumenExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
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
        return Dokumen::select(
                            'kode_klasifikasi',
                            'uraian',
                            'tanggal_validasi',
                            'jumlah_satuan_item',
                            'keterangan',
                            'no_spm',
                            'no_sp2d',
                            'nominal',
                            'pejabat_penandatangan',
                            'unit_pengolah',
                            'kurun_waktu',
                            'jumlah_satuan_berkas',
                            'tkt_perkemb',
                            'no_box'
                        )->get();;
    }

    public function registerEvents(): array {
        
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:N1');
                $sheet->setCellValue('A1', "DAFTAR ARSIP");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:N1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'KODE KLASIFIKASI',
            'URAIAN', 
            'TANGGAL VALIDASI',
            'JUMLAH SATUAN ITEM',
            'KETERANGAN',
            'NO. SPM',
            'NO.SP2D',
            'NOMINAL',
            'PEJABAT PENANDATANGAN',
            'UNIT PENGOLAH',
            'KURUN WAKTU',
            'JUMLAH SATUAN BERKAS',
            'TKT. PERKEMB',
            'NO. BOX'
        ];
    }
}
