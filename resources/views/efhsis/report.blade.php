@extends('layouts.app')

@section('content')
<style>
    @media print {
        @page { size: landscape; }
    }
</style>
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
                <div class="text-center">
                    <img src="{{asset('assets/images/CHO_LETTERHEAD_WITH_CESU.png')}}" class="img-fluid" style="width: 50rem;">
                    <img src="{{asset('assets/images/efhsis_logo.jpg')}}" class="img-fluid" style="width: 20rem;">
                    <h2><b><u><span class="text-primary">e</span><span class="text-danger">FHSIS</span> Report</u></b></h2>
                    @if(request()->input('type') == 'yearly')
                    <h3>YEAR : {{request()->input('year')}}</h3>
                    @elseif(request()->input('type') == 'quarterly')
                        @if(request()->input('quarter') == 1)
                        <h3>1ST QUARTER : YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('quarter') == 2)
                        <h3>2ND QUARTER : YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('quarter') == 3)
                        <h3>3RD QUARTER : YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('quarter') == 4)
                        <h3>4TH QUARTER : YEAR {{request()->input('year')}}</h3>
                        @endif
                    @elseif(request()->input('type') == 'montly')
                        @if(request()->input('month') == '01')
                        <h3>MONTH OF JANUARY: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '02')
                        <h3>MONTH OF FEBRUARY: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '03')
                        <h3>MONTH OF MARCH: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '04')
                        <h3>MONTH OF APRIL: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '05')
                        <h3>MONTH OF MAY: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '06')
                        <h3>MONTH OF JUNE: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '07')
                        <h3>MONTH OF JULY: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '08')
                        <h3>MONTH OF AUGUST: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '09')
                        <h3>MONTH OF SEPTEMBER: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '10')
                        <h3>MONTH OF OCTOBER: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '11')
                        <h3>MONTH OF NOVEMBER: YEAR {{request()->input('year')}}</h3>
                        @elseif(request()->input('month') == '12')
                        <h3>MONTH OF DECEMBER: YEAR {{request()->input('year')}}</h3>
                        @endif
                    @endif
                    <hr>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b class="text-primary">M1 BRGY</b></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="text-center thead-light">
                                    <tr>
                                        <th rowspan="2">Barangay</th>
                                        <th colspan="6">Child Care</th>
                                        <th colspan="5">Family Planning</th>
                                        <th colspan="3">Non-Com</th>
                                        <th colspan="2">Dental</th>
                                        <th colspan="3">Environmental</th>
                                    </tr>
                                    <tr>
                                        <th>FIC - M</th>
                                        <th>FIC - F</th>
                                        <th>TOTAL</th>
                                        <th>CIC - M</th>
                                        <th>CIC - F</th>
                                        <th>TOTAL</th>
                                        <th>CU (Beg. Month)</th>
                                        <th>OA</th>
                                        <th>DO</th>
                                        <th>CU (End Month)</th>
                                        <th>New Acceptors (Present Month)</th>
                                        <th>RISK ASSESS</th>
                                        <th>PPV</th>
                                        <th>FLU-VACCINE</th>
                                        <th>BHOC - Male</th>
                                        <th>BHOC - Female</th>
                                        <th>L1</th>
                                        <th>L2</th>
                                        <th>L3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $gfic_m = 0;
                                    $gfic_f = 0;
                                    $gcic_m = 0;
                                    $gcic_f = 0;

                                    $gra = 0;
                                    $gppv = 0;
                                    $gflu = 0;

                                    $gfp_currusers_beggining = 0;
                                    $gfp_otheraccp_present = 0;
                                    $gfp_dropouts_present = 0;
                                    $gfp_currusers_end = 0;
                                    $gfp_newaccp_present = 0;

                                    $elvl1 = 0;
                                    $elvl2 = 0;
                                    $elvl3 = 0;
                                    
                                    $gbhoc_m = 0;
                                    $gbhoc_f = 0;
                                    @endphp

                                    @foreach($bgy_mone_list as $b)
                                    <tr>
                                        <td><b>{{mb_strtoupper($b['barangay'])}}</b></td>
                                        <td class="text-center">{{$b['fic_m']}}</td>
                                        <td class="text-center">{{$b['fic_f']}}</td>
                                        <td class="text-center"><b>{{($b['fic_m'] + $b['fic_f'])}}</b></td>
                                        <td class="text-center">{{$b['cic_m']}}</td>
                                        <td class="text-center">{{$b['cic_f']}}</td>
                                        <td class="text-center"><b>{{($b['cic_m'] + $b['cic_f'])}}</b></td>
                                        <td class="text-center">{{$b['fp_currusers_beggining']}}</td>
                                        <td class="text-center">{{$b['fp_otheraccp_present']}}</td>
                                        <td class="text-center">{{$b['fp_dropouts_present']}}</td>
                                        <td class="text-center">{{($b['fp_currusers_beggining'] + $b['fp_otheraccp_present'] - $b['fp_dropouts_present'])}}</td>
                                        <td class="text-center">{{$b['fp_newaccp_present']}}</td>
                                        <td class="text-center">{{$b['ra']}}</td>
                                        <td class="text-center">{{$b['ppv']}}</td>
                                        <td class="text-center">{{$b['flu']}}</td>
                                        <td class="text-center">{{$b['bhoc_m']}}</td>
                                        <td class="text-center">{{$b['bhoc_f']}}</td>
                                        <td class="text-center">{{$b['env_lvl1']}}</td>
                                        <td class="text-center">{{$b['env_lvl2']}}</td>
                                        <td class="text-center">{{$b['env_lvl3']}}</td>
                                    </tr>
                                    @php
                                    $gfic_m += $b['fic_m'];
                                    $gfic_f += $b['fic_f'];
                                    $gcic_m += $b['cic_m'];
                                    $gcic_f += $b['cic_f'];
                                    
                                    $gra += $b['ra'];
                                    $gppv += $b['ppv'];
                                    $gflu += $b['flu'];

                                    $gfp_currusers_beggining += $b['fp_currusers_beggining'];
                                    $gfp_otheraccp_present += $b['fp_otheraccp_present'];
                                    $gfp_dropouts_present += $b['fp_dropouts_present'];
                                    $gfp_currusers_end += $b['fp_currusers_beggining'] + $b['fp_otheraccp_present'] - $b['fp_dropouts_present'];
                                    $gfp_newaccp_present += $b['fp_newaccp_present'];

                                    $elvl1 += $b['env_lvl1'];
                                    $elvl2 += $b['env_lvl2'];
                                    $elvl3 += $b['env_lvl3'];

                                    $gbhoc_m += $b['bhoc_m'];
                                    $gbhoc_f += $b['bhoc_f'];
                                    @endphp

                                    @endforeach
                                </tbody>
                                <tfoot class="text-center font-weight-bold">
                                    <tr>
                                        <td>GRAND TOTAL</td>
                                        <td>{{number_format($gfic_m)}}</td>
                                        <td>{{number_format($gfic_f)}}</td>
                                        <td>{{number_format(($gfic_m + $gfic_f))}}</td>
                                        <td>{{number_format($gcic_m)}}</td>
                                        <td>{{number_format($gcic_f)}}</td>
                                        <td>{{number_format(($gcic_m + $gcic_f))}}</td>
                                        <td>{{number_format($gfp_currusers_beggining)}}</td>
                                        <td>{{number_format($gfp_otheraccp_present)}}</td>
                                        <td>{{number_format($gfp_dropouts_present)}}</td>
                                        <td>{{number_format($gfp_currusers_end)}}</td>
                                        <td>{{number_format($gfp_newaccp_present)}}</td>
                                        <td>{{number_format($gra)}}</td>
                                        <td>{{number_format($gppv)}}</td>
                                        <td>{{number_format($gflu)}}</td>
                                        <td>{{number_format($gbhoc_m)}}</td>
                                        <td>{{number_format($gbhoc_f)}}</td>
                                        <td>{{number_format($elvl1)}}</td>
                                        <td>{{number_format($elvl2)}}</td>
                                        <td>{{number_format($elvl3)}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b class="text-primary">MORTALITY AND NATALITY</b></div>
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
                                        <td><b>{{mb_strtoupper($b['barangay'])}}</b></td>
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
                                <tfoot class="bg-light">
                                    <tr>
                                        <td class="text-center"><b>GRAND TOTAL</b></td>
                                        <td class="text-center"><b>{{number_format($gt_population)}}</b></td>
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
                <div class="card mb-3">
                    <div class="card-header"><b class="text-primary">MORBIDITY AND MORTALITY</b></div>
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
                <div class="alert alert-info" role="alert">
                    <b class="text-danger">DISCLAIMER:</b> eFHSIS Report was generated using CESU General Trias Integrated Web System (Developed and Maintained by Christian James Historillo - J.O Encoder). Every effort has been made to provide accurate and updated information; however, errors can still occur. By using the information in this report, the reader assumes all risks concerning such use. The City Health Office of General Trias City shall not be held responsible for errors, nor liable for damage(s) resulting from the use or reliance upon this material.
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Prepared by</div>
                            <div class="card-body text-center">
                                <h5 style="margin-top: 50px;"><b>CHRISTOFER JOHN A. PEDRASA & LESTER E. CAMINGAY</b></h5>
                                <h5>Encoder</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Approved by</div>
                            <div class="card-body text-center">
                                <h5 style="margin-top: 50px;"><b>LUIS P. BROAS, RN, RPh, MAN</b></h5>
                                <h5>Nurse II/CESU Head</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">Noted by</div>
                            <div class="card-body text-center">
                                <h5 style="margin-top: 50px;"><b>JONATHAN P. LUSECO, MD</b></h5>
                                <h5>City Health Officer II</h5>
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