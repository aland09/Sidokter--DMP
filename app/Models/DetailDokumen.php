<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dokumen;

class DetailDokumen extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'tkt_perk',
        'file_dokumen',
    ];

    public function dokumen(){
    	return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }
}
