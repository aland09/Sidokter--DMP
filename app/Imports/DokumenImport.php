<?php

namespace App\Imports;

use App\Models\Dokumen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;

class DokumenImport implements ToModel, WithHeadingRow, WithStartRow
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $data = Dokumen::select('no_sp2d')->where('no_sp2d', $row['i_sp2dno_dok'])->first();
        if(empty($data)) {
            if($row['c_wil_sp2dproses'] === '1') {
                $unit_pengolah = 'SBPK-JP';
            } else if($row['c_wil_sp2dproses'] === '2') {
                $unit_pengolah = 'SBPK-JU';
            } else if($row['c_wil_sp2dproses'] === '3') {
                $unit_pengolah = 'SBPK-JB';
            } else if($row['c_wil_sp2dproses'] === '4') {
                $unit_pengolah = 'SBPK-JS';
            } else if($row['c_wil_sp2dproses'] === '5') {
                $unit_pengolah = 'SBPK-JT';
            }

            return new Dokumen([
                'kode_klasifikasi' => 'UD.02.02',
                'uraian' => $row['e_spp'],
                'tanggal_validasi' => \Carbon\Carbon::parse($row['d_sp2d_sah'])->isoFormat('YYYY-MM-DD HH:mm:ss'),
                'no_spm' => $row['i_spmno_dok'],
                'no_sp2d' => $row['i_sp2dno_dok'],
                'nominal' => $row['v_spp'],
                'skpd' => $row['n_opd'],
                'pejabat_penandatangan' => 'Kuasa Bendahara Umum Daerah',
                'unit_pengolah' => $unit_pengolah,
                'kurun_waktu' => $row['c_angg_tahun'],
                'jumlah_satuan_berkas' => '1 Berkas',
                'tkt_perkemb' => 'Tembusan',
                'status' => 'Menunggu Verifikasi'
            ]);
        };
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}
