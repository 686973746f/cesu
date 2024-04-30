@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <a href="{{route('edcs_barangay_home')}}" class="btn btn-secondary mb-3">Back</a>
        <div class="card">
            <div class="card-header">
                <div>
                    <div><b>View <span class="text-danger">{{mb_strtoupper($case)}}</span> Cases (BRGY. {{session('brgyName')}}) - Year: {{$year}}</b></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Birthdate</th>
                            <th>Age/Sex</th>
                            <th>Address</th>
                            <th>Case Classification</th>
                            <th>Outcome</th>
                            <th>Disease Reporting Unit</th>
                            <th>Created at/by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td><a href="{{route('edcs_barangay_view_cif', [mb_strtoupper($case), $l->EPIID])}}"><b>{{$l->getName()}}</b></a></td>
                            <td class="text-center">{{date('m/d/Y', strtotime($l->DOB))}}</td>
                            <td class="text-center">{{$l->AgeYears}}/{{$l->Sex}}</td>
                            <td class="text-center">
                                <div>{{(!is_null($l->Streetpurok)) ? $l->Streetpurok : 'NO STREET PUROK'}}</div>
                                <div>BRGY. {{$l->Barangay}}</div>
                            </td>
                            <td class="text-center">{{$l->CaseClassification}}</td>
                            <td class="text-center">{{$l->Outcome}}</td>
                            <td class="text-center">{{$l->NameOfDru}}</td>
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

    <script>
        $('#mainTbl').dataTable();
    </script>
@endsection