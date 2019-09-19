<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtherExperience extends Model
{
    protected $fillable = [
        'employee_id', 'other_id', 'experience_period_id',
    ];

    public function employee()
    {
        return $this->hasOne('App\Employee', 'id', 'employee_id');
    }

    public function other()
    {
        return $this->hasOne('App\Other', 'id', 'other_id');
    }

    public function experiencePeriod()
    {
        return $this->hasOne('App\ExperiencePeriod', 'id', 'experience_period_id');
    }
}
