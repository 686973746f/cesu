<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use App\Http\Requests\InterviewerValidationRequest;

class InterviewersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $list = Interviewers::orderBy('lname', 'asc')->get();

        return view('interviewers_home', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list = Brgy::orderBy('brgyName', 'asc')->get();
        return view('interviewers_create', ['list' => $list]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InterviewerValidationRequest $request)
    {
        $request->validated();

        $request->user()->interviewer()->create([
            'lname' => strtoupper($request->lname),
            'fname' => strtoupper($request->fname),
            'mname' => ($request->filled('mname')) ? strtoupper($request->mname) : null,
            'desc' => ($request->filled('desc')) ? strtoupper($request->desc) : null,
            'brgy_id' => $request->brgy_id,
        ]);

        return redirect()->action([InterviewersController::class, 'index'])->with('status', 'Successfully added a new Interviewer.')->with('statustype', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(InterviewerValidationRequest $request, $id)
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
