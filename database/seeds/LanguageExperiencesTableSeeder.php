<?php

use Illuminate\Database\Seeder;
use App\Employee;
use App\Language;
use App\LanguageExperience;
use Illuminate\Support\Facades\Log;

class LanguageExperiencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languageExperiences = [];
        $employees = Employee::orderBy('id')->get('id');
        $languages = Language::orderBy('id')->get('id');
        foreach ($employees as $key => $employee) {
            $languagesSliced = $languages->shuffle()->slice(0, rand(1, 3));
            foreach ($languagesSliced as $key => $language) {
                $languageExperiences[] = ['employee_id' => $employee->id, 'language_id' => $language->id, 'experience_period_id' => rand(1, 5)];
            }
        }

        foreach ($languageExperiences as $key => $languageExperience) {
            LanguageExperience::create([
                'employee_id' => $languageExperience['employee_id'],
                'language_id' => $languageExperience['language_id'],
                'experience_period_id' => $languageExperience['experience_period_id']
            ]);
        }
    }
}
