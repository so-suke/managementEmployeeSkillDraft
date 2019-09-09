<?php

use App\Framework;
use Illuminate\Database\Seeder;

class FrameworksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['name' => 'Laravel'],
            ['name' => 'CakePHP'],
            ['name' => 'FuelPHP'],
            ['name' => 'ZendFramework'],
            ['name' => 'RubyonRails'],
            ['name' => 'SpringBoot'],
            ['name' => 'Struts'],
            ['name' => 'Seasar2'],
            ['name' => 'Angluar'],
            ['name' => 'React'],
            ['name' => 'Vue.js'],
        ];

        foreach ($languages as $key => $language) {
            Framework::create([
                'name' => $language['name'],
            ]);
        }
    }
}
