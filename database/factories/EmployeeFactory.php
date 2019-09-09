<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Employee;
use App\Model;
use Faker\Generator as Faker;

$name_lasts = [
    ['chinese' => '佐藤', 'kana' => 'サトウ'],
    ['chinese' => '鈴木', 'kana' => 'スズキ'],
    ['chinese' => '高橋', 'kana' => 'タカハシ'],
    ['chinese' => '田中', 'kana' => 'タナカ'],
    ['chinese' => '渡辺', 'kana' => 'ワタナベ'],
    ['chinese' => '伊藤', 'kana' => 'イトウ'],
    ['chinese' => '山本', 'kana' => 'ヤマモト'],
    ['chinese' => '中村', 'kana' => 'ナカムラ'],
];

$name_firsts = [
    ['chinese' => '実', 'kana' => 'ミノル', 'gender' => 'male'],
    ['chinese' => '真一', 'kana' => 'シンイチ', 'gender' => 'male'],
    ['chinese' => '浩二', 'kana' => 'コウジ', 'gender' => 'male'],
    ['chinese' => '学', 'kana' => 'マナブ', 'gender' => 'male'],
    ['chinese' => '浩之', 'kana' => 'ヒロユキ', 'gender' => 'male'],
    ['chinese' => '久美子', 'kana' => 'クミコ', 'gender' => 'female'],
    ['chinese' => '啓子', 'kana' => 'ケイコ', 'gender' => 'female'],
    ['chinese' => '恵美子', 'kana' => 'エミコ', 'gender' => 'female'],
    ['chinese' => '絵美', 'kana' => 'エミ', 'gender' => 'female'],
    ['chinese' => '悦子', 'kana' => 'エツコ', 'gender' => 'female'],
    ['chinese' => '節子', 'kana' => 'セツコ', 'gender' => 'female'],
    ['chinese' => '美加子', 'kana' => 'ミカコ', 'gender' => 'female'],
];



$factory->define(Employee::class, function (Faker $faker) use ($name_lasts, $name_firsts) {

    $name_last = $name_lasts[array_rand($name_lasts)];
    $name_first = $name_firsts[array_rand($name_firsts)];

    return [
        'is_admin' => false,
        'name_last' => $name_last['chinese'],
        'name_first' => $name_first['chinese'],
        'name_last_kana' => $name_last['kana'],
        'name_first_kana' => $name_first['kana'],
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => $faker->password(6, 10),
        'gender' => $name_first['gender'],
        'hired_at' => $faker->dateTimeBetween('-3 years', 'now')->format("Y-m-d"),
        'birth_at' => $faker->dateTimeBetween('-35 years', '-24 years')->format("Y-m-d"),
        'remarks' => '',
        'remember_token' => Str::random(10),
    ];
});
