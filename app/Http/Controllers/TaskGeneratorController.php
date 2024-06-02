<?php

namespace App\Http\Controllers;

use App\Models\TaskGenerator;
use Illuminate\Http\Request;

class TaskGeneratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $task_list = TaskGenerator::paginate(10);

        return view('taskgenerator.index', [
            'task_list' => $task_list,
        ]);
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
    public function store(Request $r)
    {
        TaskGenerator::create([
            'name' => mb_strtoupper($r->name),
            'description' => $r->description,
            'generate_every' => $r->generate_every,
            'weekly_whatday' => $r->weekly_whatday,
            'monthly_whatday' => $r->monthly_whatday,
            'has_duration' => $r->has_duration,
            'duration_type' => $r->duration_type,
            'duration_daily_whattime' => $r->duration_daily_whattime,
            'duration_weekly_howmanydays' => $r->duration_weekly_howmanydays,
            'duration_monthly_howmanymonth' => $r->duration_monthly_howmanymonth,
            'duration_yearly_howmanyyear' => $r->duration_yearly_howmanyyear,
            'encodedcount_enable' => $r->encodedcount_enable,
            'has_tosendimageproof' => $r->has_tosendimageproof,
        ]);

        return redirect()->route('taskgenerator.index')
        ->with('msg', 'Task Generator was successfully added.')
        ->with('msgtype', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaskGenerator  $taskGenerator
     * @return \Illuminate\Http\Response
     */
    public function show(TaskGenerator $taskGenerator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskGenerator  $taskGenerator
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskGenerator $taskGenerator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaskGenerator  $taskGenerator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskGenerator $taskGenerator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaskGenerator  $taskGenerator
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskGenerator $taskGenerator)
    {
        //
    }
}
