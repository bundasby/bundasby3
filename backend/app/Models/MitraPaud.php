<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MitraPaud extends Model
{
    protected $table = 'mitra_paud';

    protected $fillable = [
        'nama_responden',
        'jabatan_responden',
        'telepon_responden',
        'nama_organisasi',
        'kategori_organisasi',
        'alamat',
        'telepon',
        'email',
        'website',
        'nama_pimpinan',
        'tujuan_organisasi',
        'logo_url',
        'jenis_kerjasama',
        'catatan_kerjasama',
        'dokumen_komitmen_url',
        'link_pendukung',
        'status',
    ];

    protected $casts = [
        'jenis_kerjasama' => 'array',
    ];
}
