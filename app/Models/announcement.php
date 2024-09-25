<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class announcement extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */

     protected $table = 'announcement';

     protected $fillable = [
        'deskripsi',
        'date',
     ];
}
