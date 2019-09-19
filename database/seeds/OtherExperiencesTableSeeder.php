<?php

use App\Employee;
use App\Other;
use App\OtherExperience;
use Illuminate\Database\Seeder;

class OtherExperiencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $otherExperiences = [];
        $employees = Employee::orderBy('id')->get('id');
        $others = Other::orderBy('id')->get('id');
        foreach ($employees as $key => $employee) {
            $othersSliced = $others->shuffle()->slice(0, rand(1, 3));
            foreach ($othersSliced as $key => $other) {
                $otherExperiences[] = ['employee_id' => $employee->id, 'other_id' => $other->id, 'experience_period_id' => rand(1, 5)];
            }
        }

        foreach ($otherExperiences as $key => $otherExperience) {
            OtherExperience::create([
                'employee_id' => $otherExperience['employee_id'],
                'other_id' => $otherExperience['other_id'],
                'experience_period_id' => $otherExperience['experience_period_id']
            ]);
        }
    }
}
