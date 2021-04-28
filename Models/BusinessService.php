<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessService extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'business_id',
        'description',
        'status',
        'code',
        'price',
        'currency',
        'image',
        'signup_form_id',
        'round_robin',
        'service_category_id'
    ];

    protected $appends = [
        'image_url'
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

    /*
    * Relation between a service and its staff members
    */
    public function staffMembers()
    {
        return $this->belongsToMany('App\Models\BusinessStaffMember', 'business_service_staff_members');
    }

    /**
     * Get Image url
     */
    public function getImageUrlAttribute()
    {
        return !empty($this->image) ? Storage::url($this->image) : "";
    }
}
