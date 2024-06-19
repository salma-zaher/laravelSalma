<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregnant extends Model
{
    use HasFactory;

    protected $table = 'pregnant';

    protected $fillable = [
        'id',
        'first_day_of_last_period',
        'estimated_due_date',
        'date_of_conception',
        'age_by_week',
    ];
}
