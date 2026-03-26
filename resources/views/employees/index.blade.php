@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Employees as of {{date('F d, Y')}}</b></div>
                <div><a href="{{route('employees_add')}}" class="btn btn-success">Add</a></div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <div class="alert alert-info" role="alert">
                <div class="row">
                    <div class="col-md-4">
                        <h5>Permanent: {{$emp1 = (clone $demographic_query)->whereHas('latestEmploymentStatus', function($q) {
                            $q->where('job_type', 'REGULAR')
                            ->where('status', 'ACTIVE');
                        })->count()}}</h5>
                        <h5>Casual: {{$emp2 = (clone $demographic_query)->whereHas('latestEmploymentStatus', function($q) {
                            $q->where('job_type', 'CASUAL')
                            ->where('status', 'ACTIVE');
                        })->count()}}</h5>
                        <h5>Job Order: {{$emp3 = (clone $demographic_query)->whereHas('latestEmploymentStatus', function($q) {
                            $q->where('job_type', 'JOB ORDER')
                            ->where('status', 'ACTIVE');
                        })->count()}}</h5>
                        <h5>Contractual/Consultant: {{$emp4 = (clone $demographic_query)->whereHas('latestEmploymentStatus', function($q) {
                            $q->where('job_type', 'CONTRACTUAL')
                            ->where('status', 'ACTIVE');
                        })->count()}}</h5>
                        <h5><b>Total: {{$emp1 + $emp2 + $emp3 + $emp4}}</b></h5>
                        <hr>
                        <h5>HRH-NDP: {{(clone $demographic_query)->whereHas('latestEmploymentStatus', function($q) {
                            $q->where('status', 'ACTIVE');
                        })
                        ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['HRH-NDP'])
                        ->count()}}</h5>
                        <h5>DOH-SHC: {{(clone $demographic_query)->whereHas('latestEmploymentStatus', function($q) {
                            $q->where('status', 'ACTIVE');
                        })
                        ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['DOH-SHC'])
                        ->count()}}</h5>
                    </div>
                    <div class="col-md-4">
                        @php
                        $demographic2 = (clone $demographic_query)->whereHas('latestEmploymentStatus', function($q) {
                            $q->where('job_type', 'REGULAR')
                            ->where('status', 'ACTIVE');
                        });
                        @endphp
                        <h5><b>PERMANENT</b></h5>
                        <ul>
                            <li><h5>RN: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['NURSE'])
                            ->count()}}</h5></li>
                            <li><h5>RM: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['MIDWIFE'])
                            ->count()}}</h5></li>
                            <li><h5>RMT: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['MEDTECH'])
                            ->count()}}</h5></li>
                            <li><h5>DMD: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['DENTIST'])
                            ->count()}}</h5></li>
                            <li><h5>MD: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PHYSICIAN'])
                            ->count()}}</h5></li>
                            <li><h5>RPH: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PHARMACIST'])
                            ->count()}}</h5></li>
                            <li><h5>RT: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['RADIO TECHNOLOGIST'])
                            ->count()}}</h5></li>
                            <li><h5>OB-GYN: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['OB-GYN'])
                            ->count()}}</h5></li>
                            <li><h5>Psychometrician: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PSYCHOMETRICIAN'])
                            ->count()}}</h5></li>
                            <li><h5>Psychologist: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PSYCHOLOGIST'])
                            ->count()}}</h5></li>
                            <li><h5>RPH: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PHARMACIST'])
                            ->count()}}</h5></li>
                            <li><h5>Ambulance Driver: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['AMBULANCE DRIVER'])
                            ->count()}}</h5></li>
                            <li><h5>Driver: {{ (clone $demographic2)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['DRIVER'])
                            ->count()}}</h5></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        @php
                        $demographic3 = (clone $demographic_query)->whereHas('latestEmploymentStatus', function($q) {
                            $q->where('job_type', 'JOB ORDER')
                            ->where('status', 'ACTIVE');
                        });
                        @endphp
                        <h5><b>JOB ORDER</b></h5>
                        <ul>
                            <li><h5>RN: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['NURSE'])
                            ->count()}}</h5></li>
                            <li><h5>RM: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['MIDWIFE'])
                            ->count()}}</h5></li>
                            <li><h5>RMT: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['MEDTECH'])
                            ->count()}}</h5></li>
                            <li><h5>DMD: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['DENTIST'])
                            ->count()}}</h5></li>
                            <li><h5>MD: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PHYSICIAN'])
                            ->count()}}</h5></li>
                            <li><h5>RPH: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PHARMACIST'])
                            ->count()}}</h5></li>
                            <li><h5>RT: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['RADIO TECHNOLOGIST'])
                            ->count()}}</h5></li>
                            <li><h5>OB-GYN: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['OB-GYN'])
                            ->count()}}</h5></li>
                            <li><h5>Psychometrician: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PSYCHOMETRICIAN'])
                            ->count()}}</h5></li>
                            <li><h5>Psychologist: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PSYCHOLOGIST'])
                            ->count()}}</h5></li>
                            <li><h5>RPH: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['PHARMACIST'])
                            ->count()}}</h5></li>
                            <li><h5>Ambulance Driver: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['AMBULANCE DRIVER'])
                            ->count()}}</h5></li>
                            <li><h5>Driver: {{ (clone $demographic3)
                            ->whereRaw("FIND_IN_SET(?, emp_access_list)", ['DRIVER'])
                            ->count()}}</h5></li>
                        </ul>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#filterModal">Filter</button>

            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="mainTbl">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Sub-Office</th>
                        <th>Date Hired</th>
                        <th>Length of Service</th>
                        <th>BLS Trained</th>
                        <th>Type of Responder</th>
                        <th>HERO Trained</th>
                        <th>WASH-N Trained</th>
                        <th>Nutrition in Emergencies Trained</th>
                        <th>HERT Team</th>
                        <th>Deployable in Duties</th>
                        <th>Duty Cycle Status</th>
                        <th>Duty Balance</th>
                        <th>T-Shirt Size</th>
                        <th>Access List</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $ind => $d)
                    <tr>
                        <td class="text-center">{{$ind+1}}</td>
                        <td><a href="{{route('employees_edit', $d->id)}}"><b>{{$d->getFullName()}}</b></a></td>
                        <td class="text-center">{{$d->gender}}</td>
                        <td class="text-center">{{$d->latestEmploymentStatus->status ?? 'N/A'}}</td>
                        <td class="text-center">{{$d->latestEmploymentStatus->job_type ?? 'N/A'}}</td>
                        <td class="text-center">{{$d->latestEmploymentStatus->job_position ?? 'N/A'}}</td>
                        <td class="text-center">{{$d->latestEmploymentStatus->office ?? 'N/A'}}</td>
                        <td class="text-center">{{$d->latestEmploymentStatus->sub_office ?? 'N/A'}}</td>
                        <td class="text-center">{{($d->oldEmploymentStatus) ? Carbon\Carbon::parse($d->oldEmploymentStatus->effective_date)->format('m/d/Y') : 'N/A'}}</td>
                        <td class="text-center">{{$d->getLengthOfService()}}</td>
                        <td class="text-center">{{$d->is_blstrained}}</td>
                        <td class="text-center">{{$d->bls_typeofrescuer}}</td>
                        <td class="text-center">{{$d->is_herotrained}}</td>
                        <td class="text-center">{{$d->is_washntrained}}</td>
                        <td class="text-center">{{$d->is_nutriemergtrained}}</td>
                        <td class="text-center">{{$d->duty_team}}</td>
                        <td class="text-center">{{$d->duty_canbedeployed}}</td>
                        <td class="text-center">{{($d->duty_completedcycle == 'Y') ? 'DONE' : 'PENDING'}}</td>
                        <td class="text-center">{{$d->duty_balance}}</td>
                        <td class="text-center">{{$d->shirt_size}}</td>
                        <td class="text-center">{{$d->emp_access_list}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<form action="" method="GET">
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="showAll" id="showAll" value="1" {{(request()->input('showAll')) ? 'checked' : ''}}>Show All Employees (Including Resigned and Retired Employees)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Filter</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#mainTbl').dataTable({
        order : [[1, 'asc']],
        dom: 'QBfritp',
        buttons: [
            {
                extend: 'excel',
                title: '',
            },
            'copy',
        ],
    });
</script>
@endsection