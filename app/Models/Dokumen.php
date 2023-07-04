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
        'status',
    ];

    public function detailDokumen(){
    	return $this->hasMany(DetailDokumen::class);
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('no_sp2d', 'like', '%' .  $search . '%');
        });
    }
}
