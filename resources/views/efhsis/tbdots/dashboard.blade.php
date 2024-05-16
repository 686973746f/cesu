@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>FHSIS TB-DOTS</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="d-flex justify-content-between">
                    <div>
                        <h5><b>Barangay:</b></h5>
                        <h5>{{request()->input('brgy')}}</h5>
                    </div>
                    <div>
                        <h5><b>Month:</b></h5>
                        <h5>{{date('F', strtotime(request()->input('year').'-'.request()->input('month').'-01'))}}</h5>
                    </div>
                    <div>
                        <h5><b>Year:</b></h5>
                        <h5>{{request()->input('year')}}</h5>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered table-striped">
                    <thead class="thead-light text-center">
                        <tr>
                            <th rowspan="2">DISEASE</th>
                            <th colspan="2">0-6 Days</th>
                            <th colspan="2">7-28 Days</th>
                            <th colspan="2">29 Days - 11 Mos.</th>
                            <th colspan="2">1-4</th>
                            <th colspan="2">5-9</th>
                            <th colspan="2">10-14</th>
                            <th colspan="2">15-19</th>
                            <th colspan="2">20-24</th>
                            <th colspan="2">25-29</th>
                            <th colspan="2">30-34</th>
                            <th colspan="2">35-39</th>
                            <th colspan="2">40-44</th>
                            <th colspan="2">45-49</th>
                            <th colspan="2">50-54</th>
                            <th colspan="2">55-59</th>
                            <th colspan="2">60-64</th>
                            <th colspan="2">65-69</th>
                            <th colspan="2">70 and Above</th>
                            <th colspan="2"><b>TOTAL</b></th>
                        </tr>
                        <tr>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;">M</th>
                            <th style="background-color: #dea6a5">F</th>
                            <th style="background-color: #8fa2bd;"><b>M</b></th>
                            <th style="background-color: #dea6a5"><b>F</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($final_array as $d)
                        <tr class="text-center">
                            <td>{{$d['disease']}}</td>
                            <td style="background-color: #8fa2bd;">0</td>
                            <td style="background-color: #dea6a5">0</td>
                            <td style="background-color: #8fa2bd;">0</td>
                            <td style="background-color: #dea6a5">0</td>
                            <td style="background-color: #8fa2bd;">0</td>
                            <td style="background-color: #dea6a5">0</td>
                            <td style="background-color: #8fa2bd;">{{$d['age1_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age1_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age2_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age2_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age3_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age3_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age4_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age4_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age5_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age5_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age6_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age6_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age7_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age7_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age8_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age8_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age9_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age9_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age10_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age10_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age11_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age11_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age12_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age12_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age13_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age13_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age14_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age14_female']}}</td>
                            <td style="background-color: #8fa2bd;">{{$d['age15_male']}}</td>
                            <td style="background-color: #dea6a5">{{$d['age15_female']}}</td>
                            <td style="background-color: #8fa2bd;"><b>{{$d['agetotal_male']}}</b></td>
                            <td style="background-color: #dea6a5"><b>{{$d['agetotal_female']}}</b></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <form action="{{route('fhsis_tbdots_dashboard')}}" method="GET">
        <div class="modal fade" id="loadDashboard" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Load TB-DOTS Morbidity Dashboard</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="brgy"><b class="text-danger">*</b>Select Barangay</label>
                            <select class="form-control" name="brgy" id="brgy" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach($brgy_list as $b)
                                <option value="{{$b->brgyName}}">{{$b->brgyName}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Select Year</label>
                            <select class="form-control" name="year" id="year" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(range(date('Y'), 2023) as $y)
                                <option value="{{$y}}">{{$y}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month"><b class="text-danger">*</b>Select Month</label>
                            <select class="form-control" name="month" id="month" required>
                                <option value="" disabled selected>Choose...</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
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
@endsection