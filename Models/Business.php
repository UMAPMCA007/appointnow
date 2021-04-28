<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;

class Business extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'business_owner_id',
        'logo',
        'timezone',
        'status',
        'code',
        'language',
        "description"
    ];

    protected $appends = [
        'logo_url'
    ];

    /**
     * Attributes that will be logged
     */
    protected static $logAttributes = ['*'];

    /**
     * Relation between the business owner and its business
     */
    public function businessOwner()
    {
        return $this->belongsTo('App\Models\BusinessOwner', 'business_owner_id','id');
    }

    /**
     * Relation between the business and its locations
     */
    public function locations()
    {
        return $this->hasMany('App\Models\BusinessLocation', 'business_id', 'id');
    }

    /**
     * Relation between the business and its staff members
     */
    public function staffMembers()
    {
        return $this->hasMany('App\Models\BusinessStaffMember', 'business_id', 'id');
    }

    /**
     * Relation between a business and its services
     */
    public function services()
    {
        return $this->hasMany("App\Models\BusinessService", 'business_id', 'id');
    }

    /**
     * Relation between a business and its signup forms
     */
    public function serviceSignupForms()
    {
        return $this->hasMany('App\Models\BusinessServiceSignupForm', 'business_id', 'id');
    }

    /**
     * Relation between a business and its service categories
     */
    public function serviceCategories()
    {
        return $this->hasMany('App\Models\BusinessServiceCategory', 'business_id', 'id');
    }

    /**
     * Get Image url
     */
    public function getLogoUrlAttribute()
    {
        return !empty($this->logo) ? Storage::url($this->logo) : "";
    }

    /**
     * Relation between a business and its schedule
     */
    public function schedules()
    {
        return $this->hasMany("App\Models\BusinessSchedule", "business_id", 'id');
    }
}
