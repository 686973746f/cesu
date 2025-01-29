@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>Weekly Submission Checker</b></div>
            <div class="card-body">
                <div class="alert alert-info" role="alert">
                    <b class="text-danger">Note:</b> The system checks submission per hospital every Tuesday, 7AM. Therefore, encoding must be done every Monday.
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Facility {{date('Y')}}</th>
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
                                if($w == 'ZERO CASE') {
                                    $w = '✔Z';
                                }
                                else if($w == 'LATE SUBMISSION') {
                                    $w = 'L';
                                }

                                if(request()->input('simplifiedview')) {
                                    if($w == 'ENCODED BUT NO WEEKLY REPORT') {
                                        $w = 'X';
                                    }
                                }

                                if($w == '✔' || $w == '✔Z') {
                                    $text_color = 'bg-success';
                                }
                                else if($w == 'X') {
                                    $text_color = 'bg-danger';
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

                <ul>
                    Legend
                    <li>✔ - Submitted</li>
                    <li>Z - Zero Case</li>
                    <li>L - Late Submission</li>
                    <li>X - No Submission</li>
                    <li>Submitted but No Report - Encoded a Case/s on EDCS-IS on the particular MW but didn't submitted a Weekly Report</li>
                </ul>
            </div>
        </div>
    </div>
@endsection