@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">VaxCert Concerns</div>
        <div class="card-body">
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
                        <th>Date Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $d)
                    <tr>
                        <td class="text-center">{{$d->id}}</td>
                        <td><a href="{{route('vaxcert_viewpatient', $d->id)}}">{{$d->getName()}}</a></td>
                        <td class="text-center">{{date('m/d/Y', strtotime($d->bdate))}} / {{$d->gender}}</td>
                        <td><small>{{$d->getAddress()}}</small></td>
                        <td class="text-center">{{$d->contact_number}}</td>
                        <td class="text-center">{{(!is_null($d->email)) ? $d->email : 'N/A'}}</td>
                        <td class="text-center">{{$d->concern_type}}</td>
                        <td class="text-center">{{$d->category}}</td>
                        <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="pagination justify-content-center mt-3">
                {{$list->links()}}
            </div>
        </div>
    </div>
</div>
@endsection