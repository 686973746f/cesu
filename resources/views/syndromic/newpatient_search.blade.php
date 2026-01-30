@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Search Results</b></div>
            <div class="card-body">
                <div class="alert alert-primary" role="alert">
                    Search result returned similar records. If the patient already exists, please select from the list below.
                    <hr>
                    If not, click "Add New Patient" below to create a new record.
                </div>
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Name</th>
                            <th>Birthdate</th>
                            <th>Address</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td>
                                <div><a href="">{{ $d->getName() }}</a></div>
                                <div>{{$d->getAge()}} / {{$d->gender}}</div>
                            </td>
                            <td>{{ date('m/d/Y', strtotime($d->bdate)) }}</td>
                            <td>{{ $d->getFullAddress() }}</td>
                            <td>{{ date('m/d/Y h:i A', strtotime($d->created_at)) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="{{ route('syndromic_newPatient') }}" class="btn btn-success btn-block">Add New Patient</a>
            </div>
        </div>
    </div>
@endsection