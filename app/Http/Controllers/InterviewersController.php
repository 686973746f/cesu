<?php

namespace App\Http\Controllers;

use App\Models\Brgy;
use App\Models\Interviewers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        if(request()->input('q')) {
            $search = request()->input('q');

            $list = Interviewers::where(DB::raw('CONCAT(lname," ",fname," ", mname)'), 'LIKE', "%".str_replace(',','',$search)."%")
            ->orWhere(DB::raw('CONCAT(lname," ",fname)'), 'LIKE', "%".str_replace(',','',$search)."%")
            ->orWhere('id', request()->input('q'))
            ->paginate(10);
        }
        else {
            $list = Interviewers::orderBy('lname', 'asc')
            ->paginate(10);
        }

        return view('interviewers_home', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list = Brgy::where('displayInList', 1)
        ->orderBy('brgyName', 'asc')
        ->get();
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
        $record = Interviewers::findOrFail($id);
        $list = Brgy::orderBy('brgyName', 'asc')->get();

        return view('interviewers_edit', ['record' => $record, 'list' => $list]);
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
        $request->validated();

        $record = Interviewers::where('id', $id)->update([
            'lname' => strtoupper($request->lname),
            'fname' => strtoupper($request->fname),
            'mname' => ($request->filled('mname')) ? strtoupper($request->mname) : null,
            'desc' => ($request->filled('desc')) ? strtoupper($request->desc) : null,
            'brgy_id' => $request->brgy_id,
        ]);

        return redirect()->route('interviewers.index')->with('status', 'Interviewer record has been updated successfully.')->with('statustype', 'success');
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

    public function options(Request $request, $id) {
        $item = Interviewers::findOrFail($id);

        if($request->submit == 'toggleStatus') {
            if($item->enabled == 1) {
                $item->enabled = 0;
                $statusmsg = 'Interviewer #'.$item->id.' ('.$item->getName().') has been DISABLED successfully.';
            }
            else {
                $item->enabled = 1;
                $statusmsg = 'Interviewer #'.$item->id.' ('.$item->getName().') has been ENABLED successfully.';
            }

            $item->save();
        }

        return redirect()->action([InterviewersController::class, 'index'])->with('status', $statusmsg)->with('statustype', 'success');
    }
}
