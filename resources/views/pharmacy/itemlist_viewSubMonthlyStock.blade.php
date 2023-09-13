@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>Stock Monthly View</b></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2">Item</th>
                                @foreach($month_array as $m)
                                <th colspan="2">{{$m['month']}}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($month_array as $m)
                                <th>+</th>
                                <th>-</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$d->pharmacysupplymaster->name}}</td>
                                @foreach($month_array as $m)
                                <td>{{$m['received_count']}}</td>
                                <td>{{$m['issued_count']}}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection