<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    protected $table = 'konsultasi';

    protected $fillable = [
        'nama',
        'instansi',
        'telepon',
        'email',
        'topik',
        'tanggal',
        'waktu',
        'deskripsi',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
