@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">Threshold for <b>{{$s}}, Year {{request()->input('year')}}</b></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                @for($i=1;$i<=$compa;$i++)
                                <th>{{$i}}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @for($i=1;$i<=$compa;$i++)
                                <td class="text-center">{{$arr["mw$i"]}}</td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection