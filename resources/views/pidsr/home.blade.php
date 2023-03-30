@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                      <div>Welcome, {{auth()->user()->name}}</div>
                      <div>Date: {{date('m/d/Y (D)')}} | Morbidity Week: {{date('W')}}</div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                      <div><b>PIDSR MENU</b></div>
                      <div>
                        @if(auth()->user()->canaccess_covid == 1)
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changemenu">Change</button>
                        @endif
                      </div>
                  </div>
                </div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#thresh">Threshold Count</button>
                    <hr>
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#report">Report</button>
                    <hr>
                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#export">Import Excel to Database</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changemenu" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <a href="{{route('main')}}" class="btn btn-primary btn-block">COVID-19</a>
              <a href="{{route('vaxcert_home')}}" class="btn btn-primary btn-block">VaxCert Concerns</a>
              <hr>
              <a href="{{route('abtc_home')}}" class="btn btn-primary btn-block">Animal Bite (ABTC)</a>
            </div>
        </div>
    </div>
</div>

<form action="{{route('pidsr.threshold')}}" method="GET">
    <div class="modal fade" id="thresh" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Threshold</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sd">Select Disease</label>
                        <select class="form-control" name="sd" id="sd" required>
                            <optgroup label="Category 1 (Immediately Notifiable)">
                                <option value="AFP">Acute Flaccid Paralysis (AFP)</option>
                                <option value="AEFI">Adverse Event Following Immunization (AEFI)</option>
                                <option value="ANTHRAX">Anthrax</option>
                                <option value="HFMD">Hand, Foot and Mouth Disease (HFMD)</option>
                                <option value="MEASLES">Measles</option>
                                <option value="MENINGO">Meningococcal Disease</option>
                                <option value="NT">Neonatal Tetanus</option>
                                <option value="PSP">Paralytic Shellfish Poisoning</option>
                                <option value="RABIES">Rabies</option>
                            </optgroup>
                            <optgroup label="Category 2 (Weekly Notifiable)">
                                <option value="ABD">Acute Bloody Diarrhea</option>
                                <option value="AES">Acute Encephalitis Syndrome</option>
                                <option value="AHF">Acute Hemorrhagic-Fever Syndrome</option>
                                <option value="HEPATITIS">Acute Viral Hepatitis</option>
                                <option value="AMES">AMES</option>
                                <option value="MENINGITIS">Bacterial Meningitis</option>
                                <option value="ChikV">Chikungunya</option>
                                <option value="CHOLERA">Cholera</option>
                                <option value="DENGUE">Dengue</option>
                                <option value="DIPH">Diptheria</option>
                                <option value="INFLUENZA">Influenza-like Illness</option>
                                <option value="LEPTOSPIROSIS">Leptospirosis</option>
                                <option value="MALARIA">Malaria</option>
                                <option value="NNT">Non-Neonatal Tetanus</option>
                                <option value="PERT">Pertussis</option>
                                <option value="ROTAVIRUS">RotaVirus</option>
                                <option value="TYPHOID">Typhoid and Parathypoid Fever</option>
                            </optgroup>
                            <optgroup label="Others">
                                <option value="MONKEYPOX">Monkeypox</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Select Year</label>
                        <select class="form-control" name="year" id="year" required>
                            @foreach(range(date('Y'), 2018) as $y)
                            <option value="{{$y}}">{{$y}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="export" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body text-center">
                <p>Please execute the Batch Script first before Proceeding.</p>
            </div>
            <div class="modal-footer">
                <a href="{{route('pidsr.import')}}?m=1" class="btn btn-primary">Proceed</a>
            </div>
        </div>
    </div>
</div>

<form action="{{route('pidsr.report')}}" method="GET">
    <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="year">Select Year</label>
                        <select class="form-control" name="year" id="year" required>
                          @foreach(range(date('Y'), 2020) as $y)
                              <option value="{{$y}}">{{$y}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="rtype">Select Type</label>
                        <select class="form-control" name="rtype" id="rtype" required>
                          <option value="" disabled selected>Choose...</option>
                          <option value="YEARLY">Yearly</option>
                          <option value="QUARTERLY">Quarterly</option>
                          <option value="MONTHLY">Monthly</option>
                          <option value="WEEKLY">Weekly</option>
                        </select>
                      </div>
                      <div class="form-group d-none" id="squarter">
                        <label for="quarter">Select Quarter</label>
                        <select class="form-control" name="quarter" id="quarter">
                          <option value="1">1st Quarter</option>
                          <option value="2">2nd Quarter</option>
                          <option value="3">3rd Quarter</option>
                          <option value="4">4th Quarter</option>
                        </select>
                      </div>
                      <div class="form-group d-none" id="smonth">
                        <label for="month">Select Month</label>
                        <select class="form-control" name="month" id="month">
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
                      <div class="form-group d-none" id="sweek">
                        <label for="week">Select Week</label>
                        <input type="number" min="1" max="53" class="form-control" name="week" id="week" value="{{date('W')}}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block" name="submit" value="report1">Report 1 (Per Barangay)</button>
                    <button type="submit" class="btn btn-primary btn-block">Report 2</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $('#rtype').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'YEARLY') {
      $('#squarter').addClass('d-none');
      $('#smonth').addClass('d-none');
      $('#sweek').addClass('d-none');

      $('#quarter').prop('required', false);
      $('#month').prop('required', false);
      $('#week').prop('required', false);
    }
    else if($(this).val() == 'QUARTERLY') {
      $('#squarter').removeClass('d-none');
      $('#smonth').addClass('d-none');
      $('#sweek').addClass('d-none');

      $('#quarter').prop('required', true);
      $('#month').prop('required', false);
      $('#week').prop('required', false);
    }
    else if($(this).val() == 'MONTHLY') {
      $('#squarter').addClass('d-none');
      $('#smonth').removeClass('d-none');
      $('#sweek').addClass('d-none');

      $('#quarter').prop('required', false);
      $('#month').prop('required', true);
      $('#week').prop('required', false);
    }
    else if($(this).val() == 'WEEKLY') {
      $('#squarter').addClass('d-none');
      $('#smonth').addClass('d-none');
      $('#sweek').removeClass('d-none');

      $('#quarter').prop('required', false);
      $('#month').prop('required', false);
      $('#week').prop('required', true);
    }
  }).trigger('change');
</script>
@endsection