<?php

use App\Other;
use Illuminate\Database\Seeder;

class OthersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $others = [
            ['name' => 'git'],
            ['name' => 'circleci'],
            ['name' => 'android'],
            ['name' => 'aws-cli'],
            ['name' => 'pipenv'],
            ['name' => 'dropbox-paper'],
            ['name' => 'bash'],
            ['name' => 'amazon-glacier'],
            ['name' => 'webpack'],
            ['name' => 'aws'],
            ['name' => 'bigquery'],
            ['name' => 'azure'],
            ['name' => 'docker'],
        ];

        foreach ($others as $key => $other) {
            Other::create([
                'name' => $other['name'],
            ]);
        }
    }
}
