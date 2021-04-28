<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessLocation extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'business_id',
        'address',
        'latitude',
        'longitude',
        'phone',
        'code',
        'status'
    ];

    /**
     * Attributes that will be logged
     */
    protected static $logAttributes = ['*'];

    /**
     * Relation between the business and its locations
     */
    public function business()
    {
        return $this->belongsTo("App\Models\Business", 'business_id', 'id');
    }
}
