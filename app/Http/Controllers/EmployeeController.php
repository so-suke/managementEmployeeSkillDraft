<?php

namespace App\Http\Controllers;

use App\Employee;
use App\ExperiencePeriod;
use App\Framework;
use App\Language;
use App\LanguageExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class EmployeeController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        return back();
    }

    public function getLanguageExperiences()
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
        )->where('id', 2)->first();

        $languageExperiences = LanguageExperience::where('employee_id', 2)
            ->with('language')
            ->with('experiencePeriod')
            ->get();

        return response()->json([
            'employee' => $employee,
            'languageExperiences' => $languageExperiences
        ]);
    }

    /**
     * 社員詳細ページへ遷移
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

        $languages = Language::orderBy('name', 'asc')->get();
        $frameworks = Framework::orderBy('name', 'asc')->get();
        return view('employee', compact('employee', 'languages', 'frameworks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
