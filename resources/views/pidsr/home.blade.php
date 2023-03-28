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
                                <option value="RotaVirus">RotaVirus</option>
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
@endsection