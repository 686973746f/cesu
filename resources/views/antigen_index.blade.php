@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>Antigen Kits</div>
                    <div><a href="{{route('antigen_create')}}" class="btn btn-success">Add</a></div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-stripeds">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Short Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $item)
                        <tr>
                            <td scope="row">{{$loop->iteration}}</td>
                            <td><a href="{{route('antigen_edit', ['id' => $item->id])}}">{{$item->antigenKitName}}</a></td>
                            <td>{{$item->antigenKitShortName}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection