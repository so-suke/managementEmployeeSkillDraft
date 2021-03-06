<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LanguageExperience;
use Illuminate\Support\Facades\Log;

class LanguageExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        LanguageExperience::create($request->all());

        return response()->json([
            'message' => 'success store.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $languageExperiences = LanguageExperience::where('employee_id', $id)
            ->orderBy('language_id', 'asc')
            ->with('language')
            ->with('experiencePeriod')
            ->get();

        return response()->json([
            'languageExperiences' => $languageExperiences
        ]);
    }

    public function exists($employeeId, $languageExperienceId)
    {
        $exists = LanguageExperience::where('employee_id', $employeeId)->where('id', $languageExperienceId)->exists();
        return response()->json([
            'exists' => $exists,
            'message' => $exists,
        ]);
    }

    public function existsLanguage($employeeId, $languageId)
    {
        $exists = LanguageExperience::where('employee_id', $employeeId)
            ->where('language_id', $languageId)
            ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LanguageExperience $languageExperience)
    {
        $languageExperience->experience_period_id = $request->experience_period_id;
        $languageExperience->save();

        return response()->json([
            'message' => 'success update.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LanguageExperience $languageExperience)
    {
        $languageExperience->delete();
        return response()->json([
            'message' => 'success destroy.'
        ]);
    }
}
