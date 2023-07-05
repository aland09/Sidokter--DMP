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
        'tanggal_validasi',
        'jumlah_satuan_item',
        'keterangan',
        'no_spm',
        'no_sp2d',
        'nominal',
        'skpd',
        'pejabat_penandatangan',
        'unit_pengolah',
        'kurun_waktu',
        'jumlah_satuan_berkas',
        'tkt_perkemb',
        'no_box',
        'file_dokumen'
    ];

    public function dokumen(){
    	return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }
}
