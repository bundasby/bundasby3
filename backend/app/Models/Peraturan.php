<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    protected $table = 'peraturan';

    protected $fillable = [
        'title',
        'description',
        'file_url',
        'file_type',
        'nomor_peraturan',
        'tahun',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
