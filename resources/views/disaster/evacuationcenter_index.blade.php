@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>View Evacuation Center</b></div>
                    <div>
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#newEvacuationCenter">Options</button>
                        <a href="{{route('gtsecure_newpatient', $d->id)}}" class="btn btn-success">New Patient</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Age/Gender</th>
                            <th>Address</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient_list as $ind => $p)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td><a href="">{{$p->getName()}}</a></td>
                            <td>{{$p->getAge()}}/{{$p->sex}}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection