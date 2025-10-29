@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <b>Monthly Accomplishment Report for <span class="text-success">{{mb_strtoupper(Carbon\Carbon::createFromDate(request()->input('year'), request()->input('month'), 1)->format('F Y'))}}</span></b>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changeMonth">Change Month</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if(request()->input('month') && request()->input('year'))
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="text-center">
                                <td colspan="2">
                                    <div><b>Name: {{mb_strtoupper($name)}}</b></div>
                                    <div>MONTHLY ACCOMPLISHMENT: <b class="text-success">{{mb_strtoupper(Carbon\Carbon::createFromDate(request()->input('year'), request()->input('month'), 1)->format('F Y'))}}</b></div>
                                </td>
                            </tr>
                            @if(auth()->user()->isArChecker() || auth()->user()->isArApprover())
                            <tr>
                                <td>COVID-19 (Suspected/Probable/Confirmed/Negative/Recovered)</td>
                                <td class="text-center">{{$covid_count_final}}</td>
                            </tr>
                            <tr>
                                <td>Animal Bite (New Patients)</td>
                                <td class="text-center">{{$abtc_count}}</td>
                            </tr>
                            <tr>
                                <td>Animal Bite (Follow-up)</td>
                                <td class="text-center">{{$abtc_ffup_gtotal}}</td>
                            </tr>
                            <tr>
                                <td>VaxCert Concerns</td>
                                <td class="text-center">{{$vaxcert_count}}</td>
                            </tr>
                            <tr>
                                <td>OPD</td>
                                <td class="text-center">{{$opd_count}}</td>
                            </tr>
                            <tr>
                                <td>Livebirths (LCR)</td>
                                <td class="text-center">{{$lcr_livebirth}}</td>
                            </tr>
                            <tr>
                                <td>Imports from EDCS-IS</td>
                                <td class="text-center">{{$edcs_count}}</td>
                            </tr>
                            <tr>
                                <td>Death Certificates</td>
                                <td class="text-center">{{$death_count}}</td>
                            </tr>
                            <tr>
                                <td>OPD to iClinicSys Tickets</td>
                                <td class="text-center">{{$opdtoics_count}}</td>
                            </tr>
                            <tr>
                                <td>Animal Bite to iClinicSys Tickets</td>
                                <td class="text-center">{{$abtctoics_count}}</td>
                            </tr>
                            <tr>
                                <td>Evacuation Center (Family Heads and Members)</td>
                                <td class="text-center">{{$evac_count}}</td>
                            </tr>
                            @else
                            
                            @if($covid_count_final != 0)
                            <tr>
                                <td class="text-center">Encoded <b>{{$covid_count_final}}</b> COVID-19 Cases (Suspected/Probable/Confirmed/Negative/Recovered)</td>
                            </tr>
                            @endif
                            @if($abtc_count != 0)
                            <tr>
                                <td class="text-center">Encoded <b>{{$abtc_count}}</b> Animal Bite - New Patients</td>
                            </tr>
                            @endif
                            @if($abtc_ffup_gtotal != 0)
                            <tr>
                                <td class="text-center">Encoded <b>{{$abtc_ffup_gtotal}}</b> Animal Bite - Follow-up Patients</td>
                            </tr>
                            @endif
                            @if($vaxcert_count != 0)
                            <tr>
                                <td class="text-center">Encoded and Resolved <b>{{$vaxcert_count}}</b> VaxCert Concerns Tickets</td>
                            </tr>
                            @endif
                            @if($opd_count != 0)
                            <tr>
                                <td class="text-center">Encoded <b>{{$opd_count}}</b> Patients in OPD</td>
                            </tr>
                            @endif
                            @if($lcr_livebirth != 0)
                            <tr>
                                <td class="text-center">Encoded <b>{{$lcr_livebirth}}</b> Livebirths in Local City Registrar (LCR)</td>
                            </tr>
                            @endif
                            @if($edcs_count != 0)
                            <tr>
                                <td class="text-center">Imported <b>{{$edcs_count}}</b> Cases from DOH EDCS Information System</td>
                            </tr>
                            @endif
                            @if($death_count != 0)
                            <tr>
                                <td class="text-center">Encoded <b>{{$death_count}}</b> Death Certificates for Mortality to eFHSIS</td>
                            </tr>
                            @endif
                            @if($opdtoics_count != 0)
                            <tr>
                                <td class="text-center">Encoded <b>{{$opdtoics_count}}</b> OPD to iClinicSys Tickets</td>
                            </tr>
                            @endif
                            @if($abtctoics_count != 0)
                            <tr>
                                <td class="text-center">Encoded <b>{{$abtctoics_count}}</b> Animal Bite to iClinicSys Tickets</td>
                            </tr>
                            @endif

                            @endif
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @if(auth()->user()->isArChecker() || auth()->user()->isArApprover())
            @if($ar)

            @else
            <div class="card-footer text-right">
                <form action="{{route('encoderstats_approvear', ['id' => $id, 'year' => $year, 'month' => $month])}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>
            @endif
            @endif
        </div>
    </div>

    <form action="" method="GET">
        <div class="modal fade" id="changeMonth" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Load Monthly Accomplishment Report</h5>
                    </div>
                    <div class="modal-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Year</label>
                            <input type="number" class="form-control" name="year" id="year" min="2022" max="{{date('Y')}}" value="{{date('Y')}}" required>
                          </div>
                        <div class="form-group">
                            <label for="month"><b class="text-danger">*</b>Select Month</label>
                            <select class="form-control" name="month" id="month" required>
                                <option value="" disabled selected>Choose...</option>
                                <option value="1" {{(request()->input('month') == 1 || date('n', strtotime('-1 Month')) == 1) ? 'selected' : ''}}>January</option>
                                <option value="2" {{(request()->input('month') == 2 || date('n', strtotime('-1 Month')) == 2) ? 'selected' : ''}}>February</option>
                                <option value="3" {{(request()->input('month') == 3 || date('n', strtotime('-1 Month')) == 3) ? 'selected' : ''}}>March</option>
                                <option value="4" {{(request()->input('month') == 4 || date('n', strtotime('-1 Month')) == 4) ? 'selected' : ''}}>April</option>
                                <option value="5" {{(request()->input('month') == 5 || date('n', strtotime('-1 Month')) == 5) ? 'selected' : ''}}>May</option>
                                <option value="6" {{(request()->input('month') == 6 || date('n', strtotime('-1 Month')) == 6) ? 'selected' : ''}}>June</option>
                                <option value="7" {{(request()->input('month') == 7 || date('n', strtotime('-1 Month')) == 7) ? 'selected' : ''}}>July</option>
                                <option value="8" {{(request()->input('month') == 8 || date('n', strtotime('-1 Month')) == 8) ? 'selected' : ''}}>August</option>
                                <option value="9" {{(request()->input('month') == 9 || date('n', strtotime('-1 Month')) == 9) ? 'selected' : ''}}>September</option>
                                <option value="10" {{(request()->input('month') == 10 || date('n', strtotime('-1 Month')) == 10) ? 'selected' : ''}}>October</option>
                                <option value="11" {{(request()->input('month') == 11 || date('n', strtotime('-1 Month')) == 11) ? 'selected' : ''}}>November</option>
                                <option value="12" {{(request()->input('month') == 12 || date('n', strtotime('-1 Month')) == 12) ? 'selected' : ''}}>December</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-success btn-warning" href="{{route('encoder_stats_index')}}">Cancel</a>
                        <button type="submit" class="btn btn-success">Submit and View</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        @if(!request()->input('year') && !request()->input('month'))
        $('#changeMonth').modal({backdrop: 'static', keyboard: false});
        $('#changeMonth').modal('show');
        @endif
    </script>
@endsection