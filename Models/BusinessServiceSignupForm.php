<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessServiceSignupForm extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'business_id',
        'name',
        'status'
    ];

    /**
     * Attributes that will be logged
     */
    protected static $logAttributes = ['*'];

    /**
     * Relation between a form and its fields
     */
    public function fields()
    {
        return $this->hasMany('App\Models\BusinessServiceSignupFormField', 'signup_form_id', 'id');
    }
}
