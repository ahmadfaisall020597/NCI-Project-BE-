<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class slideShow extends Model
{
    use HasFactory;
    /**
     * The table associated with the model
     * 
     * @var string
     */

     protected $table = 'slideShow';

     protected $fillable = [
        'title',
        'deskripsi',
        'image_url',
        'date'
     ];
}
