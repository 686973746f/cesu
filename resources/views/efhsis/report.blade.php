@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>eFHSIS Report (2023)</b></div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Select Options first to Proceed</span>
                        </div>
                        <select class="custom-select" name="type" id="type" required>
                            <option disabled {{(is_null(request()->input('type'))) ? 'selected' : ''}}>Select Type...</option>
                            <option value="yearly" {{(request()->input('type') == 'yearly') ? 'selected' : ''}}>Yearly</option>
                            <option value="quarterly" {{(request()->input('type') == 'quarterly') ? 'selected' : ''}}>Quarterly</option>
                            <option value="monthly" {{(request()->input('type') == 'monthly') ? 'selected' : ''}}>Monthly</option>
                        </select>
                        <select class="custom-select" name="year" id="year" required>
                            <option disabled {{(is_null(request()->input('year'))) ? 'selected' : ''}}>Select Year...</option>
                            @foreach(range(date('Y'), 2020) as $y)
                            <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                            @endforeach
                        </select>
                        <select class="custom-select d-none" name="quarter" id="quarter">
                            <option disabled {{(is_null(request()->input('quarter'))) ? 'selected' : ''}}>Select Quarter...</option>
                            <option value="1" {{(request()->input('quarter') == '1') ? 'selected' : ''}}>1st Quarter</option>
                            <option value="2" {{(request()->input('quarter') == '2') ? 'selected' : ''}}>2nd Quarter</option>
                            <option value="3" {{(request()->input('quarter') == '3') ? 'selected' : ''}}>3rd Quarter</option>
                            <option value="4" {{(request()->input('quarter') == '4') ? 'selected' : ''}}>4th Quarter</option>
                        </select>
                        <select class="custom-select d-none" name="month" id="month">
                            <option disabled {{(is_null(request()->input('month'))) ? 'selected' : ''}}>Select Month</option>
                            <option value="01" {{(request()->input('month') == '01') ? 'selected' : ''}}>JANUARY</option>
                            <option value="02" {{(request()->input('month') == '02') ? 'selected' : ''}}>FEBRUARY</option>
                            <option value="03" {{(request()->input('month') == '03') ? 'selected' : ''}}>MARCH</option>
                            <option value="04" {{(request()->input('month') == '04') ? 'selected' : ''}}>APRIL</option>
                            <option value="05" {{(request()->input('month') == '05') ? 'selected' : ''}}>MAY</option>
                            <option value="06" {{(request()->input('month') == '06') ? 'selected' : ''}}>JUNE</option>
                            <option value="07" {{(request()->input('month') == '07') ? 'selected' : ''}}>JULY</option>
                            <option value="08" {{(request()->input('month') == '08') ? 'selected' : ''}}>AUGUST</option>
                            <option value="09" {{(request()->input('month') == '09') ? 'selected' : ''}}>SEPTEMBER</option>
                            <option value="10" {{(request()->input('month') == '10') ? 'selected' : ''}}>OCTOBER</option>
                            <option value="11" {{(request()->input('month') == '11') ? 'selected' : ''}}>NOVEMBER</option>
                            <option value="12" {{(request()->input('month') == '12') ? 'selected' : ''}}>DECEMBER</option>
                        </select>
                        <div class="input-group-append">
                          <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>

                @if(request()->input('type') && request()->input('year'))
                <div class="card mb-3">
                    <div class="card-header"><b>M1</b></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="text-center thead-light">
                                    <tr>
                                        <th rowspan="2">Barangay</th>
                                        <th colspan="2">Child Care</th>
                                        <th colspan="2">Family Planning</th>
                                        <th colspan="3">Non-Com</th>
                                        <th rowspan="2">Dental</th>
                                        <th rowspan="2">Environmental</th>
                                    </tr>
                                    <tr>
                                        <th>FIC</th>
                                        <th>CIC</th>
                                        <th>CU</th>
                                        <th>END</th>
                                        <th>RISK ASSESS</th>
                                        <th>PPV</th>
                                        <th>FLU-VACCINE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bgy_mone_list as $b)
                                    <tr>
                                        <td>{{$b['barangay']}}</td>
                                        <td class="text-center">{{$b['fic']}}</td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                        <td class="text-center"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>Mortality and Natality</b></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>Barangay</th>
                                        <th>Population ({{request()->input('year')}})</th>
                                        <th>Live Birth</th>
                                        <th>Total Death</th>
                                        <th>TDR</th>
                                        <th>Maternal Death</th>
                                        <th>MDR</th>
                                        <th>Infant Death</th>
                                        <th>IDR</th>
                                        <th>Under-Five Death</th>
                                        <th>UFDR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $gt_population = 0;
                                    $gt_livebirth = 0;
                                    $gt_tot_death = 0;
                                    $gt_mat_death = 0;
                                    $gt_inf_death = 0;
                                    $gt_unf_death = 0;
                                    @endphp
                                    @foreach($bgy_nm_list as $b)
                                    <tr>
                                        <td>{{$b['barangay']}}</td>
                                        <td class="text-center">{{number_format($b['population'])}}</td>
                                        <td class="text-center">{{$b['livebirth']}}</td>
                                        <td class="text-center">{{$b['tot_death']}}</td>
                                        <td class="text-center">{{round(($b['tot_death'] / $b['population']) * 100, 2)}}</td>
                                        <td class="text-center">{{$b['mat_death']}}</td>
                                        <td class="text-center">{{round(($b['mat_death'] / $b['population']) * 100, 2)}}</td>
                                        <td class="text-center">{{$b['inf_death']}}</td>
                                        <td class="text-center">{{round(($b['inf_death'] / $b['population']) * 100, 2)}}</td>
                                        <td class="text-center">{{$b['unf_death']}}</td>
                                        <td class="text-center">{{round(($b['unf_death'] / $b['population']) * 100, 2)}}</td>
                                    </tr>

                                    @php
                                    $gt_population += $b['population'];
                                    $gt_livebirth += $b['livebirth'];
                                    $gt_tot_death += $b['tot_death'];
                                    $gt_mat_death += $b['mat_death'];
                                    $gt_inf_death += $b['inf_death'];
                                    $gt_unf_death += $b['unf_death'];
                                    @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-center"><b>GRAND TOTAL</b></td>
                                        <td class="text-center"><b>{{$gt_population}}</b></td>
                                        <td class="text-center"><b>{{$gt_livebirth}}</b></td>
                                        <td class="text-center"><b>{{$gt_tot_death}}</b></td>
                                        <td class="text-center"><b>{{round(($gt_tot_death / $gt_population) * 100, 2)}}</b></td>
                                        <td class="text-center"><b>{{$gt_mat_death}}</b></td>
                                        <td class="text-center"><b>{{round(($gt_mat_death / $gt_population) * 100, 2)}}</b></td>
                                        <td class="text-center"><b>{{$gt_inf_death}}</b></td>
                                        <td class="text-center"><b>{{round(($gt_inf_death / $gt_population) * 100, 2)}}</b></td>
                                        <td class="text-center"><b>{{$gt_unf_death}}</b></td>
                                        <td class="text-center"><b>{{round(($gt_unf_death / $gt_population) * 100, 2)}}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><b>Morbidity and Mortality</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="mortable">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th>No.</th>
                                                <th>Top 10 Mortality <i>(Highest to Lowest)</i></th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mort_final_list as $m)
                                            <tr>
                                                <td scope="row" class="text-center"></td>
                                                <td>{{$m['disease']}}</td>
                                                <td class="text-center">{{$m['count']}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="morbtable">
                                        <thead class="thead-light text-center">
                                            <tr>
                                                <th>No.</th>
                                                <th>Top 10 Morbidity <i>(Highest to Lowest)</i></th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($morb_final_list as $m)
                                            <tr>
                                                <td scope="row" class="text-center"></td>
                                                <td>{{$m['disease']}}</td>
                                                <td class="text-center">{{$m['count']}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        $('#mortable, #morbtable').dataTable({
            iDisplayLength: 10,
            'dom': 'ftp',
            'orderFixed': {
                'pre': [[2, 'desc']]
            },
            'drawCallback': function(settings) {
                var api = this.api();
                var startIndex = api.context[0]._iDisplayStart;
                api.column(0).nodes().each(function(cell, i) {
                    cell.innerHTML = startIndex + i + 1;
                });
            }
        });

        $('#type').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'yearly') {
                $('#quarter').addClass('d-none');
                $('#quarter').prop('required', false);
                $('#month').addClass('d-none');
                $('#month').prop('required', false);
            }
            else if($(this).val() == 'quarterly') {
                $('#quarter').removeClass('d-none');
                $('#quarter').prop('required', true);
                $('#month').addClass('d-none');
                $('#month').prop('required', false);
            }
            else if($(this).val() == 'monthly') {
                $('#quarter').addClass('d-none');
                $('#quarter').prop('required', false);
                $('#month').removeClass('d-none');
                $('#month').prop('required', true);
            }
        }).trigger('change');
    </script>
@endsection