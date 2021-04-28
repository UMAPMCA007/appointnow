<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffScheduleDay extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_schedule_id',
        'day',
        'status',
        'hours'
    ];
    public function hour()
    {
        return $this->hasmany(StaffScheduleDayHour::class,'staff_schedule_day_id','id');
    }

}
