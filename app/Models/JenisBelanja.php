<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dokumen;
use app\Models\DetailDokumen;


class JenisBelanja extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail_dokumen_id',
        'name',
        'status',
        'retensi',


    ];

    public function jenisFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' .  $search . '%');
        });
    }
     
    public function detaildokumen()
    {
        return $this->belongsTo(DetailDokumen::class,'detail_dokumen_id');
    }
    
}

