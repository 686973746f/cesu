@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="font-weight-bold">Interviewers</div>
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
                <table class="table table-bordered">
                    <thead class="text-center bg-light">
                        <tr>
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
                            <td style="vertical-align: middle;" class="text-center" scope="row">{{$key+1}}</td>
                            <td style="vertical-align: middle;">{{$item->lname.", ".$item->fname." ".$item->mname}}</td>
                            <td class="text-center" style="vertical-align: middle;">{{(!is_null($item->brgy_id)) ? $item->brgy->brgyName : "N/A"}}</td>
                            <td class="text-center" style="vertical-align: middle;">{{$item->desc}}</td>
                            <td class="text-center" style="vertical-align: middle;"><a href="interviewers/{{$item->id}}/edit" class="btn btn-primary">Edit</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection