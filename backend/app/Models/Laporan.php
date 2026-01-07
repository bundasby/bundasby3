<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'title',
        'description',
        'file_url',
        'file_type',
        'periode',
        'tahun',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
