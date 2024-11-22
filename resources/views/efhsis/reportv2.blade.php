@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>FHSIS Report v2</b></div>
            <div class="card-body">
                <div class="text-center mb-5">
                    <h2 class="text-success"><b>GENERAL TRIAS CITY</b></h2>
                    @if($brgy != 'ALL')
                    <h2><b>BARANGAY: {{mb_strtoupper($brgy)}}</b></h2>
                    @endif
                    <h3><b>{{date('M. d, Y', strtotime(request()->input('startDate')))}}</b> to <b>{{date('M. d, Y', strtotime(request()->input('endDate')))}}</b></h3>
                </div>
                <div class="row mb-3">
                    <div class="col-4 text-center">
                        <h4><b>Population</b></h4>
                        <h5>{{number_format($data_demographic->total_population)}}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h4><b>Household</b></h4>
                        <h5>{{number_format($data_demographic->total_household)}}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h4><b>{{Str::plural('Barangay', $data_demographic->total_brgy)}}</b></h4>
                        <h5>{{number_format($data_demographic->total_brgy)}}</h5>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>Demographic Profile</b></div>
                    <div class="card-body">
                        @if(Carbon\Carbon::parse(request()->input('startDate'))->format('m') == 12)
                        <table class="table table-bordered table-striped">
                            <tbody class="text-center">
                                <tr>
                                    <td>
                                        <h5 class="font-weight-bold">Main Health Center</h5>
                                        <h5>{{number_format($data_demographic->total_mainhc)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">City Health Centers</h5>
                                        <h5>{{number_format($data_demographic->total_cityhc)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Rural Health Units</h5>
                                        <h5>{{number_format($data_demographic->total_ruralhc)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Barangay Health Stations (BHS)</h5>
                                        <h5>{{number_format($data_demographic->total_bhs)}}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="font-weight-bold">Physician/Doctors</h5>
                                        <h5>{{number_format($data_demographic->doctors_lgu + $data_demographic->doctors_doh)}} {{(($data_demographic->doctors_lgu + $data_demographic->doctors_doh) != 0) ? ': '.number_format($data_demographic->total_population / ($data_demographic->doctors_lgu + $data_demographic->doctors_doh)) : ''}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Dentists</h5>
                                        <h5>{{number_format($data_demographic->dentists_lgu + $data_demographic->dentists_doh)}} {{(($data_demographic->dentists_lgu + $data_demographic->dentists_doh) != 0) ? ': '.number_format($data_demographic->total_population / ($data_demographic->dentists_lgu + $data_demographic->dentists_doh)) : ''}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Nurses</h5>
                                        <h5>{{number_format($data_demographic->nurses_lgu + $data_demographic->nurses_doh)}} {{(($data_demographic->nurses_lgu + $data_demographic->nurses_doh) != 0) ? ': '.number_format($data_demographic->total_population / ($data_demographic->nurses_lgu + $data_demographic->nurses_doh)) : ''}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Midwives</h5>
                                        <h5>{{number_format($data_demographic->midwifes_lgu + $data_demographic->midwifes_doh)}} {{(($data_demographic->midwifes_lgu + $data_demographic->midwifes_doh) != 0) ? ': '.number_format($data_demographic->total_population / ($data_demographic->midwifes_lgu + $data_demographic->midwifes_doh)) : ''}}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="font-weight-bold">Medical Technologist</h5>
                                        <h5>{{number_format($data_demographic->medtechs_lgu + $data_demographic->medtechs_doh)}} {{(($data_demographic->medtechs_lgu + $data_demographic->medtechs_doh) != 0) ? ': '.number_format($data_demographic->total_population / ($data_demographic->medtechs_lgu + $data_demographic->medtechs_doh)) : ''}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Nutritionist Dietitians</h5>
                                        <h5>{{number_format($data_demographic->nutritionists_lgu + $data_demographic->nutritionists_doh)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Sanitary Engineers</h5>
                                        <h5>{{number_format($data_demographic->sanitary_eng_lgu + $data_demographic->sanitary_eng_doh)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Sanitary Inspectors</h5>
                                        <h5>{{number_format($data_demographic->sanitary_ins_lgu + $data_demographic->sanitary_ins_doh)}}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <h5 class="font-weight-bold">Active BHWs</h5>
                                        <h5>{{number_format($data_demographic->bhws_lgu + $data_demographic->bhws_doh)}} {{(($data_demographic->bhws_lgu + $data_demographic->bhws_doh) != 0) ? ': '.number_format($data_demographic->total_population / ($data_demographic->bhws_lgu + $data_demographic->bhws_doh)) : ''}}</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        @else
                        <h6 class="text-center">Selected month is not December.</h6>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><b>Morbidity and Mortality</b></div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tbody class="text-center">
                                @php
                                
                                @endphp
                                <tr>
                                    @if(request()->input('brgy') == 'ALL')
                                    <td colspan="2">
                                        <h5 class="font-weight-bold">Livebirths (LCR)</h5>
                                        <h5>{{number_format($gtot_livebirths)}}</h5>
                                    </td>
                                    <td colspan="2">
                                        <h5 class="font-weight-bold">
                                            <div>Livebirths outside GenTri</div>
                                            <small>(Gave birth to Hospitals or Lying-in Clinics in City of GenTri but a resident outside of GenTri)</small>
                                        </h5>
                                        <h5>{{number_format($gtot_livebirths_outside)}}</h5>
                                    </td>
                                    @else
                                    <td colspan="4">
                                        <h5 class="font-weight-bold">Livebirths (LCR)</h5>
                                        <h5>{{number_format($gtot_livebirths)}}</h5>
                                    </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="font-weight-bold">Total Deaths</h5>
                                        <h5>{{number_format($gtot_deaths)}}</h5>
                                        <h6>Male: {{$tot_deaths_m}} Female: {{$tot_deaths_f}}</h6>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Infant Deaths</h5>
                                        <h5>{{number_format($gtot_infdeaths)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Maternal Deaths</h5>
                                        <h5>{{number_format($gtot_matdeaths)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Under-Five Deaths</h5>
                                        <h5>{{number_format($gtot_und5deaths)}}</h5>
                                    </td>
                                </tr>
                                <tr>     
                                    <td>
                                        <h5 class="font-weight-bold">Mortality Rate</h5>
                                        <h5>{{$mortality_rate}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">IMR</h5>
                                        <h5>{{$imr}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">MMR</h5>
                                        <h5>{{$mmr}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">UFMR</h5>
                                        <h5>{{$ufmr}}</h5>
                                    </td>
                                </tr>
                                <tr>     
                                    <td>
                                        <h5 class="font-weight-bold">Neonatal Death</h5>
                                        <h5>{{number_format($gtot_neonataldeaths)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Early Naonatal Death</h5>
                                        <h5>{{number_format($gtot_earlyneonataldeaths)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Perinatal Death</h5>
                                        <h5>{{number_format($gtot_perinataldeaths)}}</h5>
                                    </td>
                                    <td rowspan="2" style="vertical-align: middle;">
                                        <h5 class="font-weight-bold">Originating Maternal Death</h5>
                                        <h5>{{number_format($gtot_matorigdeaths)}}</h5>
                                    </td>
                                </tr>
                                <tr>     
                                    <td>
                                        <h5 class="font-weight-bold">Neonatal Mortality Rate</h5>
                                        <h5>{{$neomort_rate}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Fetal Death</h5>
                                        <h5>{{number_format($gtot_fetaldeaths)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Perinatal Mortality Rate</h5>
                                        <h5>{{$perimort_rate}}</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h5>Total Deaths occured inside the City but the Patient Adress was Outside the City = {{$tot_deaths_outsidecity_m + $tot_deaths_outsidecity_f}} (Male: {{$tot_deaths_outsidecity_m}} Female: {{$tot_deaths_outsidecity_f}})</h5>
                        <div class="row">
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-sm" id="mortable">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th colspan="5">Leading Causes of Mortality</th>
                                            </tr>
                                            <tr>
                                                <th>No.</th>
                                                <th>Mortality</th>
                                                <th style="color: blue;">Male</th>
                                                <th style="color: magenta;">Female</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mort_final_list as $m)
                                            <tr>
                                                <td scope="row" class="text-center"></td>
                                                <td>{{$m['disease']}}</td>
                                                <td class="text-center" style="color: blue;">{{$m['count_male']}}</td>
                                                <td class="text-center" style="color: magenta;">{{$m['count_female']}}</td>
                                                <td class="text-center"><b>{{$m['count']}}</b></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-sm" id="morbtable">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th colspan="5">Leading Causes of Morbidity</th>
                                            </tr>
                                            <tr>
                                                <th>No.</th>
                                                <th>Morbidity</th>
                                                <th style="color: blue;">Male</th>
                                                <th style="color: magenta;">Female</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($morb_final_list as $m)
                                            <tr>
                                                <td scope="row" class="text-center"></td>
                                                <td>{{$m['disease']}}</td>
                                                <td class="text-center" style="color: blue;">{{$m['count_male']}}</td>
                                                <td class="text-center" style="color: magenta;">{{$m['count_female']}}</td>
                                                <td class="text-center"><b>{{$m['count']}}</b></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <h4><b>Underfive Deaths ≥ Infant Deaths</b></h4>
                                @if(($gtot_und5deaths + $gtot_infdeaths) != 0)
                                <canvas id="donut1" style="width: 500px;"></canvas>
                                @else
                                <p class="text-center my-5">Chart Data is empty.</p>
                                @endif
                            </div>
                            <div class="col-6">
                                <h4><b>Infant Deaths (ND + END + FD)</b></h4>
                                @if(($gtot_infdeaths + ($gtot_neonataldeaths + $gtot_earlyneonataldeaths + $gtot_fetaldeaths)) != 0)
                                <canvas id="donut2" style="width: 500px;"></canvas>
                                @else
                                <p class="text-center my-5">Chart Data is empty.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#mortable, #morbtable').dataTable({
            iDisplayLength: 5,
            'dom': 't',
            'orderFixed': {
                'pre': [[4, 'desc']]
            },
            'drawCallback': function(settings) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart;
                api.column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = startIndex + i + 1;
                });
            }
        });

        @if(($gtot_und5deaths + $gtot_infdeaths) != 0)
        var pieTitles = {!! json_encode($donut1_titles) !!};
        var pieDatas = {!! json_encode($donut1_values) !!};

        var ctx = document.getElementById('donut1').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'doughnut',

            data: {
                labels: pieTitles,
                datasets: [{
                    label: "My Chart",
                    data: pieDatas,
                    //backgroundColor: ['rgba(148,55,52,255)', 'rgba(119,146,61,255)', 'rgba(166,167,167,255)']
                }]
            },

            options: {
                responsive: false,
                title: {
                    text: "My Chart",
                    display: true,
                },
                events: [],
                tooltips: {
                    mode: ''
                },
                layout: {},
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    },
                    datalabels: {
                    display: true,
                    formatter: (value, ctx) => {
                        const datapoints = ctx.chart.data.datasets[0].data
                        const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                        const percentage = value / total * 100
                        return value + " (" + percentage.toFixed(0) + "%)";
                    },
                    }
                },
                animation: {}
            }
        });
        @endif

        @if(($gtot_infdeaths + ($gtot_neonataldeaths + $gtot_earlyneonataldeaths + $gtot_fetaldeaths)) != 0)
        var pieTitles = {!! json_encode($donut2_titles) !!};
        var pieDatas = {!! json_encode($donut2_values) !!};

        var ctx = document.getElementById('donut2').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'doughnut',

            data: {
                labels: pieTitles,
                datasets: [{
                    label: "My Chart",
                    data: pieDatas,
                    //backgroundColor: ['rgba(148,55,52,255)', 'rgba(119,146,61,255)', 'rgba(166,167,167,255)']
                }]
            },

            options: {
                responsive: false,
                title: {
                    text: "My Chart",
                    display: true,
                },
                events: [],
                tooltips: {
                    mode: ''
                },
                layout: {},
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    },
                    datalabels: {
                    display: true,
                    formatter: (value, ctx) => {
                        const datapoints = ctx.chart.data.datasets[0].data
                        const total = datapoints.reduce((total, datapoint) => total + datapoint, 0)
                        const percentage = value / total * 100
                        return value + " (" + percentage.toFixed(0) + "%)";
                    },
                    }
                },
                animation: {}
            }
        });
        @endif
    </script>
@endsection