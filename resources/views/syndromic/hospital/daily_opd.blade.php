@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Daily Reporting Summary</b></div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="input-group mb-3">
                    <input type="date" class="form-control" name="d" id="d" value="{{(request()->input('d')) ? request()->input('d') : date('Y-m-d')}}" max="{{date('Y-m-d')}}" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" type="submit"><i class="fas fa-calendar-alt mr-2"></i>Date Search</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th>
                            <h6><b>{{date('M d, Y', strtotime((request()->input('d')) ? request()->input('d') : date('Y-m-d')))}}</b></h6>
                            <h6><i>{{date('l', strtotime((request()->input('d')) ? request()->input('d') : date('Y-m-d')))}}</i></h6>
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
                        <td class="text-center">{{$er_old}}</td>
                        <td class="text-center font-weight-bold">{{$opd_old + $er_old}}</td>
                    </tr>
                    <tr>
                        <td><b>NEW</b></td>
                        <td class="text-center">{{$opd_new}}</td>
                        <td class="text-center">{{$er_new}}</td>
                        <td class="text-center font-weight-bold">{{$opd_new + $er_new}}</td>
                    </tr>
                    <tr>
                        <td><b>POLICE</b></td>
                        <td class="text-center">{{$opd_police}}</td>
                        <td class="text-center">{{$er_police}}</td>
                        <td class="text-center font-weight-bold">{{$opd_police + $er_police}}</td>
                    </tr>
                    <tr>
                        <td><b>TOTAL</b></td>
                        <td class="text-center">{{$opd_old + $opd_new + $opd_police}}</td>
                        <td class="text-center">{{$er_old + $er_new + $er_police}}</td>
                        <td class="text-center font-weight-bold">{{$opd_old + $opd_new + $opd_police + $er_old + $er_new + $er_police}}</td>
                    </tr>
                    <tr>
                        <td>THOC</td>
                        <td class="text-center">{{$opd_thoc}}</td>
                        <td class="text-center">{{$er_thoc}}</td>
                        <td class="text-center font-weight-bold">{{$opd_thoc + $er_thoc}}</td>
                    </tr>
                    <tr>
                        <td>Admission</td>
                        <td>{{$admission}}</td>
                        <td>Discharged</td>
                        <td>{{$discharged}}</td>
                    </tr>
                    <tr>
                        <td>In-Patient</td>
                        <td>{{$inpatient}}</td>
                        <td>DOA</td>
                        <td>{{$doa}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection