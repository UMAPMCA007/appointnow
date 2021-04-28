<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessServiceCategory extends Model
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
        'status',
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
}
