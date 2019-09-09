<?php

use Illuminate\Database\Seeder;
use App\ExperiencePeriod;

class ExperiencePeriodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $experiencePeriods = [
            ['name' => '半年未満'],
            ['name' => '半年から1年'],
            ['name' => '1年から2年'],
            ['name' => '2年から3年'],
            ['name' => '3年以上'],
        ];

        foreach ($experiencePeriods as $key => $experiencePeriod) {
            ExperiencePeriod::create([
                'name' => $experiencePeriod['name'],
            ]);
        }
    }
}
