@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Laboratory Logbook</b></div>
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId">New Lab Result</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if($list->count() != 0)
                <table class="table table-bordered">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Disease</th>
                            <th>Name</th>
                            <th>Age/Sex</th>
                            <th>Address</th>
                            <th>Date Swab Collected</th>
                            <th>Type</th>
                            <th>Result</th>
                            <th>Encoded by/at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$list->firstItem() + $ind}}</td>
                            <td class="text-center"><a href="">{{$l->disease_tag}}</a></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination justify-content-center mt-3">
                    {{$list->appends(request()->input())->links()}}
                </div>
                @else
                <p class="text-center">Results is currently empty.</p>
                @endif
            </div>
        </div>
    </div>
@endsection