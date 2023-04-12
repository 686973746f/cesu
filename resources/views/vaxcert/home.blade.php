@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>VaxCert Concerns</div>
                <div>
                    @if(request()->input('viewcomplete'))
                    Currently Viewing <b class="text-success">COMPLETED</b> List <i>(Oldest to Newest)</i>. <a href="{{route('vaxcert_home')}}" class="btn btn-warning ml-3">Show Pending</a>
                    @else
                    Currently Viewing <b class="text-warning">PENDING</b> List <i>(Newest to Oldest)</i>. <a href="{{route('vaxcert_home')}}?viewcomplete=1" class="btn btn-success ml-3">Show Completed</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            @if($list->count() != 0)
            <table class="table table-bordered table-striped">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Ticket ID</th>
                        <th>Name</th>
                        <th>Birthdate / Gender</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Concern Type</th>
                        <th>Category</th>
                        <th>Travel Type</th>
                        <th>Date Submitted</th>
                        @if(request()->input('viewcomplete'))
                        <th>Status</th>
                        <th>Processed by / At</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $d)
                    @php
                    if($d->status == 'PENDING') {
                        $stext = 'text-warning';
                    }
                    else if($d->status == 'COMPLETE') {
                        $stext = 'text-warning';
                    }
                    @endphp
                    <tr>
                        <td class="text-center">{{$d->id}}</td>
                        <td><a href="{{route('vaxcert_viewpatient', $d->id)}}">{{$d->getName()}}</a></td>
                        <td class="text-center">{{date('m/d/Y', strtotime($d->bdate))}} / {{$d->gender}}</td>
                        <td><small>{{$d->getAddress()}}</small></td>
                        <td class="text-center">{{$d->contact_number}}</td>
                        <td class="text-center">{{(!is_null($d->email)) ? $d->email : 'N/A'}}</td>
                        <td class="text-center">{{$d->concern_type}}</td>
                        <td class="text-center">{{$d->category}}</td>
                        <td class="text-center">{{($d->use_type == 'ABROAD') ? 'ABROAD - '.$d->passport_no : 'LOCAL'}}</td>
                        <td class="text-center"><small>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</small></td>
                        @if(request()->input('viewcomplete'))
                        <td class="text-center"><b>{{$d->status}}</b></td>
                        <td>{{$d->getProcessedBy()}} / <small>{{date('m/d/Y H:i A', strtotime($d->updated_at))}}</small></td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-center">No pending records found. Come back later.</p>
            @endif
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center mt-3">
                {{$list->links()}}
            </div>
        </div>
    </div>
</div>
@endsection