<?php

namespace App\Http\Controllers\API;

use App\FrameworkExperience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrameworkExperienceController extends Controller
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
        FrameworkExperience::create($request->all());

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
        // $frameworkExperiences = FrameworkExperience::where('employee_id', $id)
        //     ->with('framework')
        //     ->orderBy('framework.name', 'asc')
        //     ->with('experiencePeriod')
        //     ->toSql();
        // Log::debug($frameworkExperiences);
        $frameworkExperiences = FrameworkExperience::where('employee_id', $id)
            ->with('framework')
            ->with('experiencePeriod')
            ->get();

        return response()->json([
            'frameworkExperiences' => $frameworkExperiences
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
    public function destroy(FrameworkExperience $frameworkExperience)
    {
        $frameworkExperience->delete();
        return response()->json([
            'message' => 'success destroy.'
        ]);
    }
}
