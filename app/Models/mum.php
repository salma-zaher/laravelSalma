<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mum extends Model
{
    use HasFactory;

    protected $table = 'mums';

    protected $fillable = [
        'id',
        'baby_name',
        'gender',
        'date_of_birth',
    ];


}
