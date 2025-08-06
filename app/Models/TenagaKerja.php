<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenagaKerja extends Model
{
    use HasFactory;

    protected $table = 'tenaga_kerja';
    
    protected $fillable = [
        'tahun',
        'kecamatan',
        'jumlah_angkatan_kerja',
        'jumlah_bekerja',
        'jumlah_pengangguran',
        'tingkat_pengangguran',
        'tingkat_partisipasi_kerja',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'jumlah_angkatan_kerja' => 'integer',
        'jumlah_bekerja' => 'integer',
        'jumlah_pengangguran' => 'integer',
        'tingkat_pengangguran' => 'float',
        'tingkat_partisipasi_kerja' => 'float',
    ];
} 