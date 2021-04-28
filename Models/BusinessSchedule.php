<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessSchedule extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_id',
        'timezone',
        'name',
        'is_default'
    ];

    /**
     * Attributes that will be logged
     */
    protected static $logAttributes = ['*'];

    /*
     * Relation between a business and its services
     */
    public function business()
    {
        return $this->belongsTo('App\Models\Business', 'business_id', 'id');
    }

    /**
     * Relation between a schedule and its days
     */
    public function days()
    {
        return $this->hasMany('App\Models\BusinessScheduleDay', 'business_schedule_id', 'id');
    }
}
