<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dokumen;

class DokumenKeluar extends Model
{
    use HasFactory;

    protected $fillable = [
        'dokumen_id',
        'nama_peminjam',
        'tanggal_peminjaman',
        'instansi',
        'tujuan',
    ];

    public function dokumen(){
    	return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('dokumen.no_sp2d', 'like', '%' .  $search . '%')
            ->orWhere('nama_peminjam', 'like', '%' .  $search . '%')
            ->orWhere('instansi', 'like', '%' .  $search . '%')
            ->orWhere('tujuan', 'like', '%' .  $search . '%');
        });
    }
}
