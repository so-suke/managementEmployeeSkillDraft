<?php

use Illuminate\Database\Seeder;
use App\Language;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['name' => 'java'],
            ['name' => 'javascript'],
            ['name' => 'php'],
            ['name' => 'python'],
            ['name' => 'ruby'],
            ['name' => 'kotlin'],
        ];

        foreach ($languages as $key => $language) {
            Language::create([
                'name' => $language['name'],
            ]);
        }
    }
}
