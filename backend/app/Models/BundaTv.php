<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundaTv extends Model
{
    protected $table = 'bunda_tv';

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'thumbnail_url',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
