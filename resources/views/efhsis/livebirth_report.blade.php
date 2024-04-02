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
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td>Year</td>
                        <td>{{$year}}</td>
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
                      <input type="number" class="form-control" name="year" id="year" min="{{(date('Y')-5)}}" max="{{date('Y')}}" value="{{date('Y')}}" required>
                    </div>
                    <div class="form-group">
                      <label for="month"><b class="text-danger">*</b>Month</label>
                      <select class="form-control" name="month" id="month" required>
                        <option value="" disabled selected>Choose...</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="brgy">Barangay</label>
                      <select class="form-control" name="brgy" id="brgy" required>
                        <option value="" disabled selected>Choose...</option>
                        @foreach ($brgylist as $b)
                            <option value="{{$b->brgyName}}">{{$b->brgyName}}</option>
                        @endforeach
                        <option value="OTHER CITIES">OTHER CITIES</option>
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