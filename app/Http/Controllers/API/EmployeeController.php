<?php

namespace App\Http\Controllers\API;

use App\Employee;
use App\FrameworkExperience;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LanguageExperience;
use App\OtherExperience;
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
        // return Employee::select(DB::raw('CONCAT(name_last, " ", name_first) as full_name, gender'))
        //     ->paginate(3);
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
            ->get()
            ->sortBy('language.name')
            ->values();

        $frameworkExperiences = FrameworkExperience::where('employee_id', $id)
            ->with('framework')
            ->with('experiencePeriod')
            ->get()
            ->sortBy('framework.name')
            ->values();

        $otherExperiences = OtherExperience::where('employee_id', $id)
            ->with('other')
            ->with('experiencePeriod')
            ->get()
            ->sortBy('other.name')
            ->values();
        return response()->json(compact('employee', 'languageExperiences', 'frameworkExperiences', 'otherExperiences'));
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
