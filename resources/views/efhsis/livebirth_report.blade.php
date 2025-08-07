@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Natality Report</b></div>
                <div><button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#liveBirthReport">Change Report Period</button></div>
            </div>
        </div>
        <div class="card-body">
            @if($brgy == 'ALL BARANGAYS IN GENERAL TRIAS')
            <h3 class="text-center"><b>NATALITY REPORT - <span class="text-success">{{mb_strtoupper(date('F', strtotime($year.'-'.$month.'-01')))}} {{$year}}</span></b></h3>
            <table class="table table-bordered table-striped table-hover" id="lbTable">
                <thead class="thead-light text-center">
                    <tr>
                        <th rowspan="2">#</th>
                        <th rowspan="2">
                            <div>Barangay</div>
                            <div class="text-info">{{date('F', strtotime($year.'-'.$month.'-01'))}} {{$year}}</div>
                        </th>
                        <th colspan="2">Livebirths</th>
                        <th colspan="2">Livebirths among 10-14 y/o women</th>
                        <th colspan="2">Livebirths among 15-19 y/o women</th>
                    </tr>
                    <tr>
                        <th style="background-color: #8fa2bd;">M</th>
                        <th style="background-color: #dea6a5">F</th>
                        <th style="background-color: #8fa2bd;">M</th>
                        <th style="background-color: #dea6a5">F</th>
                        <th style="background-color: #8fa2bd;">M</th>
                        <th style="background-color: #dea6a5">F</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($brgy_array as $ind => $b)
                    <tr>
                        <td class="text-center">{{$ind+1}}</td>
                        <td><b>{{$b['name']}}</b></td>
                        <td class="text-center" style="background-color: #8fa2bd;">{{($b['total_livebirths_m'] != 0) ? $b['total_livebirths_m'] : 'N/A'}}</td>
                        <td class="text-center" style="background-color: #dea6a5">{{($b['total_livebirths_f'] != 0) ? $b['total_livebirths_f'] : 'N/A'}}</td>
                        <td class="text-center" style="background-color: #8fa2bd;">{{(($b['total_livebirths_m'] + $b['total_livebirths_f']) != 0) ? $b['livebirth1014_m'] : ''}}</td>
                        <td class="text-center" style="background-color: #dea6a5">{{(($b['total_livebirths_m'] + $b['total_livebirths_f']) != 0) ? $b['livebirth1014_f'] : ''}}</td>
                        <td class="text-center" style="background-color: #8fa2bd;">{{(($b['total_livebirths_m'] + $b['total_livebirths_f']) != 0) ? $b['livebirth1519_m'] : ''}}</td>
                        <td class="text-center" style="background-color: #dea6a5">{{(($b['total_livebirths_m'] + $b['total_livebirths_f']) != 0) ? $b['livebirth1519_f'] : ''}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="text-center">999</td>
                        <td><b>Non-Resident (Outside Gen. Trias)</b></td>
                        <td class="text-center" style="background-color: #8fa2bd;">{{($livebirth_othercities_total_m != 0) ? $livebirth_othercities_total_m : 'N/A'}}</td>
                        <td class="text-center" style="background-color: #dea6a5">{{($livebirth_othercities_total_f != 0) ? $livebirth_othercities_total_f : 'N/A'}}</td>
                        <td class="text-center" style="background-color: #8fa2bd;">{{(($livebirth_othercities_1014_m + $livebirth_othercities_1014_f) != 0) ? $livebirth_othercities_1014_m : ''}}</td>
                        <td class="text-center" style="background-color: #dea6a5">{{(($livebirth_othercities_1014_m + $livebirth_othercities_1014_f) != 0) ? $livebirth_othercities_1014_f : ''}}</td>
                        <td class="text-center" style="background-color: #8fa2bd;">{{(($livebirth_othercities_1014_m + $livebirth_othercities_1014_f) != 0) ? $livebirth_othercities_1519_m : ''}}</td>
                        <td class="text-center" style="background-color: #dea6a5">{{(($livebirth_othercities_1014_m + $livebirth_othercities_1014_f) != 0) ? $livebirth_othercities_1519_f : ''}}</td>
                    </tr>
                </tbody>
            </table>
            @else
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Year</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Month</td>
                        <td>{{date('F', strtotime($year.'-'.$month.'-01'))}}</td>
                    </tr>
                    <tr>
                        <td>Barangay</td>
                        <td>{{$brgy}}</td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Livebirths</td>
                        <td class="text-center">{{$total_livebirths}}</td>
                    </tr>
                    <tr>
                        <td>Livebirths among 10-14 y/o women</td>
                        <td class="text-center">{{$livebirth1014}}</td>
                    </tr>
                    <tr>
                        <td>Livebirths among 15-19 y/o women</td>
                        <td class="text-center">{{$livebirth1519}}</td>
                    </tr>
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>

<form action="{{route('fhsis_livebirth_report')}}" method="GET">
    <div class="modal fade" id="liveBirthReport" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Natality Report</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="year"><b class="text-danger">*</b>Year</label>
                      <input type="number" class="form-control" name="year" id="year" min="{{(date('Y')-5)}}" max="{{date('Y')}}" value="{{$year}}" required>
                    </div>
                    <div class="form-group">
                      <label for="month"><b class="text-danger">*</b>Month</label>
                      <select class="form-control" name="month" id="month" required>
                        <option value="" disabled selected>Choose...</option>
                        <option value="1" {{($month == 1) ? 'selected' : ''}}>January</option>
                        <option value="2" {{($month == 2) ? 'selected' : ''}}>February</option>
                        <option value="3" {{($month == 3) ? 'selected' : ''}}>March</option>
                        <option value="4" {{($month == 4) ? 'selected' : ''}}>April</option>
                        <option value="5" {{($month == 5) ? 'selected' : ''}}>May</option>
                        <option value="6" {{($month == 6) ? 'selected' : ''}}>June</option>
                        <option value="7" {{($month == 7) ? 'selected' : ''}}>July</option>
                        <option value="8" {{($month == 8) ? 'selected' : ''}}>August</option>
                        <option value="9" {{($month == 9) ? 'selected' : ''}}>September</option>
                        <option value="10" {{($month == 10) ? 'selected' : ''}}>October</option>
                        <option value="11" {{($month == 11) ? 'selected' : ''}}>November</option>
                        <option value="12" {{($month == 12) ? 'selected' : ''}}>December</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="brgy">Barangay</label>
                      <select class="form-control" name="brgy" id="brgy" required>
                        <option value="" disabled {{is_null($brgy) ? 'selected' : ''}}>Choose...</option>
                        <option value="ALL BARANGAYS IN GENERAL TRIAS" {{($brgy == 'ALL BARANGAYS IN GENERAL TRIAS') ? 'selected' : ''}}>ALL BARANGAYS IN GENERAL TRIAS</option>
                        @foreach ($brgylist as $b)
                            <option value="{{$b->brgyName}}" {{($brgy == $b->brgyName) ? 'selected' : ''}}>{{$b->brgyName}}</option>
                        @endforeach
                        <option value="OTHER CITIES" {{($brgy == 'OTHER CITIES') ? 'selected' : ''}}>OTHER CITIES</option>
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Generate</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#lbTable').DataTable( {
        fixedHeader: true,
        order: [[0, 'asc']]
    });
</script>

@if($brgy == 'ALL BARANGAYS IN GENERAL TRIAS')

@endif
@endsection