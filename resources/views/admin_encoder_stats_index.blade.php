@extends('layouts.app')

@section('content')
@php
$gt_covid = 0;
$gt_abtc = 0;
$gt_abtc_ff = 0;
$gt_vaxcert = 0;
$gt_opd = 0;
$gt_lcr = 0;
$gt_edcs = 0;
@endphp
<div class="container">
    <div class="card">
        <div class="card-header font-weight-bold">Encoder Statistics for {{(request()->input('date')) ? date('m/d/Y', strtotime(request()->input('date'))) : date('m/d/Y, h:i A')}}</div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="input-group mb-3">
                    <input type="date" class="form-control" name="date" id="date" value="{{(request()->input('date')) ? request()->input('date') : date('Y-m-d')}}" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" type="submit"><i class="fas fa-calendar-alt mr-2"></i>Date Search</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>COVID-19</th>
                            <th>ABTC (New)</th>
                            <th>ABTC (FFup)</th>
                            <th>VaxCert Concerns</th>
                            <th>OPD</th>
                            <th>LCR Livebirths</th>
                            <th>Imports from EDCS-IS</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arr as $i) 
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td><b>{{mb_strtoupper($i['name'])}}</b></td>
                            <td class="text-center">{{$i['covid_count_final']}}</td>
                            <td class="text-center">{{$i['abtc_count']}}</td>
                            <td class="text-center">{{$i['abtc_ffup_gtotal']}}</td>
                            <td class="text-center">{{$i['vaxcert_count']}}</td>
                            <td class="text-center">{{$i['opd_count']}}</td>
                            <td class="text-center">{{$i['lcr_livebirth']}}</td>
                            <td class="text-center">{{$i['edcs_count']}}</td>
                            <td class="text-center font-weight-bold">{{$i['covid_count_final'] + $i['abtc_count'] + $i['vaxcert_count'] + $i['opd_count'] + $i['abtc_ffup_gtotal'] + $i['lcr_livebirth'] + $i['edcs_count']}}</td>
                        </tr>
                        @php
                        $gt_covid += $i['covid_count_final'];
                        $gt_abtc += $i['abtc_count'];
                        $gt_abtc_ff += $i['abtc_ffup_gtotal'];
                        $gt_vaxcert += $i['vaxcert_count'];
                        $gt_opd += $i['opd_count'];
                        $gt_lcr += $i['lcr_livebirth'];
                        $gt_edcs += $i['edcs_count'];
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="text-center font-weight-bold">
                        <tr>
                            <td colspan="2">TOTAL</td>
                            <td>{{$gt_covid}}</td>
                            <td>{{$gt_abtc}}</td>
                            <td>{{$gt_abtc_ff}}</td>
                            <td>{{$gt_vaxcert}}</td>
                            <td>{{$gt_opd}}</td>
                            <td>{{$gt_lcr}}</td>
                            <td>{{$gt_edcs}}</td>
                            <td>{{$gt_covid + $gt_abtc + $gt_vaxcert + $gt_opd + $gt_abtc_ff + $gt_lcr + $gt_edcs}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection