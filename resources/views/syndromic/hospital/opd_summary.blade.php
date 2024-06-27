@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>OPD/ER Summary</b></div>
                <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filter">Filter</button></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="text-center thead-light">
                    <tr>
                        <th rowspan="3">
                            <h6><b>{{request()->input('id')}}</b></h6>
                            <h6>Date: 
                                @if(request()->input('type') == 'Daily')
                                {{date('F d, Y', strtotime(request()->input('sdate')))}}
                                @else
                                {{$month_flavor}}, {{$syear}}
                                @endif
                            </h6>
                        </th>
                        <th colspan="6">
                            <h6><b>Pedia</b></h6>
                            <h6><i>(0-19 y.o)</i></h6>
                        </th>
                        <th colspan="6">
                            <h6><b>Adult</b></h6>
                            <h6><i>(20-59 y.o)</i></h6>
                        </th>
                        <th colspan="6">
                            <h6><b>Geriatric</b></h6>
                            <h6><i>(60 AND ABOVE)</i></h6>
                        </th>
                        <th rowspan="3">
                            TOTAL
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-primary">M</th>
                        <th colspan="3" class="text-danger">F</th>
                        <th colspan="3" class="text-primary">M</th>
                        <th colspan="3" class="text-danger">F</th>
                        <th colspan="3" class="text-primary">M</th>
                        <th colspan="3" class="text-danger">F</th>
                    </tr>
                    <tr>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                        <th colspan="1">O</th>
                        <th colspan="1">N</th>
                        <th colspan="1">P</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $pedia_old_m_total = 0;
                    $pedia_new_m_total = 0;
                    $pedia_police_m_total = 0;
                    $pedia_old_f_total = 0;
                    $pedia_new_f_total = 0;
                    $pedia_police_f_total = 0;
                    $adult_old_m_total = 0;
                    $adult_new_m_total = 0;
                    $adult_police_m_total = 0;
                    $adult_old_f_total = 0;
                    $adult_new_f_total = 0;
                    $adult_police_f_total = 0;
                    $senior_old_m_total = 0;
                    $senior_new_m_total = 0;
                    $senior_police_m_total = 0;
                    $senior_old_f_total = 0;
                    $senior_new_f_total = 0;
                    $senior_police_f_total = 0;

                    $main_pedia_total = 0;
                    $main_adult_total = 0;
                    $main_senior_total = 0;
                    @endphp
                    @foreach($final_arr as $f)
                    <tr>
                        <td><b>{{$f['name']}}</b></td>
                        <td class="text-center">{{$f['pedia_old_m']}}</td>
                        <td class="text-center">{{$f['pedia_new_m']}}</td>
                        <td class="text-center">{{$f['pedia_police_m']}}</td>
                        <td class="text-center">{{$f['pedia_old_f']}}</td>
                        <td class="text-center">{{$f['pedia_new_f']}}</td>
                        <td class="text-center">{{$f['pedia_police_f']}}</td>
                        <td class="text-center">{{$f['adult_old_m']}}</td>
                        <td class="text-center">{{$f['adult_new_m']}}</td>
                        <td class="text-center">{{$f['adult_police_m']}}</td>
                        <td class="text-center">{{$f['adult_old_f']}}</td>
                        <td class="text-center">{{$f['adult_new_f']}}</td>
                        <td class="text-center">{{$f['adult_police_f']}}</td>
                        <td class="text-center">{{$f['senior_old_m']}}</td>
                        <td class="text-center">{{$f['senior_new_m']}}</td>
                        <td class="text-center">{{$f['senior_police_m']}}</td>
                        <td class="text-center">{{$f['senior_old_f']}}</td>
                        <td class="text-center">{{$f['senior_new_f']}}</td>
                        <td class="text-center">{{$f['senior_police_f']}}</td>
                        <td class="text-center">
                            <b>{{$f['pedia_old_m'] +
                                $f['pedia_new_m'] +
                                $f['pedia_police_m'] +
                                $f['pedia_old_f'] +
                                $f['pedia_new_f'] +
                                $f['pedia_police_f'] +
                                $f['adult_old_m'] +
                                $f['adult_new_m'] +
                                $f['adult_police_m'] +
                                $f['adult_old_f'] +
                                $f['adult_new_f'] +
                                $f['adult_police_f'] +
                                $f['senior_old_m'] +
                                $f['senior_new_m'] +
                                $f['senior_police_m'] +
                                $f['senior_old_f'] +
                                $f['senior_new_f'] +
                                $f['senior_police_f']}}</b>
                        </td>
                    </tr>
                        @php
                        $pedia_old_m_total += $f['pedia_old_m'];
                        $pedia_new_m_total += $f['pedia_new_m'];
                        $pedia_police_m_total += $f['pedia_police_m'];
                        $pedia_old_f_total += $f['pedia_old_f'];
                        $pedia_new_f_total += $f['pedia_new_f'];
                        $pedia_police_f_total += $f['pedia_police_f'];
                        $adult_old_m_total += $f['adult_old_m'];
                        $adult_new_m_total += $f['adult_new_m'];
                        $adult_police_m_total += $f['adult_police_m'];
                        $adult_old_f_total += $f['adult_old_f'];
                        $adult_new_f_total += $f['adult_new_f'];
                        $adult_police_f_total += $f['adult_police_f'];
                        $senior_old_m_total += $f['senior_old_m'];
                        $senior_new_m_total += $f['senior_new_m'];
                        $senior_police_m_total += $f['senior_police_m'];
                        $senior_old_f_total += $f['senior_old_f'];
                        $senior_new_f_total += $f['senior_new_f'];
                        $senior_police_f_total += $f['senior_police_f'];

                        $main_pedia_total += $f['pedia_old_m'] + $f['pedia_new_m'] + $f['pedia_police_m'] + $f['pedia_old_f'] + $f['pedia_new_f'] + $f['pedia_police_f'];
                        $main_adult_total += $f['adult_old_m'] + $f['adult_new_m'] + $f['adult_police_m'] + $f['adult_old_f'] + $f['adult_new_f'] + $f['adult_police_f'];
                        $main_senior_total += $f['senior_old_m'] + $f['senior_new_m'] + $f['senior_police_m'] + $f['senior_old_f'] + $f['senior_new_f'] + $f['senior_police_f'];
                        @endphp
                    @endforeach
                    <tr class="font-weight-bold bg-light">
                        <td class="text-right">TOTAL</td>
                        <td class="text-center">{{$pedia_old_m_total}}</td>
                        <td class="text-center">{{$pedia_new_m_total}}</td>
                        <td class="text-center">{{$pedia_police_m_total}}</td>
                        <td class="text-center">{{$pedia_old_f_total}}</td>
                        <td class="text-center">{{$pedia_new_f_total}}</td>
                        <td class="text-center">{{$pedia_police_f_total}}</td>
                        <td class="text-center">{{$adult_old_m_total}}</td>
                        <td class="text-center">{{$adult_new_m_total}}</td>
                        <td class="text-center">{{$adult_police_m_total}}</td>
                        <td class="text-center">{{$adult_old_f_total}}</td>
                        <td class="text-center">{{$adult_new_f_total}}</td>
                        <td class="text-center">{{$adult_police_f_total}}</td>
                        <td class="text-center">{{$senior_old_m_total}}</td>
                        <td class="text-center">{{$senior_new_m_total}}</td>
                        <td class="text-center">{{$senior_police_m_total}}</td>
                        <td class="text-center">{{$senior_old_f_total}}</td>
                        <td class="text-center">{{$senior_new_f_total}}</td>
                        <td class="text-center">{{$senior_police_f_total}}</td>
                        <td class="text-center text-danger">{{$main_pedia_total + $main_adult_total + $main_senior_total}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="5" class="text-right"><b>PEDIA</b></td>
                        <td class="text-center"><b>{{$main_pedia_total}}</b></td>
                        <td colspan="5" class="text-right"><b>ADULT</b></td>
                        <td class="text-center"><b>{{$main_adult_total}}</b></td>
                        <td colspan="5" class="text-right"><b>SENIOR</b></td>
                        <td class="text-center"><b>{{$main_senior_total}}</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                    $pedia_old_m_total = 0;
                    $pedia_new_m_total = 0;
                    $pedia_police_m_total = 0;
                    $pedia_old_f_total = 0;
                    $pedia_new_f_total = 0;
                    $pedia_police_f_total = 0;
                    $adult_old_m_total = 0;
                    $adult_new_m_total = 0;
                    $adult_police_m_total = 0;
                    $adult_old_f_total = 0;
                    $adult_new_f_total = 0;
                    $adult_police_f_total = 0;
                    $senior_old_m_total = 0;
                    $senior_new_m_total = 0;
                    $senior_police_m_total = 0;
                    $senior_old_f_total = 0;
                    $senior_new_f_total = 0;
                    $senior_police_f_total = 0;

                    $main_pedia_total = 0;
                    $main_adult_total = 0;
                    $main_senior_total = 0;
                    @endphp
                    @foreach($second_array as $f)
                    <tr>
                        <td>{{$f['name']}}</td>
                        <td class="text-center {{($f['name'] == 'MEDICAL') ? 'bg-secondary' : ''}}">{{(($f['name'] != 'MEDICAL')) ? $f['pedia_old_m'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'MEDICAL') ? 'bg-secondary' : ''}}">{{(($f['name'] != 'MEDICAL')) ? $f['pedia_new_m'] : '' }}</td>
                        <td class="text-center {{($f['name'] == 'MEDICAL') ? 'bg-secondary' : ''}}">{{(($f['name'] != 'MEDICAL')) ? $f['pedia_police_m'] : '' }}</td>
                        <td class="text-center {{($f['name'] == 'MEDICAL') ? 'bg-secondary' : ''}}">{{(($f['name'] != 'MEDICAL')) ? $f['pedia_old_f'] : '' }}</td>
                        <td class="text-center {{($f['name'] == 'MEDICAL') ? 'bg-secondary' : ''}}">{{(($f['name'] != 'MEDICAL')) ? $f['pedia_new_f'] : '' }}</td>
                        <td class="text-center {{($f['name'] == 'MEDICAL') ? 'bg-secondary' : ''}}">{{(($f['name'] != 'MEDICAL')) ? $f['pedia_police_f'] : '' }}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['adult_old_m'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['adult_new_m'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['adult_police_m'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['adult_old_f'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['adult_new_f'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['adult_police_f'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['senior_old_m'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['senior_new_m'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['senior_police_m'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['senior_old_f'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['senior_new_f'] : ''}}</td>
                        <td class="text-center {{($f['name'] == 'PEDIATRICS') ? 'bg-secondary' : ''}}">{{($f['name'] != 'PEDIATRICS') ? $f['senior_police_f'] : ''}}</td>
                        <td class="text-center">
                            <b>{{$f['pedia_old_m'] +
                                $f['pedia_new_m'] +
                                $f['pedia_police_m'] +
                                $f['pedia_old_f'] +
                                $f['pedia_new_f'] +
                                $f['pedia_police_f'] +
                                $f['adult_old_m'] +
                                $f['adult_new_m'] +
                                $f['adult_police_m'] +
                                $f['adult_old_f'] +
                                $f['adult_new_f'] +
                                $f['adult_police_f'] +
                                $f['senior_old_m'] +
                                $f['senior_new_m'] +
                                $f['senior_police_m'] +
                                $f['senior_old_f'] +
                                $f['senior_new_f'] +
                                $f['senior_police_f']}}</b>
                        </td>
                    </tr>
                        @php
                        $pedia_old_m_total += $f['pedia_old_m'];
                        $pedia_new_m_total += $f['pedia_new_m'];
                        $pedia_police_m_total += $f['pedia_police_m'];
                        $pedia_old_f_total += $f['pedia_old_f'];
                        $pedia_new_f_total += $f['pedia_new_f'];
                        $pedia_police_f_total += $f['pedia_police_f'];
                        $adult_old_m_total += $f['adult_old_m'];
                        $adult_new_m_total += $f['adult_new_m'];
                        $adult_police_m_total += $f['adult_police_m'];
                        $adult_old_f_total += $f['adult_old_f'];
                        $adult_new_f_total += $f['adult_new_f'];
                        $adult_police_f_total += $f['adult_police_f'];
                        $senior_old_m_total += $f['senior_old_m'];
                        $senior_new_m_total += $f['senior_new_m'];
                        $senior_police_m_total += $f['senior_police_m'];
                        $senior_old_f_total += $f['senior_old_f'];
                        $senior_new_f_total += $f['senior_new_f'];
                        $senior_police_f_total += $f['senior_police_f'];

                        $main_pedia_total += $f['pedia_old_m'] + $f['pedia_new_m'] + $f['pedia_police_m'] + $f['pedia_old_f'] + $f['pedia_new_f'] + $f['pedia_police_f'];
                        $main_adult_total += $f['adult_old_m'] + $f['adult_new_m'] + $f['adult_police_m'] + $f['adult_old_f'] + $f['adult_new_f'] + $f['adult_police_f'];
                        $main_senior_total += $f['senior_old_m'] + $f['senior_new_m'] + $f['senior_police_m'] + $f['senior_old_f'] + $f['senior_new_f'] + $f['senior_police_f'];
                        @endphp
                    @endforeach
                    <tr class="font-weight-bold bg-light">
                        <td class="text-right">TOTAL</td>
                        <td class="text-center">{{$pedia_old_m_total}}</td>
                        <td class="text-center">{{$pedia_new_m_total}}</td>
                        <td class="text-center">{{$pedia_police_m_total}}</td>
                        <td class="text-center">{{$pedia_old_f_total}}</td>
                        <td class="text-center">{{$pedia_new_f_total}}</td>
                        <td class="text-center">{{$pedia_police_f_total}}</td>
                        <td class="text-center">{{$adult_old_m_total}}</td>
                        <td class="text-center">{{$adult_new_m_total}}</td>
                        <td class="text-center">{{$adult_police_m_total}}</td>
                        <td class="text-center">{{$adult_old_f_total}}</td>
                        <td class="text-center">{{$adult_new_f_total}}</td>
                        <td class="text-center">{{$adult_police_f_total}}</td>
                        <td class="text-center">{{$senior_old_m_total}}</td>
                        <td class="text-center">{{$senior_new_m_total}}</td>
                        <td class="text-center">{{$senior_police_m_total}}</td>
                        <td class="text-center">{{$senior_old_f_total}}</td>
                        <td class="text-center">{{$senior_new_f_total}}</td>
                        <td class="text-center">{{$senior_police_f_total}}</td>
                        <td class="text-center text-danger">{{$main_pedia_total + $main_adult_total + $main_senior_total}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<form action="" method="GET">
    <div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="id"><b class="text-danger">*</b>Type</label>
                      <select class="form-control" name="id" id="id" required>
                        <option value="" disabled {{(is_null(request()->input('id'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="OPD" {{(request()->input('id') == 'OPD') ? 'selected' : ''}}>OPD</option>
                        <option value="ER" {{(request()->input('id') == 'ER') ? 'selected' : ''}}>ER</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="type"><b class="text-danger">*</b>Scope</label>
                      <select class="form-control" name="type" id="type" required>
                        <option value="" disabled selected>Choose...</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Daily">Daily</option>
                      </select>
                    </div>
                    <div id="ifMonthly" class="d-none">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="syear"><b class="text-danger">*</b>Year</label>
                                    <input type="number" class="form-control" name="syear" id="syear" value="{{(request()->input('syear')) ? request()->input('syear') : date('Y')}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                  <label for="smonth"><b class="text-danger">*</b>Month</label>
                                  <select class="form-control" name="smonth" id="smonth">
                                    <option value="01" {{($smonth == '01') ? 'selected' : ''}}>January</option>
                                    <option value="02" {{($smonth == '02') ? 'selected' : ''}}>February</option>
                                    <option value="03" {{($smonth == '03') ? 'selected' : ''}}>March</option>
                                    <option value="04" {{($smonth == '04') ? 'selected' : ''}}>April</option>
                                    <option value="05" {{($smonth == '05') ? 'selected' : ''}}>May</option>
                                    <option value="06" {{($smonth == '06') ? 'selected' : ''}}>June</option>
                                    <option value="07" {{($smonth == '07') ? 'selected' : ''}}>July</option>
                                    <option value="08" {{($smonth == '08') ? 'selected' : ''}}>August</option>
                                    <option value="09" {{($smonth == '09') ? 'selected' : ''}}>September</option>
                                    <option value="10" {{($smonth == '10') ? 'selected' : ''}}>October</option>
                                    <option value="11" {{($smonth == '11') ? 'selected' : ''}}>November</option>
                                    <option value="12" {{($smonth == '12') ? 'selected' : ''}}>December</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="ifDaily" class="d-none">
                        <div class="form-group">
                          <label for="sdate"><b class="text-danger">*</b>Select Date</label>
                          <input type="date" class="form-control" name="sdate" id="sdate" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#type').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Monthly') {
            $('#ifMonthly').removeClass('d-none');

            $('#syear').prop('required', true);
            $('#smonth').prop('required', true);

            $('#ifDaily').addClass('d-none');
            $('#sdate').prop('required', false);
        }
        else if ($(this).val() == 'Daily') {
            $('#ifMonthly').addClass('d-none');

            $('#syear').prop('required', false);
            $('#smonth').prop('required', false);

            $('#ifDaily').removeClass('d-none');
            $('#sdate').prop('required', true);
        }
        else {
            $('#ifMonthly').addClass('d-none');
            $('#ifDaily').addClass('d-none');

            $('#syear').prop('required', false);
            $('#smonth').prop('required', false);
            $('#sdate').prop('required', false);
        }
    }).trigger('change');
</script>
@endsection