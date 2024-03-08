@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div><b>eFHSIS Menu</b></div>
                            <div></div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#liveBirthModal">Encode Livebirths</button>
                        <a href="{{route('fhsis_report')}}" class="btn btn-primary btn-block">Report</a>
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#cesum2">Generate M2</button>
                        <hr>
                        <a href="{{route('fhsis_pquery')}}" class="btn btn-primary btn-block">Start MDB Import</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('fhsis_cesum2')}}" method="GET">
        <div class="modal fade" id="cesum2" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">CESU M2</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="disease">Select Disease</label>
                          <select class="form-control" name="disease" id="disease" required>
                            <option value="" disabled selected>Choose...</option>
                            <option value="AnimalBite">Animal Bite (ABTC)</option>
                            <option value="Covid">COVID-19</option>
                            <optgroup label="PIDSR Category I">
                                <option value="Dengue">Acute Flaccid Paralysis</option>
                                <option value="Dengue">Anthrax</option>
                                <option value="Dengue">Measles</option>
                                <option value="Dengue">Meningococcal Disease</option>
                                <option value="Dengue">Neonatal Tetanus</option>
                                <option value="Dengue">Paralytic Shellfish Poisoning</option>
                                <option value="Dengue">Rabies</option>
                                <option value="Dengue">Hand, Foot and Mouth Disease (HFMD)</option>
                            </optgroup>
                            <optgroup label="PIDSR Category II">
                                <option value="Dengue">Acute Bloody Diarrhea</option>
                                <option value="Dengue">Acute Encephalitis Syndrome</option>
                                <option value="Dengue">Acute Hemorrhagic Fever Syndrome</option>
                                <option value="Dengue">Acute Viral Hepatitis</option>
                                <option value="Dengue">AMES</option>
                                <option value="Dengue">Bacterial Meningitis</option>
                                <option value="Dengue">Chikungunya</option>
                                <option value="Dengue">Rabies</option>
                                <option value="Dengue">Cholera</option>
                                <option value="Dengue">Dengue</option>
                                <option value="Dengue">Dipheria</option>
                                <option value="Dengue">Influenza-like Illness</option>
                                <option value="Dengue">Leptospirosis</option>
                                <option value="Dengue">Malaria</option>
                                <option value="Dengue">Non-Neonatal Tetanus</option>
                                <option value="Dengue">Pertussis</option>
                                <option value="Dengue">RotaVirus</option>
                                <option value="Dengue">Typhoid and Parathypoid Fever</option>
                            </optgroup>
                          </select>
                        </div>
                        <div class="form-group">
                            <label for="year">Select Year</label>
                            <select class="form-control" name="year" id="year" required>
                                <option value="" disabled selected>Choose...</option>
                                @foreach(range(date('Y'), 2020) as $y)
                                <option value="{{$y}}">{{$y}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="month">Select Month</label>
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
                        <button type="submit" class="btn btn-success btn-block">View</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="{{route('fhsis_livebirth_encode')}}" method="GET">
        <div class="modal fade" id="liveBirthModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Encode Livebirths</h5>
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
                            <option value="1" {{(date('n') == 1) ? 'selected' : ''}}>January</option>
                            <option value="2" {{(date('n') == 2) ? 'selected' : ''}}>February</option>
                            <option value="3" {{(date('n') == 3) ? 'selected' : ''}}>March</option>
                            <option value="4" {{(date('n') == 4) ? 'selected' : ''}}>April</option>
                            <option value="5" {{(date('n') == 5) ? 'selected' : ''}}>May</option>
                            <option value="6" {{(date('n') == 6) ? 'selected' : ''}}>June</option>
                            <option value="7" {{(date('n') == 7) ? 'selected' : ''}}>July</option>
                            <option value="8" {{(date('n') == 8) ? 'selected' : ''}}>August</option>
                            <option value="9" {{(date('n') == 9) ? 'selected' : ''}}>September</option>
                            <option value="10" {{(date('n') == 10) ? 'selected' : ''}}>October</option>
                            <option value="11" {{(date('n') == 11) ? 'selected' : ''}}>November</option>
                            <option value="12" {{(date('n') == 12) ? 'selected' : ''}}>December</option>
                          </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Start</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection