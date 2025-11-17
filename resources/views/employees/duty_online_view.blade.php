@extends('layouts.app')

@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>CHO HERT Duty List as of {{date('M. d, Y h:i A')}}</b></div>
                <div>
                    @if(request()->input('masterlistView'))
                    <a href="{{route('hert_duty_online_view')}}" class="btn btn-secondary">Switch to Team View</a>
                    @else
                    <a href="{{route('hert_duty_online_view')}}?masterlistView=1" class="btn btn-primary">Switch to Masterlist View</a>
                    @endif
                </div>
            </div>
        </div>
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
                        <h5 class="text-center"><b>Team A (Total: {{$ta_total}})</b></h5>
                        <h6>Deployed: {{$ta_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$ta_notdeployed}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-center"><b>Team B (Total: {{$tb_total}})</b></h5>
                        <h6>Deployed: {{$tb_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$tb_notdeployed}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-center"><b>Team C (Total: {{$tc_total}})</b></h5>
                        <h6>Deployed: {{$tc_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$tc_notdeployed}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-center"><b>Team D (Total: {{$td_total}})</b></h5>
                        <h6>Deployed: {{$td_deployed}}</h6>
                        <h6>Not Yet Deployed: {{$td_notdeployed}}</h6>
                    </div>
                </div>
                <hr>
                <h5>
                    <div>Ang <b>Duty Cycle</b> po ay tumutukoy sa kung nakakailang ikot na tayo ng dutyhan simula ng nabuo ang system. Tataas ng isang bilang ang cycle kapag lahat/karamihan ng mga members ng Team A to Team D ay nai-deploy na. Tayo ngayon ay nasa <b>Cycle {{$cycle_count}}</b>.</div>
                    <div class="mt-3">Kapag nagbago na ng Cycle, babalik sa PENDING lahat ng Team A to D, at ang mga hindi nakapag-duty sa nakaraang Cycle ay magkakaroon ng tinatawag na <b>"Duty Balance"</b> sa bagong Cycle.</div>
                    <div class="mt-3">Ang CHO Staff na may duty balance, ibig sabihin hindi siya nakapag-duty sa nakaraang Cycle. At para mawala yun, kailangan niyang dumuty ngayong <b>Cycle + kung ilan ang kanyang Duty Balance.</b></div>
                </h5>
                <hr>
                <h5>For any questions or concerns, you may contact us at <b>CESU/DRRM-H Office</b>. Please be guided accordingly. Thank you.</h5>
                <hr>
                <ul>
                    <h5>Legend:</h5>
                    <li><span class="badge badge-success">HCP</span> - BLS Trained Health Care Provider</li>
                    <li><span class="badge badge-primary">LR</span> - BLS Trained Lay Rescuer</li>
                </ul>
            </div>

            @if(is_null(request()->input('masterlistView')))
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
                                            <th>Nakapag-duty na ngayong Cycle</th>
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
                                            <td class="text-center">
                                                @if($d->getLatestDuty())
                                                {{$d->getLatestDuty()->event->event_name.' ('.date('M. d, Y', strtotime($d->getLatestDuty()->event->event_date)).')'}} <span class="badge badge-{{($d->getLatestDuty()->event->cycle_number != $cycle_count) ? 'secondary' : 'success'}}">CYCLE {{$d->getLatestDuty()->event->cycle_number}}</span>
                                                @else
                                                N/A
                                                @endif
                                            </td>
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
                        <div class="card-header"><b>Team B</b></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tbl2">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Nakapag-duty na ngayong Cycle</th>
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
                                            <td class="text-center">
                                                @if($d->getLatestDuty())
                                                {{$d->getLatestDuty()->event->event_name.' ('.date('M. d, Y', strtotime($d->getLatestDuty()->event->event_date)).')'}} <span class="badge badge-{{($d->getLatestDuty()->event->cycle_number != $cycle_count) ? 'secondary' : 'success'}}">CYCLE {{$d->getLatestDuty()->event->cycle_number}}</span>
                                                @else
                                                N/A
                                                @endif
                                            </td>
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
                                            <th>Nakapag-duty na ngayong Cycle</th>
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
                                            <td class="text-center">
                                                @if($d->getLatestDuty())
                                                {{$d->getLatestDuty()->event->event_name.' ('.date('M. d, Y', strtotime($d->getLatestDuty()->event->event_date)).')'}} <span class="badge badge-{{($d->getLatestDuty()->event->cycle_number != $cycle_count) ? 'secondary' : 'success'}}">CYCLE {{$d->getLatestDuty()->event->cycle_number}}</span>
                                                @else
                                                N/A
                                                @endif
                                            </td>
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
                                            <th>Nakapag-duty na ngayong Cycle</th>
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
                                            <td class="text-center">
                                                @if($d->getLatestDuty())
                                                {{$d->getLatestDuty()->event->event_name.' ('.date('M. d, Y', strtotime($d->getLatestDuty()->event->event_date)).')'}} <span class="badge badge-{{($d->getLatestDuty()->event->cycle_number != $cycle_count) ? 'secondary' : 'success'}}">CYCLE {{$d->getLatestDuty()->event->cycle_number}}</span>
                                                @else
                                                N/A
                                                @endif
                                            </td>
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
            @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="masterlisttbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>HERT Team</th>
                            <th>Nakapag-duty na ngayong Cycle</th>
                            <th>Last Duty</th>
                            <th>Duty Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $d)
                        <tr>
                            <td class="text-center">{{$ind + 1}}</td>
                            <td>
                                {{$d->getName()}}
                                @if($d->is_blstrained == 'Y')
                                @if($d->bls_typeofrescuer == 'LR')
                                <span class="badge badge-primary">LR</span>
                                @else
                                <span class="badge badge-success">HCP</span>
                                @endif
                                @endif
                            </td>
                            <td class="text-center">{{$d->duty_team}}</td>
                            <td class="text-center">{{($d->duty_completedcycle == 'Y') ? 'DONE' : 'PENDING'}}</td>
                            <td class="text-center">
                                @if($d->getLatestDuty())
                                {{$d->getLatestDuty()->event->event_name.' ('.date('M. d, Y', strtotime($d->getLatestDuty()->event->event_date)).')'}} <span class="badge badge-{{($d->getLatestDuty()->event->cycle_number != $cycle_count) ? 'secondary' : 'success'}}">CYCLE {{$d->getLatestDuty()->event->cycle_number}}</span>
                                @else
                                N/A
                                @endif
                            </td>
                            <td class="text-center">{{$d->duty_balance}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    @if(is_null(request()->input('masterlistView')))
    $('#tbl1, #tbl2, #tbl3, #tbl4').dataTable({
        iDisplayLength: -1,
        dom: 'fti',
        responsive: {
            details: true,
        }
    });
    @else
    $('#masterlisttbl').dataTable({
        iDisplayLength: -1,
        dom: 'QBfti',
        buttons: [
            {
                extend: 'excel',
                title: '',
            },
            'copy',
        ],
        responsive: {
            details: true,
        }
    });
    @endif
</script>
@endsection