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
                            <h6><b>OPD</b></h6>
                            <h6>Date: {{$month_flavor}}, {{$syear}}</h6>
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
                      <label for="id">Type</label>
                      <select class="form-control" name="id" id="id" required>
                        <option value="" disabled {{(is_null(request()->input('id'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="OPD" {{(request()->input('id') == 'OPD') ? 'selected' : ''}}>OPD</option>
                        <option value="ER" {{(request()->input('id') == 'ER') ? 'selected' : ''}}>ER</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Year</label>
                      <input type="number" class="form-control" name="syear" id="syear" value="{{(request()->input('syear')) ? request()->input('syear') : date('Y')}}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Month</label>
                        <input type="number" class="form-control" name="smonth" id="smonth" value="{{(request()->input('smonth')) ? request()->input('smonth') : date('m')}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection