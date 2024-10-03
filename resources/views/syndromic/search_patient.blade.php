@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{route('syndromic_home')}}" method="GET">
        <div class="row">
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="SEARCH BY SURNAME, NAME / ID" style="text-transform: uppercase;" required>
                    <div class="input-group-append">
                      <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </div>

                    <button type="button" class="btn btn-secondary ml-2" data-toggle="modal" data-target="#advanceSearch">
                        Advanced Search
                    </button>
                </div>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-header"><b>Search Patient</b> | {{Str::plural('Result', $list->total())}} Found: {{$list->total()}}</div>
        <div class="card-body">
            @if($list->count() != 0)
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Age/Sex/DOB</th>
                        <th>Contact Number</th>
                        <th>Complete Address</th>
                        <th>
                            <div>Last Consultation/</div>
                            <div>Chief Complaint</div>
                        </th>
                        <th class="{{($search_mode == 'DIAG') ? 'bg-warning' : ''}}">Diagnosis</th>
                        <th>Encoded by / At</th>
                        <th>Updated by / At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $ind => $d)
                    @if($search_mode == 'PATIENT')
                    <tr>
                        <td class="text-center">{{$list->firstItem() + $ind}}</td>
                        <td>
                            <div><b><a href="{{route('syndromic_viewPatient', $d->id)}}">{{$d->getName()}}</a></b></div>
                            @if(!is_null($d->unique_opdnumber) && auth()->user()->itr_facility_id ==10525)
                            <div><small>OPD No. {{$d->unique_opdnumber}}</small></div>
                            @endif
                            @if($d->facility_id == 11730 && auth()->user()->itr_facility_id == 11730)
                            <div><small>Control No: {{$d->facility_controlnumber}}</small></div>
                            @endif
                        </td>
                        <td class="text-center">
                            <div>{{$d->getAge()}}/{{substr($d->gender,0,1)}}</div>
                            <div>{{date('m/d/Y', strtotime($d->bdate))}}</div>
                        </td>
                        <td class="text-center">{{$d->getContactNumber()}}</td>
                        <td class="text-center">
                            <small>{{$d->getStreetPurok()}}</small>
                            <h6>BRGY. {{$d->address_brgy_text}}</h6>
                        </td>
                        <td class="text-center">
                            @if(is_null($d->getLastCheckup()))
                            <h6>N/A</h6>
                            @else
                            <a href="{{route('syndromic_viewRecord', $d->getLastCheckup()->id)}}">
                                <div>{{date('m/d/Y', strtotime($d->getLastCheckup()->created_at))}}</div>
                            </a>
                            <div>{{$d->getLastCheckup()->chief_complain}}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            {{(!is_null($d->getLastCheckup())) ? $d->getLastCheckup()->dcnote_assessment : 'N/A'}}
                        </td>
                        <td class="text-center">
                            <div><small>{{$d->user->name}}</small></div>
                            <div><small>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</small></div>
                        </td>
                        <td class="text-center"><small>{{($d->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($d->created_at)).' / '.$d->getUpdatedBy->name : 'N/A'}}</small></td>
                    </tr>
                    @elseif($search_mode == 'DIAG')
                    <tr>
                        <td class="text-center">{{$list->firstItem() + $ind}}</td>
                        <td><b><a href="{{route('syndromic_viewPatient', $d->syndromic_patient->id)}}">{{$d->syndromic_patient->getName()}}</a></b></td>
                        <td class="text-center">
                            <div>{{$d->syndromic_patient->getAge()}}/{{substr($d->syndromic_patient->gender,0,1)}}</div>
                            <div>{{date('m/d/Y', strtotime($d->syndromic_patient->bdate))}}</div>
                        </td>
                        <td class="text-center">{{$d->syndromic_patient->getContactNumber()}}</td>
                        <td class="text-center">
                            <small>{{$d->syndromic_patient->getStreetPurok()}}</small>
                            <h6>BRGY. {{$d->syndromic_patient->address_brgy_text}}</h6>
                        </td>
                        <td class="text-center">
                            <a href="{{route('syndromic_viewRecord', $d->id)}}">
                                <div>{{date('m/d/Y', strtotime($d->created_at))}}</div>
                            </a>
                            <div>{{$d->chief_complain}}</div>
                        </td>
                        <td class="text-center bg-warning">
                            <b>{{$d->dcnote_assessment ?: 'N/A'}}</b>
                        </td>
                        <td class="text-center">
                            <div><small>{{$d->user->name}}</small></div>
                            <div><small>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</small></div>
                        </td>
                        <td class="text-center">
                            <small>
                                @if($d->getUpdatedBy())
                                <div>{{date('m/d/Y h:i A', strtotime($d->updated_at))}}</div>
                                <div>{{$d->getUpdatedBy->name}}</div>
                                @else
                                <div>N/A</div>
                                @endif
                            </small>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
            @else
            <p class="text-center">No results found.</p>
            @endif
        </div>
    </div>
</div>

@include('syndromic.advanced_search_modal')
@endsection