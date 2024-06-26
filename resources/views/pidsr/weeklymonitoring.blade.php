@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>Weekly Submission Checker</b></div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Facility 2024</th>
                            @for($i=1; $i <= $maxweek; $i++)
                            <th>MW{{$i}}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($final_array as $ind => $i)
                        <tr>
                            <td class="text-center">{{$ind + 1}}</td>
                            <td><b>{{$i['name']}}</b></td>
                            @foreach($i['weeks'] as $w)
                            @php
                            if($w == '✔') {
                                $text_color = 'bg-success';
                            }
                            else {
                                $text_color = 'bg-warning';
                            }
                            @endphp
                            <td class="text-center {{$text_color}}">{{$w}}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection