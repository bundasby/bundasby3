<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Majalah extends Model
{
    protected $table = 'majalah';

    protected $fillable = [
        'title',
        'description',
        'cover_url',
        'file_url',
        'edition',
        'publish_date',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'publish_date' => 'date',
    ];
}
