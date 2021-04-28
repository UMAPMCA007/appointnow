<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffScheduleDayHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_schedule_day_id',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'array',
        'end_time' => 'array'
    ];



}
