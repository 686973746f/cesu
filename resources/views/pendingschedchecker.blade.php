@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Pending Swab Schedule</div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Pa-swab Pending Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$paswabctr}}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Pending Swab Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arr as $i)
                        <tr>
                            <td>{{$i['date']}}</td>
                            <td>{{$i['count']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="font-weight-bold">
                        <tr>
                            <td>TOTAL</td>
                            <td>{{$total}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection