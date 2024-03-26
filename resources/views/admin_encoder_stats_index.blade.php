@extends('layouts.app')

@section('content')
@php
$gt_suspected = 0;
$gt_confirmed = 0;
$gt_negative = 0;
$gt_recovered = 0;
$gt_abtc = 0;
$gt_abtc_ff = 0;
$gt_vaxcert = 0;
$gt_opd = 0;
$gt_lcr = 0;
@endphp
<div class="container">
    <div class="card">
        <div class="card-header font-weight-bold">Encoder Statistics for {{date('m/d/Y, h:i A')}}</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Suspected/Probable</th>
                            <th>Confirmed</th>
                            <th>Recovered</th>
                            <th>Negative Result</th>
                            <th>ABTC (New)</th>
                            <th>ABTC (FFup)</th>
                            <th>VaxCert Concerns</th>
                            <th>OPD</th>
                            <th>LCR Livebirths</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arr as $i) 
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$i['name']}}</td>
                            <td class="text-center">{{$i['suspected_count']}}</td>
                            <td class="text-center">{{$i['confirmed_count']}}</td>
                            <td class="text-center">{{$i['recovered_count']}}</td>
                            <td class="text-center">{{$i['negative_count']}}</td>
                            <td class="text-center">{{$i['abtc_count']}}</td>
                            <td class="text-center">{{$i['abtc_ffup_gtotal']}}</td>
                            <td class="text-center">{{$i['vaxcert_count']}}</td>
                            <td class="text-center">{{$i['opd_count']}}</td>
                            <td class="text-center">{{$i['lcr_livebirth']}}</td>
                            <td class="text-center font-weight-bold">{{$i['suspected_count'] + $i['confirmed_count'] + $i['negative_count'] + $i['recovered_count'] + $i['abtc_count'] + $i['vaxcert_count'] + $i['opd_count'] + $i['abtc_ffup_gtotal'] + $i['lcr_livebirth']}}</td>
                        </tr>
                        @php
                        $gt_suspected += $i['suspected_count'];
                        $gt_confirmed += $i['confirmed_count'];
                        $gt_negative += $i['negative_count'];
                        $gt_recovered += $i['recovered_count'];
                        $gt_abtc += $i['abtc_count'];
                        $gt_abtc_ff += $i['abtc_ffup_gtotal'];
                        $gt_vaxcert += $i['vaxcert_count'];
                        $gt_opd += $i['opd_count'];
                        $gt_lcr += $i['lcr_livebirth'];
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot class="text-center font-weight-bold">
                        <tr>
                            <td colspan="2">TOTAL</td>
                            <td>{{$gt_suspected}}</td>
                            <td>{{$gt_confirmed}}</td>
                            <td>{{$gt_recovered}}</td>
                            <td>{{$gt_negative}}</td>
                            <td>{{$gt_abtc}}</td>
                            <td>{{$gt_abtc_ff}}</td>
                            <td>{{$gt_vaxcert}}</td>
                            <td>{{$gt_opd}}</td>
                            <td>{{$gt_lcr}}</td>
                            <td>{{$gt_suspected + $gt_confirmed + $gt_negative + $gt_recovered + $gt_abtc + $gt_vaxcert + $gt_opd + $gt_abtc_ff + $gt_lcr}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection