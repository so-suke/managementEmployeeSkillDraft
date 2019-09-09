<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EmployeesTableSeeder::class,
            LanguagesTableSeeder::class,
            ExperiencePeriodsTableSeeder::class,
            LanguageExperiencesTableSeeder::class,
            FrameworksTableSeeder::class,
            FrameworkExperiencesTableSeeder::class,
        ]);
    }
}
