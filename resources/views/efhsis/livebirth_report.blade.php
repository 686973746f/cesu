@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Natality Report</b></div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Year</td>
                        <td>{{$year}}</td>
                    </tr>
                    <tr>
                        <td>Month</td>
                        <td>{{date('F', strtotime($year.'-'.$month.'-01'))}}</td>
                    </tr>
                    <tr>
                        <td>Barangay</td>
                        <td>{{$brgy}}</td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Livebirths</td>
                        <td class="text-center">{{$total_livebirths}}</td>
                    </tr>
                    <tr>
                        <td>Livebirths among 10-14 y/o women</td>
                        <td class="text-center">{{$livebirth1014}}</td>
                    </tr>
                    <tr>
                        <td>Livebirths among 15-19 y/o women</td>
                        <td class="text-center">{{$livebirth1519}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection