@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Daily Reporting Summary</b></div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <h6>{{date('M d, Y')}}</h6>
                            <h6>{{date('l')}}</h6>
                        </th>
                        <th>OPD</th>
                        <th>ER</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>OLD</td>
                        <td>{{$opd_old}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>NEW</td>
                        <td>{{$opd_new}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>POLICE</td>
                        <td>{{$opd_police}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>TOTAL</td>
                        <td>{{$opd_old + $opd_new + $opd_police}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>THOC</td>
                        <td>{{$opd_thoc}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Admission</td>
                        <td></td>
                        <td>Discharged</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Admission</td>
                        <td></td>
                        <td>Discharged</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>In-Patient</td>
                        <td></td>
                        <td>DOA</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection