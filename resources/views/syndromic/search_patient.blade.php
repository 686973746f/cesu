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
                        <th>Last Consultation</th>
                        <th>Encoded by / At</th>
                        <th>Updated by / At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $ind => $d)
                    <tr>
                        <td class="text-center">{{$list->firstItem() + $ind}}</td>
                        <td><b><a href="{{route('syndromic_viewPatient', $d->id)}}">{{$d->getName()}}</a></b></td>
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
                            <a href="{{route('syndromic_viewRecord', $d->getLastCheckup()->id)}}">{{date('m/d/Y', strtotime($d->getLastCheckup()->created_at))}}</a>
                            @endif
                        </td>
                        <td class="text-center">
                            <div><small>{{$d->user->name}}</small></div>
                            <div><small>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</small></div>
                        </td>
                        <td class="text-center"><small>{{($d->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($d->created_at)).' / '.$d->getUpdatedBy->name : 'N/A'}}</small></td>
                    </tr>
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
@endsection