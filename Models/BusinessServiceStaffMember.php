<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessServiceStaffMember extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'business_service_id',
        'business_staff_member_id'
    ];

    /**
     * Attributes that will be logged
     */
    protected static $logAttributes = ['*'];
}
