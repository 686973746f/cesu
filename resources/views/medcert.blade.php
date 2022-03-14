@extends('layouts.app')

@section('content')
<style>
    @media print {
        #PrintBtn, #titleBody {
            display: none;
        }

        body {
            background-color: white;
        }
    }
</style>
<div class="container">
    <div class="card">
        <div class="card-header" id="titleBody">
            <div class="d-flex justify-content-between">
                <div>Online Medical Certificate</div>
                <div><button type="button" class="btn btn-primary" id="PrintBtn" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</button></div>
            </div>
        </div>
        <div id="divToPrint">
            <div class="card-body" style="font-family: Arial, Helvetica, sans-serif">
                <div class="table-responsive">
                    <table class="table table-borderless text-center">
                        <tbody>
                            <tr>
                                <td><img src="{{asset('assets/images/gentriheader.png')}}" style="width: 9rem" alt=""></td>
                                <td>
                                    <h4>Republic of the Philippines</h4>
                                    <h4>Province of Cavite</h4>
                                    <h4>City of General Trias</h4>
                                    <h5><small><i>Telephone No.: (046) 509-5289</i></small></h5>
                                </td>
                                <td><img src="{{asset('assets/images/choheader.png')}}" style="width: 9rem" alt=""></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    <h3 class="font-weight-bold my-3">OFFICE OF THE CITY HEALTH OFFICER</h3>
                    <h4 class="font-weight-bold mb-5">HEALTH CERTIFICATION</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr class="font-weight-bold">
                                <td class="text-left"><h4><strong>NAME:</strong></h4></td>
                                <td style="border-bottom: 1px solid black;" class="text-center" colspan="5"><h4><strong>{{$data->records->getName()}}</strong></h4></td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td class="text-left"><h5><strong>AGE:</strong></h5></td>
                                <td style="border-bottom: 1px solid black;" class="text-center"><h5>{{$data->records->getAge()}}</h5></td>
                                <td class="text-right"><h5><strong>GENDER:</strong></h5></td>
                                <td style="border-bottom: 1px solid black;" class="text-center"><h5>{{$data->records->gender}}</h5></td>
                                <td class="text-right"><h5><strong>CIVIL STATUS:</strong></h5></td>
                                <td style="border-bottom: 1px solid black;" class="text-center"><h5>{{$data->records->cs}}</h5></td>
                            </tr>
                            <tr>
                                <td class="text-left"><h5><strong>ADDRESS:</strong></h5></td>
                                <td style="border-bottom: 1px solid black;" class="text-center" colspan="5"><h5>BRGY. {{$data->records->address_brgy}}, {{$data->records->address_city}} {{$data->records->address_province}}</h5></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <h5>We appreciate your full cooperation and understanding.</h5>
                <h5>Given this {{$cardinal}} day of {{date('F')}} in the year of {{date('Y')}}</h5>    
                <div class="row mt-3 mb-3" style="font-size: 20px;">
                    <div class="col-md-5">
                        <p>Mandatory Quarantine Period</p>
                        <p><small><i>**if undergo a 14 day quarantine</i></small></p>
                    </div>
                    <div class="col-md-3 text-center">
                        <p><u>{{date('m/d/Y', strtotime($req->qDateStart))}}</u></p>
                        <p><small><i>Date Starts</i></small></p>
                    </div>
                    <div class="col-md-1 text-center">
                        <p>to</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <p><u>{{date('m/d/Y', strtotime($req->qDateEnd))}}</u></p>
                        <p><small><i>Date Ends</i></small></p>
                    </div>
                </div>
                <h5><strong>SYMPTOMS</strong></h5>
                <div class="card card-body mb-3">
                    <p style="font-size: 20px;" class="text-center my-5">{{(!is_null($data->SAS)) ? mb_strtoupper($data->SAS) : 'NONE'}}</p>
                </div>
                <div class="row mb-3 text-center">
                    <div class="col-md-3">
                        <h5><span class="mr-2">{{($pui) ? '⬛' : '⬜'}}</span>PUI</h5>
                    </div>
                    <div class="col-md-3">
                        <h5><span class="mr-2">{{(!$pui) ? '⬛' : '⬜'}}</span>NON-PUI</h5>
                    </div>
                    <div class="col-md-3">
                        <h5><span class="mr-2">{{($pum) ? '⬛' : '⬜'}}</span>PUM</h5>
                    </div>
                    <div class="col-md-3">
                        <h5><span class="mr-2">{{(!$pum) ? '⬛' : '⬜'}}</span>NON-PUM</h5>
                    </div>
                </div>
                <h5 style="font-size: 20px;">We appreciate your full cooperation and understanding.</h5>
                <h5 class="mb-5" style="font-size: 22px;">Given this <u><strong>{{$cardinal}}</strong></u> day of <u><strong>{{{date('F')}}}</strong></u> in the year of {{date('Y')}}.</h5>
                <div>
                    <h5 class="mb-5"><strong>PURPOSE:</strong></h5>
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <h5><span class="mr-2">{{($req->purpose == 'Fit to Travel') ? '⬛' : '⬜'}}</span>FIT TO TRAVEL</h5>
                        </div>
                        <div class="col-md-6 text-center">
                            <h5><span class="mr-2">{{($req->purpose == 'Fit to Work') ? '⬛' : '⬜'}}</span>FIT TO WORK</h5>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h5>Certified by:</h5>
                        <div class="text-center">
                            <img src="{{asset('assets/images/signatureonly_docyves.png')}}" style="width: 10rem;">
                            <h5><strong>YVES M. TALOSIG, MD</strong></h5>
                            <h5>Medical Officer III</h5>
                            <h5>Reg. # 0112243</h5>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Approved by:</h5>
                        <div class="text-center">
                            <img src="{{asset('assets/images/signatureonly_docathan.png')}}" style="width: 10rem;">
                            <h5><strong>JONATHAN P. LUSECO, MD</strong></h5>
                            <h5>City Health Officer II</h5>
                            <h5>Reg. # 102377</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection