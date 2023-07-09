<?php

namespace App\Imports;

use App\Models\Dokumen;
use App\Models\DetailDokumen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;

class DetailDokumenImport implements ToModel, WithHeadingRow, WithStartRow
{
    use Importable;

    protected $dokumens;
    public function __construct()
    {
        //QUERY UNTUK MENGAMBIL SELURUH DATA USER
        $this->dokumens = Dokumen::select('id', 'no_sp2d')->get();
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $dokumen = $this->dokumens->where('no_sp2d', $row['i_sp2dno_dok'])->first();
    
        return new DetailDokumen([
            'dokumen_id' => $dokumen->id ?? NULL,
            'kode_klasifikasi' => $dokumen->kode_klasifikasi,
            'uraian' => $row['e_spp'],
            'tanggal_surat' => \Carbon\Carbon::parse($row['d_sppno'])->isoFormat('YYYY-MM-DD HH:mm:ss'),
            'no_surat' => $row['i_sppno_dok'],
            'unit_pengolah' => $row['n_opd'],
            'kurun_waktu' => $row['c_angg_tahun'],
            'tkt_perk' => 'Asli',
        ]);
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }
}