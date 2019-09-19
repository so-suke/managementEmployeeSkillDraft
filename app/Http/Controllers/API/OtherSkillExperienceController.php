<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OtherSkillExperience;

class OtherSkillExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        OtherSkillExperience::create($request->all());

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
        $otherSkillExperiences = LanguageExperience::where('employee_id', $id)
            ->orderBy('other_skill_id', 'asc')
            ->with('otherSkill')
            ->with('experiencePeriod')
            ->get();

        return response()->json([
            'otherSkillExperiences' => $otherSkillExperiences
        ]);
    }

    public function exists($employeeId, $otherSkillExperienceId)
    {
        $exists = OtherSkillExperience::where('employee_id', $employeeId)->where('id', $otherSkillExperienceId)->exists();
        return response()->json([
            'exists' => $exists,
            'message' => $exists,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OtherSkillExperience $otherSkillExperience)
    {
        $otherSkillExperience->experience_period_id = $request->experience_period_id;
        $otherSkillExperience->save();

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
    public function destroy(OtherSkillExperience $otherSkillExperience)
    {
        $otherSkillExperience->delete();
        return response()->json([
            'message' => 'success destroy.'
        ]);
    }
}
