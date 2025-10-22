@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <b>General Trias City CESU - SBDS: Home Page</b>
                        <div><b>School:</b> {{$s->name}}</div>
                    </div>
                    <div>
                        <a href="{{route('sbs_new', $s->qr)}}" class="btn btn-success">New Case</a>
                        <a href="{{route('sbs_report')}}" class="btn btn-primary">View Report</a>
                        @if(auth()->guard('school')->check())
                        <form action="{{ route('sbs_logout') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to log out?');">
                            @csrf
                            <button type="submit" class="btn btn-danger">Logout</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-primary" role="alert">
                    <div><b>LEGAL NOTICE:</b> All personal, health, and sensitive information under the custody of the Department of Education and the General Trias City Health Office - CESU are strictly confidential and protected under the <b>Data Privacy Act of 2012 (R.A. 10173)</b>. Any person who gains access to such data is prohibited from sharing, reproducing, disclosing, taking screenshots, or disseminating said information through any means without proper authority.</div>
                    <div>Furthermore, in accordance with <b>Republic Act 11332</b> (Mandatory Reporting of Notifiable Diseases and Health Events of Public Health Concern Act), it is hereby reminded that all health data and reports must be accurate, true, and correctly submitted. All required details—including the patient’s name, address, symptoms, and other pertinent information—must be properly and truthfully filled out. Falsification, non-reporting, or misreporting of data constitutes a violation of the law and shall be subject to corresponding administrative, civil, and criminal liabilities under existing laws.</div>
                </div>
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <button type="button" class="btn btn-secondary mb-3" data-toggle="modal" data-target="#filter">Filter</button>

                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Case Date</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Address</th>
                            <th>Barangay</th>
                            <th>Type</th>
                            <th>Grade Level/Designation</th>
                            <th>Section</th>
                            <th>Date Onset of Illness</th>
                            <th>Signs and Symptoms</th>
                            <th>Suspected Disease Tag</th>
                            <th>Reported By</th>
                            <th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($l->date_reported))}}</td>
                            <td><a href="{{route('sbs_view', $l->id)}}">{{$l->getName()}}</a></td>
                            <td class="text-center">{{$l->getAgeInt()}}</td>
                            <td class="text-center">{{$l->sex}}</td>
                            <td class="text-center">{{$l->street_purok}}</td>
                            <td class="text-center">{{$l->brgy->name}}</td>
                            <td class="text-center">{{$l->patient_type}}</td>
                            <td class="text-center">{{$l->getGradeOrDesignation()}}</td>
                            <td class="text-center">{{($l->patient_type == 'STUDENT') ? $l->section : 'N/A'}}</td>
                            <td class="text-center">{{date('m/d/Y', strtotime($l->onset_illness_date))}}</td>
                            <td class="text-center">{{$l->signs_and_symptoms}}</td>
                            <td class="text-center">{{($l->suspected_disease_tag ?: 'N/A')}}</td>
                            <td class="text-center">{{$l->reported_by}}</td>
                            
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($l->created_at))}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <form action="" method="GET">
        <div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                        <label for="year"><b class="text-danger">*</b>Filter by Year</label>
                        <select class="form-control" name="year" id="year" required>
                            @foreach(range(date('Y'), 2024) as $y)
                            <option value="{{$y}}">{{$y}}</option>
                            @endforeach
                        </select>
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
            order: [[14, 'desc']],
        });
    </script>
@endsection