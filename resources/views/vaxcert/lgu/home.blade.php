@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Vaccine Certificate - LGU</b></div>
                    <div><a href="{{route('vaxcertlgu_create')}}" class="btn btn-success">Create </a></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if($list->count() != 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Age/Sex</th>
                                <th>Address</th>
                                <th>Created at/by</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $d)
                            <tr>
                                <td class="text-center">{{$d->id}}</td>
                                <td><a href="#">{{$d->getName()}}</a></td>
                                <td class="text-center">{{$d->getAge()}}/{{$d->gender}}</td>
                                <td class="text-center">{{$d->getFullAddress()}}</td>
                                <td class="text-center">
                                    <div>{{Carbon\Carbon::parse($d->created_at)->format('M. d, Y h:i A')}}</div>
                                    <div>by {{$d->user->name}}</div>
                                </td>
                                <td class="text-center"><a href="{{route('vaxcertlgu_print', $d->id)}}" class="btn btn-primary">Print</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center">Currently Empty.</p>
                @endif
            </div>
        </div>
    </div>
@endsection