@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>Mortality Search Result</b></div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>System ID</th>
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Place of Residence</th>
                            <th>Date of Death</th>
                            <th>Place of Death</th>
                            <th>Encoded by/at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $d)
                        <tr>
                            <td class="text-center">{{$d->id}}</td>
                            <td>{{$d->getName()}}</td>
                            <td class="text-center">{{Carbon\Carbon::parse($d->bdate)->format('m/d/Y')}}</td>
                            <td class="text-center">{{$d->getPlaceOfResidence()}}</td>
                            <td class="text-center">{{Carbon\Carbon::parse($d->date_died)->format('m/d/Y (D)')}}</td>
                            <td class="text-center">{{$d->getPlaceOfDeathString()}}</td>
                            <td class="text-center">
                                <div>{{$d->user->name}}</div>
                                <div>{{Carbon\Carbon::parse($d->created_at)->format('m/d/Y h:i A')}}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection