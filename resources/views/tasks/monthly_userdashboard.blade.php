@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Monthly Accomplishment Report for <span class="text-success">{{mb_strtoupper(Carbon\Carbon::createFromDate(request()->input('year'), request()->input('month'), 1)->format('F Y'))}}</span></b></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr class="text-center">
                            <td colspan="2">
                                <div><b>Name: {{mb_strtoupper($name)}}</b></div>
                                <div>MONTHLY ACCOMPLISHMENT: {{mb_strtoupper(Carbon\Carbon::createFromDate(request()->input('year'), request()->input('month'), 1)->format('F Y'))}}</div>
                            </td>
                        </tr>
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
                    </tbody>
                </table>
            </div>
            @if(is_null($ar) && auth()->user()->isArChecker() && auth()->user()->isArApprover())
            <div class="card-footer">
                <form action="{{route('encoderstats_approvear', ['id' => $id, 'year' => $year, 'month' => $month])}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>
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
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Submit and View</button>
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