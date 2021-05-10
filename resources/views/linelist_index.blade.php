@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    Line List
                </div>
                <div>
                    <a href="{{route('linelist.createlasalle')}}" class="btn btn-success">Create LaSalle</a>
                    <a href="{{route('linelist.createoni')}}" class="btn btn-success">Create ONI</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-{{session('statustype')}}" role="alert">
                    {{session('status')}}
                </div>
                <hr>
            @endif
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Date Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $item)
                    <tr>
                        <td scope="row">{{$key+1}}</td>
                        <td>{{($item->type == 1) ? 'ONI' : 'LASALLE'}}</td>
                        <td>{{$item->created_at}}</td>
                        <td><a class="btn btn-primary" href="linelist/oni/print/{{$item->id}}">Print</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
