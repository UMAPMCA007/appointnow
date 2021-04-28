<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessScheduleDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_schedule_id',
        'day',
        'status',
        'hours'
    ];
}
