<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyValidationRequest;
use App\Models\Companies;
use Illuminate\Http\Request;
use IlluminateAgnostic\Collection\Support\Str;

class CompaniesController extends Controller
{
    public function makeCode(Request $request) {
        $request->validate([
            'company_id' => 'required',
        ]);

        $code = strtoupper(Str::random(6));

        $request->user()->referralCode()->create([
            'company_id' => $request->company_id,
            'refCode' => $code,
        ]);
        
        return redirect()->action([CompaniesController::class, 'index'])->with('process', 'createCode')->with('statustype', 'success')->with('bCode', $code);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Companies::orderBy('companyName', 'asc')->get();

        return view('companies_panel_home', ['list' => $list]);
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
    public function store(CompanyValidationRequest $request)
    {
        $request->validated();

        $request->user()->company()->create([
            'companyName' => mb_strtoupper($request->companyName),
            'contactNumber' => $request->contactNumber,
            'email' => $request->email,
            'loc_lotbldg' => $request->loc_lotbldg,
            'loc_street' => $request->loc_street,
            'loc_brgy' => $request->loc_brgy,
            'loc_city' => $request->loc_city,
            'loc_cityjson' => $request->loc_cityjson,
            'loc_province' => $request->loc_province,
            'loc_provincejson' => $request->loc_provincejson,
            'loc_region' => $request->loc_region,
            'loc_regionjson' => $request->loc_regionjson,
        ]);

        return redirect()->action([CompaniesController::class, 'index'])->with('status', 'Company information has been created successfully.')->with('statustype', 'success');
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
    public function update(CompanyValidationRequest $request, $id)
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
