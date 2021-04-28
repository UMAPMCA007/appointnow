<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessOwner extends AuthUser
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    protected $guard = "business";

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
        'language'
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
     * Relation between the business owner and its business
     */
    public function business()
    {
        return $this->hasOne('App\Models\Business', 'business_owner_id', 'id');
    }
}
