@extends('layouts.app')

@section('content')
<style>
    #loading {
        position: fixed;
        display: block;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        text-align: center;
        background-color: #fff;
        z-index: 99;
    }
</style>
<div id="loading">
    <div class="text-center">
        <i class="fas fa-circle-notch fa-spin fa-5x my-3"></i>
        <h3>Loading...</h3>
    </div>
</div>

    <div class="container-fluid">
        <p>Today is: {{date('M. d, Y')}} - Morbidity Week: {{date('W')}}</p>
        @if(Str::contains(request()->url(), 'facility_report'))
        <a href="{{route('facility_report_case_checker', $f->sys_code1)}}" class="btn btn-secondary mb-3">Back</a>
        @else
        <a href="{{route('edcs_barangay_home')}}" class="btn btn-secondary mb-3">Back</a>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>View <span class="text-danger">{{mb_strtoupper($case)}}</span> Cases (BRGY. {{session('brgyName')}}) - Year: {{$year}}</b></div>
                    <div><a href="{{route('edcs_barangay_spotmap_viewer', ['case' => mb_strtoupper($case), 'year' => $year])}}" class="btn btn-primary"><i class="fas fa-map-marked-alt mr-2"></i>View Spot Map</a></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="alert alert-primary" role="alert">
                    <b class="text-danger">Note:</b> If duplicate data/incorrect address was found, please report to CESU.
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mainTbl">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Birthdate</th>
                                <th>Age/Sex</th>
                                <th>Address</th>
                                <th>Contact No.</th>
                                <th>Case Classification</th>
                                <th>Outcome</th>
                                <th>Disease Reporting Unit</th>
                                <th>Morbidity Week</th>
                                <th>Morbidity Month</th>
                                <th>Year</th>
                                <th>Created at/by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $ind => $l)
                            @php
                            if($case == 'SevereAcuteRespiratoryInfection') {
                                $epi_id = $l->epi_id;
                                $mw = $l->morbidity_week;
                                $mm = $l->morbidity_month;
                                $my = $l->year;
                                

                                $dob = $l->birthdate;
                                $age_years = $l->age_years;
                                $sex = $l->sex;
                                $streetpurok = $l->streetpurok;
                                $brgy = $l->barangay;
                                $facility_name = $l->facility_name;
                                $outcome = $l->outcome;
                                $cc = $l->case_classification;
                            }
                            else {
                                $epi_id = $l->EPIID;
                                $mw = $l->MorbidityWeek;
                                $mm = $l->MorbidityMonth;
                                $my = $l->Year;

                                $dob = $l->DOB;
                                $age_years = $l->AgeYears;
                                $sex = $l->Sex;
                                $streetpurok = $l->Streetpurok;
                                $brgy = $l->Barangay;
                                $facility_name = $l->NameOfDru;
                                $outcome = $l->Outcome;
                                $cc = $l->CaseClassification;
                            }
                            @endphp
                            <tr>
                                <td class="text-center">{{$ind+1}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-left" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <b>{{$l->getName()}}</b> @if((date('W') - 1) == $mw)<span class="badge badge-danger ml-1">NEW</span>@endif
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{route('edcs_barangay_view_cif', [mb_strtoupper($case), $epi_id])}}" class="dropdown-item">View CIF</a>
                                            <a href="{{route('edcs_barangay_edit_cif', [mb_strtoupper($case), $epi_id])}}" class="dropdown-item">Edit Details</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{date('m/d/Y', strtotime($dob))}}</td>
                                <td class="text-center">{{$age_years}}/{{$sex}}</td>
                                <td class="text-center">
                                    <div>{{(!is_null($streetpurok)) ? $streetpurok : 'NO STREET PUROK'}}</div>
                                    <div><b>BRGY. {{$brgy}}</b></div>
                                </td>
                                <td class="text-center">{{$l->edcs_contactNo ?: 'N/A'}}</td>
                                <td class="text-center">{{$cc}}</td>
                                <td class="text-center">{{$outcome}}</td>
                                <td class="text-center">{{$facility_name}}</td>
                                <td class="text-center">{{$mw}}</td>
                                <td class="text-center">{{$mm}}</td>
                                <td class="text-center">{{$my}}</td>
                                <td class="text-center">
                                    <div>{{date('m/d/Y h:i A', strtotime($l->created_at))}}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#loading').fadeOut();
        });

        $('#mainTbl').dataTable({
            dom: 'Bfrtip',
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