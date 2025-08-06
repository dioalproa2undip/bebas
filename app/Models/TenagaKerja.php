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
        'pengangguran_terbuka',
        'tpak',
        'tkk',
        'tpt',
        'laki_laki',
        'perempuan',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'tahun' => 'integer',
        'pengangguran_terbuka' => 'integer',
        'tpak' => 'integer',
        'tkk' => 'integer',
        'tpt' => 'integer',
        'laki_laki' => 'integer',
        'perempuan' => 'integer',
    ];
} 