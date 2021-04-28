<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessServiceSignupFormField extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'signup_form_id',
        'description',
        'type',
        'is_required',
        'options'
    ];

    protected $hidden = [
        'options'
    ];

    protected $appends = [
        'options_fields'
    ];

    /**
     * Attributes that will be logged
     */
    protected static $logAttributes = ['*'];

    /**
     * Relation between a form and its fields
     */
    public function signupForm()
    {
        return $this->belongsTo('App\Models\BusinessServiceSignupForm', 'signup_form_id', 'id');
    }

    /**
     * Convert options field into JSON
     */
    public function getOptionsFieldsAttribute()
    {
        return !(empty($this->options)) ? json_decode($this->options, true) : null;
    }
}
