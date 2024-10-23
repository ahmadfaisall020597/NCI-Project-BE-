<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pelatihan extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $table = 'pelatihan';

    protected $fillable = [
        'title',
        'image_kemendikbud_ristek',
        'image_logo_nci',
        "image_logo_mitra",
        'deskripsi',
        'persyaratan',
        'image_spanduk_pelatihan',
        'duration',
        'location',
        'biaya',
        'url_daftar',
        'output',
        'date',
    ];
}
