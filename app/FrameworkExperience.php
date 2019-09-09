<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FrameworkExperience extends Model
{
    protected $fillable = [
        'employee_id', 'framework_id', 'experience_period_id',
    ];

    public function employee()
    {
        return $this->hasOne('App\Employee', 'id', 'employee_id');
    }

    public function framework()
    {
        return $this->hasOne('App\Framework', 'id', 'framework_id');
    }

    public function experiencePeriod()
    {
        return $this->hasOne('App\ExperiencePeriod', 'id', 'experience_period_id');
    }

}
