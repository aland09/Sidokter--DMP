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

        $data = Dokumen::select('no_sp2d')->where('no_sp2d', $row['i_sppno_dok'])->first();
        if(empty($data)) {
            return new Dokumen([
                'no_sp2d' => $row['i_sppno_dok'],
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
