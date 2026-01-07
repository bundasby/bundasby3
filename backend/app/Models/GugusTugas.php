<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GugusTugas extends Model
{
    protected $table = 'gugus_tugas';

    protected $fillable = [
        'nama',
        'jabatan',
        'instansi',
        'foto_url',
        'kategori',
        'wilayah',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
