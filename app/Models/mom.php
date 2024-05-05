<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mom extends Model
{
    use HasFactory;

    protected $table = 'moms';

    protected $fillable = [
        'id',
        'baby_name',
        'date_of_birth',
    ];


}
