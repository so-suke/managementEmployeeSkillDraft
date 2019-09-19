<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OtherExperience;

class OtherExperienceController extends Controller
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
        OtherExperience::create($request->all());

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
        $otherExperiences = OtherExperience::where('employee_id', $id)
            ->with('other')
            ->with('experiencePeriod')
            ->get();

        return response()->json([
            'otherExperiences' => $otherExperiences
        ]);
    }

    public function existsOther($employeeId, $otherId)
    {
        $exists = OtherExperience::where('employee_id', $employeeId)
            ->where('other_id', $otherId)
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
    public function update(Request $request, OtherExperience $otherExperience)
    {
        $otherExperience->experience_period_id = $request->experience_period_id;
        $otherExperience->save();

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
    public function destroy(OtherExperience $otherExperience)
    {
        $otherExperience->delete();
        return response()->json([
            'message' => 'success destroy.'
        ]);
    }
}
