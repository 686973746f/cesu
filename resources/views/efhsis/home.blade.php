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
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#liveBirthModal">Encode Livebirths (LCR)/Natality</button>
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#liveBirthReport">Natality Report</button>
                        <hr>
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#deathModal">Mortality Menu</button>
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#deathReport">BE Export Report</button>
                        @if(auth()->user()->canAccessPregnancyTracking())
                        <hr>
                        <a href="{{route('ptracking_index')}}" class="btn btn-block btn-primary">Pregnancy Tracking</a>
                        @endif
                        <hr>
                        <a href="{{route('fhsis_tbdots_home')}}" class="btn btn-primary btn-block">TB-DOTS Morbidity</a>
                        <hr>
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#ncModal">Non-Comm</button>
                        <hr>
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#reportV2">Reports</button>
                        <!-- <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#cesum2">Generate M2</button> -->
                        <hr>
                        <a href="{{route('fhsis_icd10_searcher')}}" class="btn btn-primary btn-block">IDC10 Code Search</a>
                        <hr>
                        <a href="{{route('fhsis_pquery')}}" class="btn btn-secondary btn-block" onclick="return confirm('This will replace the existing eFHSIS Database on your system. Proceed with caution. Continue?')">Start MDB Import</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportV2" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reports</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('fhsis_2018_report')}}" method="GET">
                        <div class="card mb-3">
                            <div class="card-header"><b>Version 2018 Report and Verifier</b></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                          <label for="startDate"><b class="text-danger">*</b>Start Date</label>
                                          <input type="date" class="form-control" name="startDate" id="startDate" min="2020-01-01" max="{{date('Y-m-t')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="endDate"><b class="text-danger">*</b>End Date</label>
                                            <input type="date" class="form-control" name="endDate" id="endDate" min="2020-01-01" max="{{date('Y-m-t')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label for="program">Program</label>
                                  <select class="form-control" name="program" id="program" required>
                                    <option value="CHILD CARE">Child Care</option>
                                  </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">Generate</button>
                            </div>
                        </div>
                    </form>

                    <div class="card mb-3">
                        <div class="card-header"><b>Report V1</b></div>
                        <div class="card-body">
                            <a href="{{route('fhsis_report')}}" class="btn btn-primary btn-block">Go to Report V1</a>
                        </div>
                    </div>

                    <form action="{{route('fhsis_reportv2')}}" method="GET">
                        <div class="card">
                            <div class="card-header"><b>Report V2</b></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                          <label for="startDate"><b class="text-danger">*</b>Start Date</label>
                                          <input type="date" class="form-control" name="startDate" id="startDate" min="2020-01-01" max="{{date('Y-m-t')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="endDate"><b class="text-danger">*</b>End Date</label>
                                            <input type="date" class="form-control" name="endDate" id="endDate" min="2020-01-01" max="{{date('Y-m-t')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label for="brgy"><b class="text-danger">*</b>Select Barangay</label>
                                  <select class="form-control" name="brgy" id="brgy" required>
                                    <option value="ALL">ALL BARANGAYS IN GENERAL TRIAS</option>
                                    @foreach ($bgy_list_fhsisformat as $b)
                                        <option value="{{$b->BGY_DESC}}">{{mb_strtoupper($b->BGY_DESC)}}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">Generate</button>
                            </div>
                        </div>
                    </form>
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
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Start</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="deathModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Mortality Menu</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('fhsis_deathcert_encode')}}" method="GET">
                        <div class="card">
                            <div class="card-header"><b>Encode Mortality</b></div>
                            <div class="card-body">
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
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">Start</button>
                            </div>
                        </div>
                    </form>
                    
                    <form action="{{route('fhsis_deathcert_search')}}" method="GET">
                        <div class="card mt-3">
                            <div class="card-header"><b>Mortality Search</b></div>
                            <div class="card-body">
                                <div class="form-group">
                                  <label for="q"><b class="text-danger">*</b>Input Name (Format: Surname, First Name, Middle Name)</label>
                                  <input type="text" class="form-control" id="death_q" name="q" style="text-transform: uppercase" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success btn-block">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
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
                            <option value="ALL BARANGAYS IN GENERAL TRIAS">ALL BARANGAYS IN GENERAL TRIAS</option>
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

    <form action="{{route('fhsis_deathcert_report')}}" method="GET">
        <div class="modal fade" id="deathReport" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">BE Export Report</h5>
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
                          <label for="brgy"><b class="text-danger">*</b>Barangay</label>
                          <select class="form-control" name="brgy" id="mortBrgy" required>
                            <option value="" disabled selected>Choose...</option>
                            <option value="ALL">ALL BARANGAYS IN GENERAL TRIAS</option>
                            @foreach ($brgylist as $b)
                                <option value="{{$b->brgyName}}">{{$b->brgyName}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block" id="mortGenerateBtn" name="submit" value="generate">Generate</button>
                        <button type="submit" class="btn btn-success btn-block" id="mortDownloadBtn" name="submit" value="download">Download Mortality BE Excel File</button>
                        <button type="submit" class="btn btn-success btn-block" id="m2DownloadBtn" name="submit" value="m2download">Download M2/Noncomm BE Excel File</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('efhsis.riskassessment.modal_body')

    <script>
        $('#mortBrgy').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '') {
                $('#mortGenerateBtn').addClass('d-none');
                $('#mortDownloadBtn').addClass('d-none');
                $('#m2DownloadBtn').addClass('d-none');
            }
            else if($(this).val() == 'ALL') {
                $('#mortGenerateBtn').addClass('d-none');
                $('#mortDownloadBtn').removeClass('d-none');
                $('#m2DownloadBtn').removeClass('d-none');
            }
            else {
                $('#mortGenerateBtn').removeClass('d-none');
                $('#mortDownloadBtn').addClass('d-none');
                $('#m2DownloadBtn').addClass('d-none');
            }
        }).trigger('change');
    </script>
@endsection