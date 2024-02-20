@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Daily Reporting Summary</b></div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th>
                            <h6><b>{{date('M d, Y')}}</b></h6>
                            <h6><i>{{date('l')}}</i></h6>
                        </th>
                        <th>OPD</th>
                        <th>ER</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>OLD</b></td>
                        <td class="text-center">{{$opd_old}}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td><b>NEW</b></td>
                        <td class="text-center">{{$opd_new}}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td><b>POLICE</b></td>
                        <td class="text-center">{{$opd_police}}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td><b>TOTAL</b></td>
                        <td class="text-center">{{$opd_old + $opd_new + $opd_police}}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td>THOC</td>
                        <td class="text-center">{{$opd_thoc}}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td>Admission</td>
                        <td class="text-center"></td>
                        <td>Discharged</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td>In-Patient</td>
                        <td class="text-center"></td>
                        <td>DOA</td>
                        <td class="text-center"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection