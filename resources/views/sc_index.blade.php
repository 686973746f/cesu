@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card text-left">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">Health Declaration Records</div>
                    <div><a class="btn btn-primary" href="{{route('sc_create')}}">Add</a></div>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                        <tr>
                            <td></td>
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