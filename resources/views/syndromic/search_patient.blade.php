@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>Search Patient</b> | Results Found: {{$list->total()}}</div>
        <div class="card-body">
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
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Name/ID</th>
                        <th>Birthdate</th>
                        <th>Age/Sex</th>
                        <th>Contact Number</th>
                        <th>Street/Subdivision</th>
                        <th>Barangay</th>
                        <th>City</th>
                        <th>Encoded by / At</th>
                        <th>Updated by / At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $ind => $d)
                    <tr>
                        <td class="text-center">{{$list->firstItem() + $ind}}</td>
                        <td><b><a href="{{route('syndromic_viewPatient', $d->id)}}">{{$d->getName()}}</a></b></td>
                        <td class="text-center">{{date('m/d/Y', strtotime($d->bdate))}}</td>
                        <td class="text-center">{{$d->getAge()}} / {{substr($d->gender,0,1)}}</td>
                        <td class="text-center">{{$d->getContactNumber()}}</td>
                        <td class="text-center">{{$d->getStreetPurok()}}</td>
                        <td class="text-center">{{$d->address_brgy_text}}</td>
                        <td class="text-center">{{$d->address_muncity_text}}</td>
                        <td class="text-center"><small>{{$d->user->name}} @ {{date('m/d/Y h:i A', strtotime($d->created_at))}}</small></td>
                        <td class="text-center"><small>{{($d->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($d->created_at)).' / '.$d->getUpdatedBy->name : 'N/A'}}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>
@endsection