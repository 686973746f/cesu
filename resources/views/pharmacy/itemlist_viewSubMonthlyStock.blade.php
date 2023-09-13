@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>Stock Monthly View</b> | Sub-Item ID: #{{$d->id}}</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="26">Year {{date('Y')}}</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Item</th>
                                <th rowspan="2">Current Stock</th>
                                @foreach($month_array as $m)
                                <th colspan="2">{{$m['month']}}</th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach($month_array as $m)
                                <th class="text-success">RECEIVED</th>
                                <th class="text-danger">ISSUED</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>{{$d->pharmacysupplymaster->name}}</b></td>
                                <td>{{$d->displayQty()}}</td>
                                @foreach($month_array as $m)
                                <td class="{{($m['received_count'] != 0) ? 'text-success' : ''}}">{{($m['received_count'] != 0) ? '+ '.$m['received_count'] : '0'}}</td>
                                <td class="{{($m['issued_count'] != 0) ? 'text-danger' : ''}}">{{($m['issued_count'] != 0) ? '- '.$m['issued_count'] : '0'}}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection