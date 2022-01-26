@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card text-left">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">Health Declaration Records</div>
                    <div><a class="btn btn-success" href="{{route('sc_create')}}"><i class="fa fa-user-plus mr-2" aria-hidden="true"></i>Add Record</a></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="table-responsive">
                    @if($list->total() < 1)
                    <p class="text-center">There are no existing records yet.</p>
                    @else
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Name/ID</th>
                                <th>Age/Gender</th>
                                <th>Birthdate</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Street</th>
                                <th>Brgy</th>
                                <th>City/Province</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $item)
                            <tr>
                                <td><a href="">{{$item->getName()}}</a></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection