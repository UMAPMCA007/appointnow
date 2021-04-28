<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'timezone',
        'name',
        'is_default'
    ];


    /**
     * Relation between a schedule and its days
     */
    public function days()
    {
        return $this->hasmany('App\Models\StaffScheduleDay','staff_schedule_id', 'id');
    }


}
