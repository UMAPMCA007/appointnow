<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessStaffMember extends AuthUser
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'code',
        'timezone',
        'business_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Attributes that will be logged
     */
    protected static $logAttributes = ['*'];

    /**
     * Relation between the business and its staff members
     */
    public function business()
    {
        return $this->belongsTo('App\Models\Business', 'business_id', 'id');
    }

    /*
    * Relation between a service and its staff members
    */
    public function services()
    {
        return $this->belongsToMany('App\Models\BusinessStaffMember', 'business_service_staff_members');
    }

    public function schedules(){
        return $this->hasmany('App\Models\StaffSchedule','staff_id','id');
    }
}
