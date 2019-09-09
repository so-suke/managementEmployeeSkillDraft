<?php

use App\Employee;
use App\Framework;
use App\FrameworkExperience;
use Illuminate\Database\Seeder;

class FrameworkExperiencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $frameworkExperiences = [];
        $employees = Employee::orderBy('id')->get('id');
        $frameworks = Framework::orderBy('id')->get('id');
        foreach ($employees as $key => $employee) {
            $frameworksSliced = $frameworks->shuffle()->slice(0, rand(1, 3));
            foreach ($frameworksSliced as $key => $framework) {
                $frameworkExperiences[] = ['employee_id' => $employee->id, 'framework_id' => $framework->id, 'experience_period_id' => rand(1, 5)];
            }
        }

        foreach ($frameworkExperiences as $key => $frameworkExperience) {
            FrameworkExperience::create([
                'employee_id' => $frameworkExperience['employee_id'],
                'framework_id' => $frameworkExperience['framework_id'],
                'experience_period_id' => $frameworkExperience['experience_period_id']
            ]);
        }
    }
}
