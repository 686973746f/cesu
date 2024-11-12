@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>FHSIS</b></div>
            <div class="card-body">
                <div class="text-center">
                    <h4><b>CHILD CARE PROGRAM</b></h4>
                    <h5>{{$startDate->format('M. d, Y')}} to {{$endDate->endOfMonth()->format('M. d, Y')}}</h5>
                    <h5>GENERAL TRIAS, CAVITE</h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th rowspan="2">Area</th>
                                <th rowspan="2">Elig. Pop</th>
                                <th colspan="4">Child Protected at Birth (CPAB)</th>
                                <th colspan="4">BCG</th>
                                <th colspan="4">HepB within 24 hours</th>
                                <th colspan="4">DPT-HiB-HepB 1</th>
                                <th colspan="4">DPT-HiB-HepB 2</th>
                                <th colspan="4">DPT-HiB-HepB 3</th>
                            </tr>
                            <tr>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cc_arr1 as $r)
                            <tr>
                                <td>{{$r['brgy']}}</td>
                                <td class="text-center">{{$r['elig_pop']}}</td>
                                <td class="text-center">{{$r['cpb_m']}}</td>
                                <td class="text-center">{{$r['cpb_f']}}</td>
                                <td class="text-center">{{$r['cpb_total']}}</td>
                                <td class="text-center">{{$r['cpb_percent']}}%</td>

                                <td class="text-center">{{$r['bcg_m']}}</td>
                                <td class="text-center">{{$r['bcg_f']}}</td>
                                <td class="text-center">{{$r['bcg_total']}}</td>
                                <td class="text-center">{{$r['bcg_percent']}}%</td>

                                <td class="text-center">{{$r['hepabwin24_m']}}</td>
                                <td class="text-center">{{$r['hepabwin24_f']}}</td>
                                <td class="text-center">{{$r['hepabwin24_total']}}</td>
                                <td class="text-center">{{$r['hepabwin24_percent']}}%</td>

                                <td class="text-center">{{$r['dpt1_m']}}</td>
                                <td class="text-center">{{$r['dpt1_f']}}</td>
                                <td class="text-center">{{$r['dpt1_total']}}</td>
                                <td class="text-center">{{$r['dpt1_percent']}}%</td>

                                <td class="text-center">{{$r['dpt2_m']}}</td>
                                <td class="text-center">{{$r['dpt2_f']}}</td>
                                <td class="text-center">{{$r['dpt2_total']}}</td>
                                <td class="text-center">{{$r['dpt2_percent']}}%</td>

                                <td class="text-center">{{$r['dpt3_m']}}</td>
                                <td class="text-center">{{$r['dpt3_f']}}</td>
                                <td class="text-center">{{$r['dpt3_total']}}</td>
                                <td class="text-center">{{$r['dpt3_percent']}}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th rowspan="2">Area</th>
                                <th rowspan="2">
                                    <div>Elig. Pop.</div>
                                    <div>(Under 1 year old)</div>
                                </th>
                                <th colspan="4">IPV 1</th>
                                <th colspan="4">IPV 2 (Routine)</th>
                                <th rowspan="2">
                                    <div>Elig. Pop.</div>
                                    <div>(0-23 months old)</div>
                                </th>
                                <th colspan="4">IPV 2 (Catch-up)</th>
                            </tr>
                            <tr>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cc_arr2 as $r)
                            <tr>
                                <td>{{$r['brgy']}}</td>
                                <td class="text-center">{{$r['elig_pop']}}</td>
                                <td class="text-center">{{$r['ipv1_m']}}</td>
                                <td class="text-center">{{$r['ipv1_f']}}</td>
                                <td class="text-center">{{$r['ipv1_total']}}</td>
                                <td class="text-center">{{$r['ipv1_percent']}}</td>
                                <td class="text-center">{{$r['ipv2_m']}}</td>
                                <td class="text-center">{{$r['ipv2_f']}}</td>
                                <td class="text-center">{{$r['ipv2_total']}}</td>
                                <td class="text-center">{{$r['ipv2_percent']}}</td>
                                <td class="text-center">{{$r['elig_pop2']}}</td>
                                <td class="text-center">{{$r['ipv3_m']}}</td>
                                <td class="text-center">{{$r['ipv3_f']}}</td>
                                <td class="text-center">{{$r['ipv3_total']}}</td>
                                <td class="text-center">{{$r['ipv3_percent']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th rowspan="2">Area</th>
                                <th rowspan="2">
                                    <div>Elig. Pop.</div>
                                    <div>(Under 1 year old)</div>
                                </th>
                                <th colspan="4">OPV 1</th>
                                <th colspan="4">OPV 2</th>
                                <th colspan="4">OPV 3</th>
                                <th colspan="4">PCV 1</th>
                                <th colspan="4">PCV 2</th>
                                <th colspan="4">PCV 3</th>
                            </tr>
                            <tr>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cc_arr3 as $r)
                            <tr>
                                <td>{{$r['brgy']}}</td>
                                <td class="text-center">{{$r['elig_pop']}}</td>
                                <td class="text-center">{{$r['opv1_m']}}</td>
                                <td class="text-center">{{$r['opv1_f']}}</td>
                                <td class="text-center">{{$r['opv1_total']}}</td>
                                <td class="text-center">{{$r['opv1_percent']}}</td>
                                <td class="text-center">{{$r['opv2_m']}}</td>
                                <td class="text-center">{{$r['opv2_f']}}</td>
                                <td class="text-center">{{$r['opv2_total']}}</td>
                                <td class="text-center">{{$r['opv2_percent']}}</td>
                                <td class="text-center">{{$r['opv3_m']}}</td>
                                <td class="text-center">{{$r['opv3_f']}}</td>
                                <td class="text-center">{{$r['opv3_total']}}</td>
                                <td class="text-center">{{$r['opv3_percent']}}</td>
                                <td class="text-center">{{$r['pcv1_m']}}</td>
                                <td class="text-center">{{$r['pcv1_f']}}</td>
                                <td class="text-center">{{$r['pcv1_total']}}</td>
                                <td class="text-center">{{$r['pcv1_percent']}}</td>
                                <td class="text-center">{{$r['pcv2_m']}}</td>
                                <td class="text-center">{{$r['pcv2_f']}}</td>
                                <td class="text-center">{{$r['pcv2_total']}}</td>
                                <td class="text-center">{{$r['pcv2_percent']}}</td>
                                <td class="text-center">{{$r['pcv3_m']}}</td>
                                <td class="text-center">{{$r['pcv3_f']}}</td>
                                <td class="text-center">{{$r['pcv3_total']}}</td>
                                <td class="text-center">{{$r['pcv3_percent']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th rowspan="2">Area</th>
                                <th rowspan="2">
                                    <div>Elig. Pop</div>
                                    <div>Under 1 year old</div>
                                </th>
                                <th rowspan="2">
                                    <div>Elig. Pop</div>
                                    <div>0-12 months old</div>
                                </th>
                                <th colspan="4">MCV 1 *</th>
                                <th colspan="4">MCV 2 **</th>
                                <th colspan="4">Fully Immunized Children **</th>
                                <th rowspan="2">
                                    <div>Elig. Pop</div>
                                    <div>13-23 months old</div>
                                </th>
                                <th colspan="4">Completely Immunized Children ***</th>
                            </tr>
                            <tr>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                                <th>M</th>
                                <th>F</th>
                                <th>T</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cc_arr4 as $r)
                            <tr>
                                <td>{{$r['brgy']}}</td>
                                <td class="text-center">{{$r['elig_pop']}}</td>
                                <td class="text-center">{{$r['elig_pop3']}}</td>
                                <td class="text-center">{{$r['mcv1_m']}}</td>
                                <td class="text-center">{{$r['mcv1_f']}}</td>
                                <td class="text-center">{{$r['mcv1_total']}}</td>
                                <td class="text-center">{{$r['mcv1_percent']}}</td>
                                <td class="text-center">{{$r['mcv2_m']}}</td>
                                <td class="text-center">{{$r['mcv2_f']}}</td>
                                <td class="text-center">{{$r['mcv2_total']}}</td>
                                <td class="text-center">{{$r['mcv2_percent']}}</td>
                                <td class="text-center">{{$r['fic_m']}}</td>
                                <td class="text-center">{{$r['fic_f']}}</td>
                                <td class="text-center">{{$r['fic_total']}}</td>
                                <td class="text-center">{{$r['fic_percent']}}</td>
                                <td class="text-center">{{$r['elig_pop4']}}</td>
                                <td class="text-center">{{$r['cic_m']}}</td>
                                <td class="text-center">{{$r['cic_f']}}</td>
                                <td class="text-center">{{$r['cic_total']}}</td>
                                <td class="text-center">{{$r['cic_percent']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Area</th>
                                <th>Total Live Births</th>
                                <th>Newborn initiated on breastfeeding immediately after birth lasting to 90 mins.</th>
                                <th>Total Live Births with Low Birth Weight</th>
                                <th>Total Live Births</th>
                                <th>Total Live Births</th>
                                <th>Total Live Births</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td scope="row"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td scope="row"></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection