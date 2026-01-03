<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BundaPaud extends Model
{
    use HasFactory;

    protected $table = 'bunda_paud';

    protected $fillable = [
        'name',
        'position',
        'area',
        'type', // kecamatan or kelurahan
        'phone',
        'email',
        'photo_url',
        'kecamatan_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByKecamatan($query, $kecamatan)
    {
        return $query->where('area', $kecamatan);
    }
}
