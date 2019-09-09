<?php

Route::get('/', function () {
    return view('employees');
});

Route::resource('employees', 'EmployeeController');
Route::get('/getLanguageExperiences', 'EmployeeController@getLanguageExperiences');
