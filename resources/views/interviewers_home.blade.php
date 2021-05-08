@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>Interviewers</div>
                    <div>
                        <a href="{{route('interviewers.create')}}" class="btn btn-primary">Add</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('status'))
                    <div class="alert alert-{{session('statustype')}}" role="alert">
                        {{session('status')}}
                    </div>
                @endif
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Name</th>
                            <th>Barangay</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $key => $item)
                        <tr>
                            <td scope="row">{{$key+1}}</td>
                            <td>{{$item->lname.", ".$item->fname." ".$item->mname}}</td>
                            <td>{{(!is_null($item->brgy_id)) ? $item->brgy->brgyName : "N/A"}}</td>
                            <td>{{$item->desc}}</td>
                            <td><a href="interviewers/{{$item->id}}/edit" class="btn btn-primary">Edit</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection