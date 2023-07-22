<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'deskripsi',
        'tanggal_awal',
        'tanggal_akhir',
        'file_regulasi',
    ];
}
