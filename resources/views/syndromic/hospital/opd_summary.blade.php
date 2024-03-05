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
                    </tr>
                    <tr>
                        <th colspan="3">M</th>
                        <th colspan="3">F</th>
                        <th colspan="3">M</th>
                        <th colspan="3">F</th>
                        <th colspan="3">M</th>
                        <th colspan="3">F</th>
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
                    $main_pedia_total = 0;
                    $main_adult_total = 0;
                    $main_senior_total = 0;
                    @endphp
                    @foreach($final_arr as $f)
                    <tr>
                        <td>{{$f['name']}}</td>
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
                    </tr>
                        @php
                        $main_pedia_total += $f['pedia_old_m'] + $f['pedia_new_m'] + $f['pedia_police_m'] + $f['pedia_old_f'] + $f['pedia_new_f'] + $f['pedia_police_f'];
                        $main_adult_total += $f['adult_old_m'] + $f['adult_new_m'] + $f['adult_police_m'] + $f['adult_old_f'] + $f['adult_new_f'] + $f['adult_police_f'];
                        $main_senior_total += $f['senior_old_m'] + $f['senior_new_m'] + $f['senior_police_m'] + $f['senior_old_f'] + $f['senior_new_f'] + $f['senior_police_f'];
                        @endphp
                    @endforeach
                    <tr>
                        <td></td>
                        <td colspan="5" class="text-right">Pedia Total</td>
                        <td class="text-center">{{$main_pedia_total}}</td>
                        <td colspan="5" class="text-right">Adult Total</td>
                        <td class="text-center">{{$main_adult_total}}</td>
                        <td colspan="5" class="text-right">Senior Total</td>
                        <td class="text-center">{{$main_senior_total}}</td>
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
                    </tr>
                    @foreach($second_array as $f)
                    <tr>
                        <td>{{$f['name']}}</td>
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
                    </tr>
                    @endforeach
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