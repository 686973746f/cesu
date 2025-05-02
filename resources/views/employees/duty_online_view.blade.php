@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>CHO HERT Duty List as of {{date('M. d, Y h:i A')}}</b></div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="alert alert-info" role="alert">
                <div class="text-center">
                    <h4>Total Responders: <b>{{$tot_emp_duty}}</b> (Male: {{$tot_emp_duty_male}}, Female: {{$tot_emp_duty_female}})</h4>
                    <h5>Current Cycle: <b>Cycle {{$cycle_count}}</b> (Already Deployed: {{$tot_emp_duty_alreadyassigned}} - Not Yet Deployed: {{$tot_emp_duty_notyetassigned}})</h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <h5 class="text-center">Team A (Total: {{$ta_total}})</h5>
                        <h6>Deployed: {{$ta_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$ta_notdeployed}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-center">Team B (Total: {{$tb_total}})</h5>
                        <h6>Deployed: {{$tb_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$tb_notdeployed}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-center">Team C (Total: {{$tc_total}})</h5>
                        <h6>Deployed: {{$tc_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$tc_notdeployed}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-center">Team D (Total: {{$td_total}})</h5>
                        <h6>Deployed: {{$td_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$td_notdeployed}}</h6>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><b>Team A</b></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tbl1">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Nakapag-duty na sa Current Cycle</th>
                                            <th>Last Duty</th>
                                            <th>Duty Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($team_a as $ind => $d)
                                        <tr>
                                            <td class="text-center">{{$ind+1}}</td>
                                            <td>{{$d->getName()}}
                                                @if($d->is_blstrained == 'Y')
                                                @if($d->bls_typeofrescuer == 'LR')
                                                <span class="badge badge-primary">LR</span>
                                                @else
                                                <span class="badge badge-success">HCP</span>
                                                @endif
                                                @endif
                                            </td>
                                            <td class="text-center">{{($d->duty_completedcycle == 'Y') ? 'DONE' : 'PENDING'}}</td>
                                            <td class="text-center">{{($d->getLatestDuty()) ? $d->getLatestDuty()->event->event_name.' ('.date('M. d, Y', strtotime($d->getLatestDuty()->event->event_date)).')' : 'N/A'}}</td>
                                            <td class="text-center">{{$d->duty_balance}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header"><b>Team B</b></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tbl2">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Nakapag-duty na sa Current Cycle</th>
                                            <th>Last Duty</th>
                                            <th>Duty Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($team_b as $ind => $d)
                                        <tr>
                                            <td class="text-center">{{$ind+1}}</td>
                                            <td>{{$d->getName()}}
                                                @if($d->is_blstrained == 'Y')
                                                @if($d->bls_typeofrescuer == 'LR')
                                                <span class="badge badge-primary">LR</span>
                                                @else
                                                <span class="badge badge-success">HCP</span>
                                                @endif
                                                @endif
                                            </td>
                                            <td class="text-center">{{($d->duty_completedcycle == 'Y') ? 'DONE' : 'PENDING'}}</td>
                                            <td class="text-center">{{($d->getLatestDuty()) ? $d->getLatestDuty()->event->event_name.' ('.date('M. d, Y', strtotime($d->getLatestDuty()->event->event_date)).')' : 'N/A'}}</td>
                                            <td class="text-center">{{$d->duty_balance}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mt-3">
                        <div class="card-header"><b>Team C</b></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tbl3">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Nakapag-duty na sa Current Cycle</th>
                                            <th>Last Duty</th>
                                            <th>Duty Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($team_c as $ind => $d)
                                        <tr>
                                            <td class="text-center">{{$ind+1}}</td>
                                            <td>{{$d->getName()}}
                                                @if($d->is_blstrained == 'Y')
                                                @if($d->bls_typeofrescuer == 'LR')
                                                <span class="badge badge-primary">LR</span>
                                                @else
                                                <span class="badge badge-success">HCP</span>
                                                @endif
                                                @endif
                                            </td>
                                            <td class="text-center">{{($d->duty_completedcycle == 'Y') ? 'DONE' : 'PENDING'}}</td>
                                            <td class="text-center">{{($d->getLatestDuty()) ? $d->getLatestDuty()->event->event_name.' ('.date('M. d, Y', strtotime($d->getLatestDuty()->event->event_date)).')' : 'N/A'}}</td>
                                            <td class="text-center">{{$d->duty_balance}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mt-3">
                        <div class="card-header"><b>Team D</b></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tbl4">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Nakapag-duty na sa Current Cycle</th>
                                            <th>Last Duty</th>
                                            <th>Duty Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($team_d as $ind => $d)
                                        <tr>
                                            <td class="text-center">{{$ind+1}}</td>
                                            <td>{{$d->getName()}}
                                                @if($d->is_blstrained == 'Y')
                                                @if($d->bls_typeofrescuer == 'LR')
                                                <span class="badge badge-primary">LR</span>
                                                @else
                                                <span class="badge badge-success">HCP</span>
                                                @endif
                                                @endif
                                            </td>
                                            <td class="text-center">{{($d->duty_completedcycle == 'Y') ? 'DONE' : 'PENDING'}}</td>
                                            <td class="text-center">{{($d->getLatestDuty()) ? $d->getLatestDuty()->event->event_name.' ('.date('M. d, Y', strtotime($d->getLatestDuty()->event->event_date)).')' : 'N/A'}}</td>
                                            <td class="text-center">{{$d->duty_balance}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#tbl1, #tbl2, #tbl3, #tbl4').dataTable({
        iDisplayLength: -1,
        dom: 'fti',
    });
</script>
@endsection