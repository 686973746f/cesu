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
                    <h3>{{date('M d, Y', strtotime(request()->input('startDate')))}} to {{date('M d, Y', strtotime(request()->input('endDate')))}}</h3>
                </div>
                <div class="row mb-3">
                    <div class="col-4 text-center">
                        <h4>Population</h4>
                        <h5>{{number_format($data_demographic->total_population)}}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h4>Household</h4>
                        <h5>{{number_format($data_demographic->total_household)}}</h5>
                    </div>
                    <div class="col-4 text-center">
                        <h4>Barangays</h4>
                        <h5>{{number_format($data_demographic->total_brgy)}}</h5>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>Demographic Profile</b></div>
                    <div class="card-body">
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
                                        <h5>{{number_format($data_demographic->doctors_male + $data_demographic->doctors_female)}} : {{number_format($data_demographic->total_population)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Dentists</h5>
                                        <h5>{{number_format($data_demographic->dentists_male + $data_demographic->dentists_female)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Nurses</h5>
                                        <h5>{{number_format($data_demographic->nurses_male + $data_demographic->nurses_female)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Midwives</h5>
                                        <h5>{{number_format($data_demographic->midwifes_male + $data_demographic->midwifes_female)}}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="font-weight-bold">Medical Technologist</h5>
                                        <h5>{{number_format($data_demographic->medtechs_male + $data_demographic->medtechs_female)}} : {{number_format($data_demographic->total_population)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Nutritionist Dietitians</h5>
                                        <h5>{{number_format($data_demographic->nutritionists_male + $data_demographic->nutritionists_female)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Sanitary Engineers</h5>
                                        <h5>{{number_format($data_demographic->sanitary_eng_male + $data_demographic->sanitary_eng_female)}}</h5>
                                    </td>
                                    <td>
                                        <h5 class="font-weight-bold">Sanitary Inspectors</h5>
                                        <h5>{{number_format($data_demographic->sanitary_ins_male + $data_demographic->sanitary_ins_female)}}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <h5 class="font-weight-bold">Active BHWs</h5>
                                        <h5>{{number_format($data_demographic->bhws_male + $data_demographic->bhws_female)}}</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><b>Morbidity and Mortality</b></div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tbody class="text-center">
                                <tr>
                                    <td colspan="4">
                                        <h5 class="font-weight-bold">Livebirths (LCR)</h5>
                                        <h5>{{number_format($gtot_livebirths)}}</h5>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5 class="font-weight-bold">Total Deaths</h5>
                                        <h5>{{number_format($gtot_deaths)}}</h5>
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

                        <div class="row">
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="mortable">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th colspan="5">Leading Causes of Mortality</th>
                                            </tr>
                                            <tr>
                                                <th>No.</th>
                                                <th>Mortality</th>
                                                <th style="color: blue;">Male</th>
                                                <th style="color: red;">Female</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mort_final_list as $m)
                                            <tr>
                                                <td scope="row" class="text-center"></td>
                                                <td>{{$m['disease']}}</td>
                                                <td class="text-center" style="color: blue;">{{$m['count_male']}}</td>
                                                <td class="text-center" style="color: red;">{{$m['count_female']}}</td>
                                                <td class="text-center"><b>{{$m['count']}}</b></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="morbtable">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th colspan="5">Leading Causes of Morbidity</th>
                                            </tr>
                                            <tr>
                                                <th>No.</th>
                                                <th>Morbidity</th>
                                                <th style="color: blue;">Male</th>
                                                <th style="color: red;">Female</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($morb_final_list as $m)
                                            <tr>
                                                <td scope="row" class="text-center"></td>
                                                <td>{{$m['disease']}}</td>
                                                <td class="text-center" style="color: blue;">{{$m['count_male']}}</td>
                                                <td class="text-center" style="color: red;">{{$m['count_female']}}</td>
                                                <td class="text-center"><b>{{$m['count']}}</b></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
    </script>
@endsection