<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'cors'], function () {
    Route::apiResource('employees', 'API\EmployeeController');
});

Route::apiResource('languageExperiences', 'API\LanguageExperienceController');
Route::apiResource('frameworkExperiences', 'API\FrameworkExperienceController');
Route::apiResource('otherExperiences', 'API\OtherExperienceController');

Route::get('languages/existsLanguage/{employeeId}/{languageId}', 'API\LanguageExperienceController@existsLanguage');
Route::get('frameworks/existsFramework/{employeeId}/{frameworkId}', 'API\FrameworkExperienceController@existsFramework');
Route::get('others/existsOther/{employeeId}/{otherId}', 'API\OtherExperienceController@existsOther');

Route::get('languageExperiences/exists/{employeeId}/{languageExperienceId}', 'API\LanguageExperienceController@exists');
