<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DetailDokumen;

class Dokumen extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_sp2d',
        'kode_klasifikasi',
        'uraian',
        'tanggal_validasi',
        'jumlah_satuan_item',
        'keterangan',
        'no_spm',
        'no_surat',
        'nominal',
        'skpd',
        'nwp',
        'pejabat_penandatangan',
        'unit_pengolah',
        'kurun_waktu',
        'jumlah_satuan_berkas',
        'tkt_perkemb',
        'no_box',
        'status',
    ];

    public function detailDokumen(){
    	return $this->hasMany(DetailDokumen::class);
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('no_sp2d', 'like', '%' .  $search . '%')
            ->orWhere('skpd', 'like', '%' .  $search . '%')
            ->orWhere('nwp', 'like', '%' .  $search . '%')
            ->orWhere('kode_klasifikasi', 'like', '%' .  $search . '%')
            ->orWhere('uraian', 'like', '%' .  $search . '%')
            ->orWhere('keterangan', 'like', '%' .  $search . '%')
            ->orWhere('pejabat_penandatangan', 'like', '%' .  $search . '%')
            ->orWhere('unit_pengolah', 'like', '%' .  $search . '%')
            ->orWhere('tkt_perkemb', 'like', '%' .  $search . '%')
            ->orWhere('kurun_waktu', 'like', '%' .  $search . '%');
        });
    }
}
