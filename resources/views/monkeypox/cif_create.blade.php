@extends('layouts.app')

@section('content')
<form action="{{route('mp.storecif', ['record_id' => $d->id])}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header"><b>New Monkeypox Case Investigation Form (CIF)</b></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="dru_name">Name of DRU</label>
                          <input type="text"class="form-control" name="dru_name" id="dru_name" value="{{old('dru_name', 'CHO GENERAL TRIAS')}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="dru_region">Region of DRU</label>
                            <input type="text"class="form-control" name="dru_region" id="dru_region" value="{{old('dru_region', 'IV-A')}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_province">Province of DRU</label>
                            <input type="text"class="form-control" name="dru_province" id="dru_province" value="{{old('dru_province', 'CAVITE')}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_muncity">Municipality/City of DRU</label>
                            <input type="text"class="form-control" name="dru_muncity" id="dru_muncity" value="{{old('dru_muncity', 'GENERAL TRIAS')}}" style="text-transform: uppercase;" required>
                        </div>
                        <div class="form-group">
                            <label for="dru_street">Street of DRU</label>
                            <input type="text"class="form-control" name="dru_street" id="dru_street" value="{{old('dru_street', 'PRIA RD')}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_investigation">Date of Investigation</label>
                            <input type="date"class="form-control" name="date_investigation" id="date_investigation" value="{{old('date_investigation')}}" max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type of DRU</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value="C/MHO" {{(old('type') == 'C/MHO') ? 'selected' : ''}}>C/MHO</option>
                                <option value="GOVT HOSPITAL" {{(old('type') == 'GOVT HOSPITAL') ? 'selected' : ''}}>GOVT HOSPITAL</option>
                                <option value="PRIVATE HOSPITAL" {{(old('type') == 'PRIVATE HOSPITAL') ? 'selected' : ''}}>PRIVATE HOSPITAL</option>
                                <option value="AIRPORT" {{(old('type') == 'AIRPORT') ? 'selected' : ''}}>AIRPORT</option>
                                <option value="SEAPORT" {{(old('type') == 'SEAPORT') ? 'selected' : ''}}>SEAPORT</option>
                                <option value="GOVT LABORATORY" {{(old('type') == 'GOVT LABORATORY') ? 'selected' : ''}}>GOVT LABORATORY</option>
                                <option value="PRIVATE LABORATORY" {{(old('type') == 'PRIVATE LABORATORY') ? 'selected' : ''}}>PRIVATE LABORATORY</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informant_name">Name of Informant</label>
                            <input type="text"class="form-control" name="informant_name" id="informant_name" value="{{old('informant_name')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informant_relationship">Relationship with Patient</label>
                            <input type="text"class="form-control" name="informant_relationship" id="informant_relationship" value="{{old('informant_relationship')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="informant_contactnumber">Contact No. of Informant</label>
                            <input type="text"class="form-control" name="informant_contactnumber" id="informant_contactnumber" value="{{old('informant_contactnumber')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header"><b>II. PATIENT STATUS</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_admitted">Date Admitted/Seen/Consult</label>
                                    <input type="date"class="form-control" name="date_admitted" id="date_admitted" value="{{old('date_admitted')}}" max="{{date('Y-m-d')}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="admission_er">Admitted ER</label>
                                    <select class="form-control" name="admission_er" id="admission_er" required>
                                        <option value="N" {{(old('admission_er') == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_er') == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_er') == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admission_ward">Admitted Ward</label>
                                    <select class="form-control" name="admission_ward" id="admission_ward" required>
                                        <option value="N" {{(old('admission_ward') == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_ward') == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_ward') == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="admission_icu">Admitted ER</label>
                                    <select class="form-control" name="admission_icu" id="admission_icu" required>
                                        <option value="N" {{(old('admission_icu') == 'N') ? 'selected' : ''}}>NO</option>
                                        <option value="Y" {{(old('admission_icu') == 'Y') ? 'selected' : ''}}>YES</option>
                                        <option value="U" {{(old('admission_icu') == 'U') ? 'selected' : ''}}>UNKNOWN</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="other_medicalinformation">Any other known medical information</label>
                                    <input type="text"class="form-control" name="other_medicalinformation" id="other_medicalinformation" value="{{old('other_medicalinformation')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ifhashistory_blooddonation_transfusion">Blood Donation/Transfusion History</label>
                                    <select class="form-control" name="ifhashistory_blooddonation_transfusion" id="ifhashistory_blooddonation_transfusion">
                                        <option value="" {{(old('ifhashistory_blooddonation_transfusion') == '') ? 'selected' : ''}}>N/A</option>
                                        <option value="DONOR" {{(old('ifhashistory_blooddonation_transfusion') == 'DONOR') ? 'selected' : ''}}>DONOR</option>
                                        <option value="RECIPIENT" {{(old('ifhashistory_blooddonation_transfusion') == 'RECIPIENT') ? 'selected' : ''}}>RECIPIENT</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ifhashistory_blooddonation_transfusion_place">Place of Donation/Transfusion</label>
                                    <input type="text"class="form-control" name="ifhashistory_blooddonation_transfusion_place" id="ifhashistory_blooddonation_transfusion_place" value="{{old('ifhashistory_blooddonation_transfusion_place')}}" style="text-transform: uppercase;">
                                </div>
                                <div class="form-group">
                                    <label for="ifhashistory_blooddonation_transfusion_date">Date of Donation/Transfusion</label>
                                    <input type="text"class="form-control" name="ifhashistory_blooddonation_transfusion_date" id="ifhashistory_blooddonation_transfusion_date" value="{{old('ifhashistory_blooddonation_transfusion_date')}}" max="{{date('Y-m-d')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><b>III. CLINICAL HISTORY/PRESENTATION</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection