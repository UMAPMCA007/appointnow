<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessPlan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'status',
        'code',
        'can_add_staff_members',
        'staff_member_limit',
        'location_limit',
        'categories_limit',
        'services_limit',
        'signup_form_limit'
    ];

    /**
     * Attributes that will be logged
     */
    protected static $logAttributes = ['*'];
}
