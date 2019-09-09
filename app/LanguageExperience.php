<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LanguageExperience extends Model
{

    protected $fillable = [
        'employee_id', 'language_id', 'experience_period_id',
    ];

    public function employee()
    {
        return $this->hasOne('App\Employee', 'id', 'employee_id');
    }

    public function language()
    {
        return $this->hasOne('App\Language', 'id', 'language_id');
    }

    public function experiencePeriod()
    {
        return $this->hasOne('App\ExperiencePeriod', 'id', 'experience_period_id');
    }
}
