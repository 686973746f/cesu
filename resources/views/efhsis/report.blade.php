@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>eFHSIS Report</b></div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Morb</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mort_list as $m)
                        <tr>
                            <td scope="row"></td>
                            <td>{{$m['DISEASE']}}</td>
                            <td></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection