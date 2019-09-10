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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
