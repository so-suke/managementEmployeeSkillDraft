<?php

namespace App\Http\Controllers\API;

use App\Employee;
use App\FrameworkExperience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LanguageExperience;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Employee::get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::select(
            DB::raw(
                'name_last,
                name_first,
                name_last_kana,
                name_first_kana,
                DATE_FORMAT(birth_at, "%Y年%m月%d日") as birth_at_formatted,
                YEAR(CURDATE()) - YEAR(birth_at) AS age'
            )
        )->where('id', $id)->first();

        $languageExperiences = LanguageExperience::where('employee_id', $id)
            ->orderBy('language_id', 'asc')
            ->with('language')
            ->with('experiencePeriod')
            ->get();

        $frameworkExperiences = FrameworkExperience::where('employee_id', $id)
            ->with('framework')
            ->with('experiencePeriod')
            ->get();

        $frameworkExperiences = $frameworkExperiences->sortBy('framework.name')->values();
        return response()->json([
            'employee' => $employee,
            'languageExperiences' => $languageExperiences,
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
    public function destroy($id)
    {
        //
    }
}
