@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('vaxcert_processpatient', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">View VaxCert Concern Ticket - No. {{$d->id}}</div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="concern_type"><span class="text-danger font-weight-bold">*</span>Concern Type</label>
                            <select class="form-control" name="concern_type" id="concern_type" disabled>
                                  <option value="MISSING DOSE" {{($d->concern_type == 'MISSING DOSE') ? 'selected' : ''}}>Missing Dose/May kulang na Dose</option>
                                  <option value="CORRECTION" {{($d->concern_type == 'CORRECTION') ? 'selected' : ''}}>Correction/Itatama ang Detalye (Wrong Name/Birthdate/etc.)</option>
                                  <option value="OTHERS" {{($d->concern_type == 'OTHERS') ? 'selected' : ''}}>Others/Iba pa</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category"><span class="text-danger font-weight-bold">*</span>Category</label>
                            <select class="form-control" name="category" id="category" disabled>
                                  <option value="A1" {{($d->category == 'A1') ? 'selected' : ''}}>A1</option>
                                  <option value="A1.8" {{($d->category == 'A1.8') ? 'selected' : ''}}>A1.8</option>
                                  <option value="A1.9" {{($d->category == 'A1') ? 'selected' : ''}}>A1.9</option>
                                  <option value="A2" {{($d->category == 'A2') ? 'selected' : ''}}>A2</option>
                                  <option value="A3 - IMMUNOCOMPETENT" {{($d->category == 'A3 - IMMUNOCOMPETENT') ? 'selected' : ''}}>A3 - Immunocompetent</option>
                                  <option value="A3 - IMMUNOCOMPROMISED" {{($d->category == 'A3 - IMMUNOCOMPROMISED') ? 'selected' : ''}}>A3 - Immunocompromised</option>
                                  <option value="A4" {{($d->category == 'A4') ? 'selected' : ''}}>A4</option>
                                  <option value="A5" {{($d->category == 'A5') ? 'selected' : ''}}>A5</option>
                                  <option value="ADDITIONAL A1" {{($d->category == 'ADDITIONAL A1') ? 'selected' : ''}}>Additional A1</option>
                                  <option value="EXPANDED A3" {{($d->category == 'EXPANDED A3') ? 'selected' : ''}}>Expanded A3</option>
                                  <option value="PEDRIATRIC A3 (12-17 YEARS OLD)" {{($d->category == 'PEDRIATRIC A3 (12-17 YEARS OLD)') ? 'selected' : ''}}>Pediatric A3 (12-17 years old)</option>
                                  <option value="PEDRIATRIC A3 (5-11 YEARS OLD)" {{($d->category == 'PEDRIATRIC A3 (5-11 YEARS OLD)') ? 'selected' : ''}}>Pediatric A3 (5-11 years old)</option>
                                  <option value="ROAP" {{($d->category == 'ROAP') ? 'selected' : ''}}>ROAP</option>
                                  <option value="ROPP (12-17 YEARS OLD)" {{($d->category == 'ROPP (12-17 YEARS OLD)') ? 'selected' : ''}}>ROPP (12-17 years old)</option>
                                  <option value="ROPP (5-11 YEARS OLD)" {{($d->category == 'ROPP (5-11 YEARS OLD)') ? 'selected' : ''}}>ROPP (5-11 years old)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="vaxcert_refno">VaxCertPH Ticket Reference No.</label>
                          <input type="text" name="vaxcert_refno" id="vaxcert_refno" class="form-control" value="{{$d->vaxcert_refno}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="vaxcard_uniqueid">Vaccination Card ID No./Unique Person ID</label>
                          <input type="text" class="form-control" name="vaxcard_uniqueid" id="vaxcard_uniqueid" value="{{$d->vaxcard_uniqueid}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="comorbidity">Comorbidity <i>(Leave blank if empty)</i></label>
                            <input type="text" class="form-control" name="comorbidity" id="comorbidity" value="{{$d->comorbidity}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="pwd_yn"><span class="text-danger font-weight-bold">*</span>Person with Disability (PWD)</label>
                            <select class="form-control" name="pwd_yn" id="pwd_yn" required>
                              <option value="N" {{($d->pwd_yn == 'N') ? 'selected' : ''}}>No</option>
                              <option value="Y" {{($d->pwd_yn == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="concern_msg"><span class="text-danger font-weight-bold">*</span>Specific Concern Message</label>
                    <textarea class="form-control" name="concern_msg" id="concern_msg" rows="3" disabled>{{mb_strtoupper($d->concern_msg)}}</textarea>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="last_name"><span class="text-danger font-weight-bold">*</span>Last Name (Apelyido)</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="{{$d->last_name}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="DELA CRUZ" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="first_name"><span class="text-danger font-weight-bold">*</span>First Name (Unang Pangalan)</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="{{$d->first_name}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="JUAN" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="middle_name">Middle Name (Gitnang Pangalan)</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{$d->middle_name}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="SANCHEZ">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="suffix">Suffix <small>(ex. JR, SR, II, III, etc.)</small></label>
                            <input type="text" class="form-control" id="suffix" name="suffix" value="{{$d->suffix}}" minlength="2" maxlength="6" style="text-transform: uppercase;">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                          <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex/Kasarian</label>
                          <select class="form-control" name="gender" id="gender" required>
                            <option value="M" {{($d->gender == 'M') ? 'selected' : ''}}>Male/Lalaki</option>
                            <option value="F" {{($d->gender == 'F') ? 'selected' : ''}}>Female/Babae</option>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                            <input type="date" class="form-control" id="bdate" name="bdate" value="{{$d->bdate}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                        </div>
                        Age: {{$d->getAge()}}
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="contact_number"><span class="text-danger font-weight-bold">*</span>Mobile Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{$d->contact_number}}" pattern="[0-9]{11}" placeholder="09*********" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" class="form-control" name="email" id="email" value="{{$d->email}}">
                        </div>
                    </div>
                </div>
                @if(!is_null($d->glast_name))
                <div id="ifguardian">
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="glast_name"><span class="text-danger font-weight-bold">*</span>Guardian's Surname</label>
                                <input type="text" class="form-control" id="glast_name" name="glast_name" value="{{old('glast_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gfirst_name"><span class="text-danger font-weight-bold">*</span>Guardian's First Name</label>
                                <input type="text" class="form-control" id="gfirst_name" name="gfirst_name" value="{{old('gfirst_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <hr>
                <div id="address_text" class="d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text', $d->address_region_text)}}" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_province_text', $d->address_province_text)}}" readonly>
                        </div>
                        <div class="col-md-6">
                            <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_muncity_text', $d->address_muncity_text)}}"readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                          <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                          <select class="form-control" name="address_region_code" id="address_region_code" required>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                            <select class="form-control" name="address_province_code" id="address_province_code" required>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                            <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                            <select class="form-control" name="address_brgy_text" id="address_brgy_text" required>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="howmanydose"><span class="text-danger font-weight-bold">*</span>Number of dose finished/Ilang bakuna ang natapos</label>
                    <select class="form-control" name="howmanydose" id="howmanydose" required>
                        <option value="1" {{(old('howmanydose', $d->getNumberOfDose()) == 1) ? 'selected' : ''}}>1st Dose Only</option>
                        <option value="2" {{(old('howmanydose', $d->getNumberOfDose()) == 2) ? 'selected' : ''}}>1st and 2nd Dose</option>
                        <option value="3" {{(old('howmanydose', $d->getNumberOfDose()) == 3) ? 'selected' : ''}}>1st, 2nd, and 3rd Dose (1st Booster)</option>
                        <option value="4" {{(old('howmanydose', $d->getNumberOfDose()) == 4) ? 'selected' : ''}}>1st, 2nd, 3rd (1st Booster), and 4th Dose (2nd Booster)</option>
                    </select>
                </div>
                <div id="vaccine1" class="d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose1_date"><span class="text-danger font-weight-bold">*</span>1ST Dose Date</label>
                                <input type="date" class="form-control" name="dose1_date" id="dose1_date" value="{{old('dose1_date', $d->dose1_date)}}" min="2021-01-01" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="dose1_manufacturer"><span class="text-danger font-weight-bold">*</span>1ST Dose Manufacturer</label>
                            <select class="form-control" name="dose1_manufacturer" id="dose1_manufacturer" required>
                                <option value="AZ" {{(old('dose1_manufacturer', $d->dose1_manufacturer) == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                <option value="J&J" {{(old('dose1_manufacturer', $d->dose1_manufacturer) == 'J&J') ? 'selected' : ''}}>Johnson and Johnson (J&J)/Janssen</option>
                                <option value="Moderna" {{(old('dose1_manufacturer', $d->dose1_manufacturer) == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                <option value="Novavax" {{(old('dose1_manufacturer', $d->dose1_manufacturer) == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                <option value="Pfizer" {{(old('dose1_manufacturer', $d->dose1_manufacturer) == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                <option value="Sinohpharm" {{(old('dose1_manufacturer', $d->dose1_manufacturer) == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                <option value="Sinovac" {{(old('dose1_manufacturer', $d->dose1_manufacturer) == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                <option value="SputnikLight" {{(old('dose1_manufacturer', $d->dose1_manufacturer) == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                                <option value="Gamaleya" {{(old('dose1_manufacturer', $d->dose1_manufacturer) == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose1_inmainlgu_yn"><span class="text-danger font-weight-bold">*</span>1ST Dose Vaccinated here in GenTri?</label>
                                <select class="form-control" name="dose1_inmainlgu_yn" id="dose1_inmainlgu_yn" required>
                                  <option value="Y" {{(old('dose1_inmainlgu_yn', $d->dose1_inmainlgu_yn) == 'Y') ? 'selected' : ''}}>Oo/Yes</option>
                                  <option value="N" {{(old('dose1_inmainlgu_yn', $d->dose1_inmainlgu_yn) == 'N') ? 'selected' : ''}}>Hindi/No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose1_bakuna_center_text"><span class="text-danger font-weight-bold">*</span>Vaccination Site/Lugar kung saan binakunahan</label>
                                <input type="text" class="form-control" name="dose1_bakuna_center_text" id="dose1_bakuna_center_text" value="{{mb_strtoupper(old('dose1_bakuna_center_text', mb_strtoupper($d->dose1_bakuna_center_text)))}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($d->dose1_inmainlgu_yn == 'Y')
                            <div class="form-group">
                              <label for="dose1_bakuna_center_code"><span class="text-danger font-weight-bold">*</span>Select CBCR ID Based on Vaccination Site</label>
                              <select class="form-control" name="dose1_bakuna_center_code" id="dose1_bakuna_center_code">
                                <option disabled {{(is_null(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code))) ? 'selected' : ''}}>Choose...</option>
                                <option value="CBC000000000002325" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000002325') ? 'selected' : ''}}>CHO GENERAL TRIAS</option>
                                <option value="CBC000000000005586" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000005586') ? 'selected' : ''}}>CITY OF GENERAL TRIAS DOCTORS MEDICAL CENTER</option>
                                <option value="CBC000000000006637" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000006637') ? 'selected' : ''}}>CONVENTION</option>
                                <option value="CBC000000000009906" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000009906') ? 'selected' : ''}}>DBA VACCINATION FACILITY</option>
                                <option value="CBC000000000005588" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000005588') ? 'selected' : ''}}>DIVINE GRACE MEDICAL HOSPITAL (DGMC)</option>
                                <option value="CBC000000000005587" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000005587') ? 'selected' : ''}}>GENTRIMEDICAL CENTER AND HOSPITAL (GENTRIMED)</option>
                                <option value="CBC000000000007746" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000007746') ? 'selected' : ''}}>MOBILE VACCINATION</option>
                                <option value="CBC000000000007459" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000007459') ? 'selected' : ''}}>ROBINSONS PLACE GENENERAL TRIAS</option>
                                <option value="CBC000000000008481" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000008481') ? 'selected' : ''}}>SSMC GATEWAY VACCINATION CENTER</option>
                                <option value="CBC000000000008932" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000008932') ? 'selected' : ''}}>ST. EDUARD INTEGRATED SCHOOL LNC / LGU GENTRIAS</option>
                                <option value="CBC000000000007978" {{(old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code) == 'CBC000000000007978') ? 'selected' : ''}}>VISTA MALL SAN FRANCISCO / RED CROSS</option>
                              </select>
                            </div>
                            @else
                            <div class="form-group">
                                <label for="dose1_bakuna_center_code"><span class="text-danger font-weight-bold">*</span>Input Specific CBCR Code</label>
                                <input type="text" class="form-control" name="dose1_bakuna_center_code" id="dose1_bakuna_center_code" value="{{old('dose1_bakuna_center_code', $d->dose1_bakuna_center_code)}}">
                                <small>List of CBCR can be found - <a href="https://cbcr.doh.gov.ph/Covid19BakunaCenterRegistryList">HERE</a></small>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose1_batchno"><span class="text-danger font-weight-bold">*</span>Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose1_batchno" id="dose1_batchno" value="{{mb_strtoupper(old('dose1_batchno', $d->dose1_batchno))}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose1_vaccinator_name"><span class="text-danger font-weight-bold">*</span>Name of Vaccinator</label>
                                <input type="text" class="form-control" name="dose1_vaccinator_name" id="dose1_vaccinator_name" value="{{mb_strtoupper(old('dose1_vaccinator_name', $d->dose1_vaccinator_name))}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="vaccine2" class="d-none">
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose2_date"><span class="text-danger font-weight-bold">*</span>2ND Dose Date</label>
                                <input type="date" class="form-control" name="dose2_date" id="dose2_date" value="{{old('dose2_date', $d->dose2_date)}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="dose2_manufacturer"><span class="text-danger font-weight-bold">*</span>2ND Dose Manufacturer</label>
                            <select class="form-control" name="dose2_manufacturer" id="dose2_manufacturer">
                                <option value="AZ" {{(old('dose2_manufacturer', $d->dose2_manufacturer) == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                <option value="J&J" {{(old('dose2_manufacturer', $d->dose2_manufacturer) == 'J&J') ? 'selected' : ''}}>Johnson and Johnson (J&J)/Janssen</option>
                                <option value="Moderna" {{(old('dose2_manufacturer', $d->dose2_manufacturer) == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                <option value="Novavax" {{(old('dose2_manufacturer', $d->dose2_manufacturer) == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                <option value="Pfizer" {{(old('dose2_manufacturer', $d->dose2_manufacturer) == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                <option value="Sinohpharm" {{(old('dose2_manufacturer', $d->dose2_manufacturer) == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                <option value="Sinovac" {{(old('dose2_manufacturer', $d->dose2_manufacturer) == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                <option value="SputnikLight" {{(old('dose2_manufacturer', $d->dose2_manufacturer) == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                                <option value="Gamaleya" {{(old('dose2_manufacturer', $d->dose2_manufacturer) == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose2_inmainlgu_yn"><span class="text-danger font-weight-bold">*</span>2ND Dose Vaccinated here in GenTri?</label>
                                <select class="form-control" name="dose2_inmainlgu_yn" id="dose2_inmainlgu_yn" required>
                                  <option value="Y" {{(old('dose2_inmainlgu_yn' , $d->dose2_inmainlgu_yn) == 'Y') ? 'selected' : ''}}>Oo/Yes</option>
                                  <option value="N" {{(old('dose2_inmainlgu_yn' , $d->dose2_inmainlgu_yn) == 'N') ? 'selected' : ''}}>Hindi/No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose2_bakuna_center_text"><span class="text-danger font-weight-bold">*</span>Vaccination Site/Lugar kung saan binakunahan</label>
                                <input type="text" class="form-control" name="dose2_bakuna_center_text" id="dose2_bakuna_center_text" value="{{mb_strtoupper(old('dose2_bakuna_center_text', $d->dose2_bakuna_center_text))}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($d->dose2_inmainlgu_yn == 'Y')
                            <div class="form-group">
                              <label for="dose2_bakuna_center_code"><span class="text-danger font-weight-bold">*</span>Select CBCR ID Based on Vaccination Site</label>
                              <select class="form-control" name="dose2_bakuna_center_code" id="dose2_bakuna_center_code">
                                <option disabled {{(is_null(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code))) ? 'selected' : ''}}>Choose...</option>
                                <option value="CBC000000000002325" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000002325') ? 'selected' : ''}}>CHO GENERAL TRIAS</option>
                                <option value="CBC000000000005586" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000005586') ? 'selected' : ''}}>CITY OF GENERAL TRIAS DOCTORS MEDICAL CENTER</option>
                                <option value="CBC000000000006637" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000006637') ? 'selected' : ''}}>CONVENTION</option>
                                <option value="CBC000000000009906" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000009906') ? 'selected' : ''}}>DBA VACCINATION FACILITY</option>
                                <option value="CBC000000000005588" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000005588') ? 'selected' : ''}}>DIVINE GRACE MEDICAL HOSPITAL (DGMC)</option>
                                <option value="CBC000000000005587" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000005587') ? 'selected' : ''}}>GENTRIMEDICAL CENTER AND HOSPITAL (GENTRIMED)</option>
                                <option value="CBC000000000007746" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000007746') ? 'selected' : ''}}>MOBILE VACCINATION</option>
                                <option value="CBC000000000007459" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000007459') ? 'selected' : ''}}>ROBINSONS PLACE GENENERAL TRIAS</option>
                                <option value="CBC000000000008481" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000008481') ? 'selected' : ''}}>SSMC GATEWAY VACCINATION CENTER</option>
                                <option value="CBC000000000008932" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000008932') ? 'selected' : ''}}>ST. EDUARD INTEGRATED SCHOOL LNC / LGU GENTRIAS</option>
                                <option value="CBC000000000007978" {{(old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code) == 'CBC000000000007978') ? 'selected' : ''}}>VISTA MALL SAN FRANCISCO / RED CROSS</option>
                              </select>
                            </div>
                            @else
                            <div class="form-group">
                                <label for="dose2_bakuna_center_code"><span class="text-danger font-weight-bold">*</span>Input Specific CBCR Code</label>
                                <input type="text" class="form-control" name="dose2_bakuna_center_code" id="dose2_bakuna_center_code" value="{{old('dose2_bakuna_center_code', $d->dose2_bakuna_center_code)}}">
                                <small>List of CBCR can be found - <a href="https://cbcr.doh.gov.ph/Covid19BakunaCenterRegistryList">HERE</a></small>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose2_batchno"><span class="text-danger font-weight-bold">*</span>Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose2_batchno" id="dose2_batchno" value="{{mb_strtoupper(old('dose2_batchno', $d->dose2_batchno))}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose2_vaccinator_name"><span class="text-danger font-weight-bold">*</span>Name of Vaccinator</label>
                                <input type="text" class="form-control" name="dose2_vaccinator_name" id="dose2_vaccinator_name" value="{{mb_strtoupper(old('dose2_vaccinator_name', $d->dose2_vaccinator_name))}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="vaccine3" class="d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose3_date"><span class="text-danger font-weight-bold">*</span>3RD Dose (Booster 1) Date</label>
                                <input type="date" class="form-control" name="dose3_date" id="dose3_date" value="{{old('dose3_date', $d->dose3_date)}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="dose3_manufacturer"><span class="text-danger font-weight-bold">*</span>3RD Dose (Booster 1) Manufacturer</label>
                            <select class="form-control" name="dose3_manufacturer" id="dose3_manufacturer">
                                <option value="AZ" {{(old('dose3_manufacturer', $d->dose3_manufacturer) == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                <option value="J&J" {{(old('dose3_manufacturer', $d->dose3_manufacturer) == 'J&J') ? 'selected' : ''}}>Johnson and Johnson (J&J)/Janssen</option>
                                <option value="Moderna" {{(old('dose3_manufacturer', $d->dose3_manufacturer) == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                <option value="Novavax" {{(old('dose3_manufacturer', $d->dose3_manufacturer) == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                <option value="Pfizer" {{(old('dose3_manufacturer', $d->dose3_manufacturer) == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                <option value="Sinohpharm" {{(old('dose3_manufacturer', $d->dose3_manufacturer) == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                <option value="Sinovac" {{(old('dose3_manufacturer', $d->dose3_manufacturer) == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                <option value="SputnikLight" {{(old('dose3_manufacturer', $d->dose3_manufacturer) == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                                <option value="Gamaleya" {{(old('dose3_manufacturer', $d->dose3_manufacturer) == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose3_inmainlgu_yn"><span class="text-danger font-weight-bold">*</span>3RD Dose Vaccinated here in GenTri?</label>
                                <select class="form-control" name="dose3_inmainlgu_yn" id="dose3_inmainlgu_yn" required>
                                  <option value="Y" {{(old('dose3_inmainlgu_yn', $d->dose3_inmainlgu_yn) == 'Y') ? 'selected' : ''}}>Oo/Yes</option>
                                  <option value="N" {{(old('dose3_inmainlgu_yn', $d->dose3_inmainlgu_yn) == 'N') ? 'selected' : ''}}>Hindi/No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose3_bakuna_center_text"><span class="text-danger font-weight-bold">*</span>Vaccination Site/Lugar kung saan binakunahan</label>
                                <input type="text" class="form-control" name="dose3_bakuna_center_text" id="dose3_bakuna_center_text" value="{{mb_strtoupper(old('dose3_bakuna_center_text', $d->dose3_bakuna_center_text))}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($d->dose3_inmainlgu_yn == 'Y')
                            <div class="form-group">
                              <label for="dose3_bakuna_center_code"><span class="text-danger font-weight-bold">*</span>Select CBCR ID Based on Vaccination Site</label>
                              <select class="form-control" name="dose3_bakuna_center_code" id="dose3_bakuna_center_code">
                                <option disabled {{(is_null(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code))) ? 'selected' : ''}}>Choose...</option>
                                <option value="CBC000000000002325" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000002325') ? 'selected' : ''}}>CHO GENERAL TRIAS</option>
                                <option value="CBC000000000005586" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000005586') ? 'selected' : ''}}>CITY OF GENERAL TRIAS DOCTORS MEDICAL CENTER</option>
                                <option value="CBC000000000006637" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000006637') ? 'selected' : ''}}>CONVENTION</option>
                                <option value="CBC000000000009906" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000009906') ? 'selected' : ''}}>DBA VACCINATION FACILITY</option>
                                <option value="CBC000000000005588" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000005588') ? 'selected' : ''}}>DIVINE GRACE MEDICAL HOSPITAL (DGMC)</option>
                                <option value="CBC000000000005587" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000005587') ? 'selected' : ''}}>GENTRIMEDICAL CENTER AND HOSPITAL (GENTRIMED)</option>
                                <option value="CBC000000000007746" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000007746') ? 'selected' : ''}}>MOBILE VACCINATION</option>
                                <option value="CBC000000000007459" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000007459') ? 'selected' : ''}}>ROBINSONS PLACE GENENERAL TRIAS</option>
                                <option value="CBC000000000008481" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000008481') ? 'selected' : ''}}>SSMC GATEWAY VACCINATION CENTER</option>
                                <option value="CBC000000000008932" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000008932') ? 'selected' : ''}}>ST. EDUARD INTEGRATED SCHOOL LNC / LGU GENTRIAS</option>
                                <option value="CBC000000000007978" {{(old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code) == 'CBC000000000007978') ? 'selected' : ''}}>VISTA MALL SAN FRANCISCO / RED CROSS</option>
                              </select>
                            </div>
                            @else
                            <div class="form-group">
                                <label for="dose3_bakuna_center_code"><span class="text-danger font-weight-bold">*</span>Input Specific CBCR Code</label>
                                <input type="text" class="form-control" name="dose3_bakuna_center_code" id="dose3_bakuna_center_code" value="{{old('dose3_bakuna_center_code', $d->dose3_bakuna_center_code)}}">
                                <small>List of CBCR can be found - <a href="https://cbcr.doh.gov.ph/Covid19BakunaCenterRegistryList">HERE</a></small>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose3_batchno"><span class="text-danger font-weight-bold">*</span>Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose3_batchno" id="dose3_batchno" value="{{mb_strtoupper(old('dose3_batchno', $d->dose3_batchno))}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose3_vaccinator_name"><span class="text-danger font-weight-bold">*</span>Name of Vaccinator</label>
                                <input type="text" class="form-control" name="dose3_vaccinator_name" id="dose3_vaccinator_name" value="{{mb_strtoupper(old('dose3_vaccinator_name', $d->dose3_vaccinator_name))}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="vaccine4" class="d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose4_date"><span class="text-danger font-weight-bold">*</span>4TH Dose (Booster 2) Date</label>
                                <input type="date" class="form-control" name="dose4_date" id="dose4_date" value="{{old('dose4_date', $d->dose4_date)}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="dose4_manufacturer"><span class="text-danger font-weight-bold">*</span>4TH Dose (Booster 2) Manufacturer</label>
                            <select class="form-control" name="dose4_manufacturer" id="dose4_manufacturer">
                                <option value="AZ" {{(old('dose4_manufacturer', $d->dose4_manufacturer) == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                <option value="J&J" {{(old('dose4_manufacturer' , $d->dose4_manufacturer) == 'J&J') ? 'selected' : ''}}>Johnson and Johnson (J&J)/Janssen</option>
                                <option value="Moderna" {{(old('dose4_manufacturer' , $d->dose4_manufacturer) == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                <option value="Novavax" {{(old('dose4_manufacturer' , $d->dose4_manufacturer) == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                <option value="Pfizer" {{(old('dose4_manufacturer' , $d->dose4_manufacturer) == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                <option value="Sinohpharm" {{(old('dose4_manufacturer' , $d->dose4_manufacturer) == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                <option value="Sinovac" {{(old('dose4_manufacturer' , $d->dose4_manufacturer) == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                <option value="SputnikLight" {{(old('dose4_manufacturer' , $d->dose4_manufacturer) == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                                <option value="Gamaleya" {{(old('dose4_manufacturer' , $d->dose4_manufacturer) == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose4_inmainlgu_yn"><span class="text-danger font-weight-bold">*</span>4TH Dose Vaccinated here in GenTri?</label>
                                <select class="form-control" name="dose4_inmainlgu_yn" id="dose4_inmainlgu_yn" required>
                                  <option value="Y" {{(old('dose4_inmainlgu_yn', $d->dose4_inmainlgu_yn) == 'Y') ? 'selected' : ''}}>Oo/Yes</option>
                                  <option value="N" {{(old('dose4_inmainlgu_yn', $d->dose4_inmainlgu_yn) == 'N') ? 'selected' : ''}}>Hindi/No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose4_bakuna_center_text"><span class="text-danger font-weight-bold">*</span>Vaccination Site/Lugar kung saan binakunahan</label>
                                <input type="text" class="form-control" name="dose4_bakuna_center_text" id="dose4_bakuna_center_text" value="{{mb_strtoupper(old('dose4_bakuna_center_text', $d->dose4_bakuna_center_text))}}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($d->dose4_inmainlgu_yn == 'Y')
                            <div class="form-group">
                              <label for="dose4_bakuna_center_code"><span class="text-danger font-weight-bold">*</span>Select CBCR ID Based on Vaccination Site</label>
                              <select class="form-control" name="dose4_bakuna_center_code" id="dose4_bakuna_center_code">
                                <option disabled {{(is_null(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code))) ? 'selected' : ''}}>Choose...</option>
                                <option value="CBC000000000002325" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000002325') ? 'selected' : ''}}>CHO GENERAL TRIAS</option>
                                <option value="CBC000000000005586" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000005586') ? 'selected' : ''}}>CITY OF GENERAL TRIAS DOCTORS MEDICAL CENTER</option>
                                <option value="CBC000000000006637" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000006637') ? 'selected' : ''}}>CONVENTION</option>
                                <option value="CBC000000000009906" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000009906') ? 'selected' : ''}}>DBA VACCINATION FACILITY</option>
                                <option value="CBC000000000005588" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000005588') ? 'selected' : ''}}>DIVINE GRACE MEDICAL HOSPITAL (DGMC)</option>
                                <option value="CBC000000000005587" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000005587') ? 'selected' : ''}}>GENTRIMEDICAL CENTER AND HOSPITAL (GENTRIMED)</option>
                                <option value="CBC000000000007746" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000007746') ? 'selected' : ''}}>MOBILE VACCINATION</option>
                                <option value="CBC000000000007459" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000007459') ? 'selected' : ''}}>ROBINSONS PLACE GENENERAL TRIAS</option>
                                <option value="CBC000000000008481" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000008481') ? 'selected' : ''}}>SSMC GATEWAY VACCINATION CENTER</option>
                                <option value="CBC000000000008932" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000008932') ? 'selected' : ''}}>ST. EDUARD INTEGRATED SCHOOL LNC / LGU GENTRIAS</option>
                                <option value="CBC000000000007978" {{(old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code) == 'CBC000000000007978') ? 'selected' : ''}}>VISTA MALL SAN FRANCISCO / RED CROSS</option>
                              </select>
                            </div>
                            @else
                            <div class="form-group">
                                <label for="dose4_bakuna_center_code"><span class="text-danger font-weight-bold">*</span>Input Specific CBCR Code</label>
                                <input type="text" class="form-control" name="dose4_bakuna_center_code" id="dose4_bakuna_center_code" value="{{old('dose4_bakuna_center_code', $d->dose4_bakuna_center_code)}}">
                                <small>List of CBCR can be found - <a href="https://cbcr.doh.gov.ph/Covid19BakunaCenterRegistryList">HERE</a></small>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose4_batchno"><span class="text-danger font-weight-bold">*</span>Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose4_batchno" id="dose4_batchno" value="{{mb_strtoupper(old('dose4_batchno', $d->dose4_batchno))}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dose4_vaccinator_name"><span class="text-danger font-weight-bold">*</span>Name of Vaccinator</label>
                                <input type="text" class="form-control" name="dose4_vaccinator_name" id="dose4_vaccinator_name" value="{{mb_strtoupper(old('dose4_vaccinator_name', $d->dose4_vaccinator_name))}}">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="submit" value="update">Update Record</button>
                <hr>
                <ul>
                    <li><b>Step 1:</b> Verify <a href="{{$_SERVER['DOCUMENT_ROOT'].'_html/assets/vaxcert/patients/'.$d->id_file)}}" target="_blank">Submitted ID</a> and <a href="{{$_SERVER['DOCUMENT_ROOT'].'_html/assets/vaxcert/patients/'.$d->id_file)}}" target="_blank">Vaccination Card</a> of the patient.</li>
                    <li><b>Step 2:</b> Login to VAS Line List system first.</li>
                    <li>
                        <b>Step 3:</b>
                        <ul>
                            @if(!is_null($d->vaxcert_refno))
                            <li>Search Ref. No in <b>Correction Request</b> - <a href="https://vaslinelist.dict.gov.ph/vaxcert/correction?lastname={{$d->vaxcert_refno}}" target="_blank">HERE</a></li>
                            <li>Search Ref. No in <b>Not Found Request</b> - <a href="https://vaslinelist.dict.gov.ph/vaxcert/not-found?lastname={{$d->vaxcert_refno}}" target="_blank">HERE</a></li>
                            @endif
                            <li>Search Name of Patient in <b>Correction Request</b> by clicking - <a href="https://vaslinelist.dict.gov.ph/vaxcert/correction?lastname={{$d->last_name}}&firstname={{$d->first_name}}" target="_blank">HERE</a></li>
                            <li>Search Name of Patient in <b>Not Found Request</b> by clicking - <a href="https://vaslinelist.dict.gov.ph/vaxcert/not-found?lastname={{$d->last_name}}&firstname={{$d->first_name}}" target="_blank">HERE</a></li>
                        </ul>
                    </li>
                    <h6>(Kung may ticket ang Patient, wag na mag-proceed sa Step 3 at i-update na lang ang Ticket at i-close pagkatapos. Kung wala, proceed to Step 3)</h6>
                    <h6>------------</h6>
                    <li>
                        <b>Step 4:</b>
                        <ul>
                            <li>Search and check record of patient in Vacinee Query by clicking - <a href="https://vaslinelist.dict.gov.ph/linelist-dynamo-query?page=1&size=20&lastname={{$d->last_name}}&firstname={{$d->first_name}}&birthdate={{date('Y-m-d', strtotime($d->bdate))}}{{(!is_null($d->suffix)) ? '&suffix='.$d->suffix : ''}}" target="_blank">HERE</a></li>
                            <h6>(Kung may lumabas, i-check at i-update ang mga details)</h6>
                            @if(date('d', strtotime($d->bdate)) <= 12)
                            <ul>
                                <li>IF NOT FOUND, It is possible that the Birthdate of Patient was reversed, you can check it by clicking - <a href="https://vaslinelist.dict.gov.ph/linelist-dynamo-query?page=1&size=20&lastname={{$d->last_name}}&firstname={{$d->first_name}}&birthdate={{date('Y-d-m', strtotime($d->bdate))}}{{(!is_null($d->suffix)) ? '&suffix='.$d->suffix : ''}}" target="_blank">HERE</a></li>
                                <h6>(Kung may lumabas, itama ang birthdate ng patient at i-submit para ma-update)</h6>
                            </ul>
                            @endif
                            <h6>(Kung kumpleto na ang bakuna after updating, wag na mag-proceed sa Step 4 at pindutin na ang Complete button sa ibaba ng page na ito)</h6>
                        </ul>
                    </li>
                    <h6>------------</h6>
                    <li>
                        <b>Step 5:</b>
                        <ul>
                            <li>Download Patient Linelist Template by clicking - <a href="{{route('vaxcert_basedl', $d->id)}}">HERE</a></li>
                            <li>Go to <a href="https://vaslinelist.dict.gov.ph/vas-line-import/approved">VAS Linelist Import</a> and upload the downloaded Excel (.XLSX) file.</li>
                            <li>Use <b class="text-info">cesugentri.vaxcert@gmail.com</b> as the email for uploading the linelist.</li>
                        </ul>
                    </li>
                </ul>
                <!--
                    <a href="{{route('vaxcert_offdl', $d->id)}}" class="btn btn-primary btn-block">Download Offline Template</a>
                    
                -->
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-danger mr-3" name="submit" value="reject">Reject</button>
                <button type="submit" class="btn btn-success" name="submit" value="complete">Complete</button>
            </div>
        </div>
    </form>
</div>

<script>
    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
        theme: 'bootstrap',
    });

    var rdefault = '{{$d->address_region_code}}';
    var pdefault = '{{$d->address_province_code}}';
    var cdefault = '{{$d->address_muncity_code}}';
    var bdefault = '{{$d->address_brgy_text}}';

    //Region Select Initialize
    $.getJSON("{{asset('json/refregion.json')}}", function(data) {
        var sorted = data.sort(function(a, b) {
            if (a.regDesc > b.regDesc) {
                return 1;
            }
            if (a.regDesc < b.regDesc) {
                return -1;
            }

            return 0;
        });

        $.each(sorted, function(key, val) {
            $('#address_region_code').append($('<option>', {
                value: val.regCode,
                text: val.regDesc,
                selected: (val.regCode == rdefault) ? true : false, //default is Region IV-A
            }));
        });
    });

    $('#address_region_code').change(function (e) { 
        e.preventDefault();
        //Empty and Disable
        $('#address_province_code').empty();
        $("#address_province_code").append('<option value="" selected disabled>Choose...</option>');

        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', true);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_region_text').val($('#address_region_code option:selected').text());

        $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.provDesc > b.provDesc) {
                return 1;
                }
                if (a.provDesc < b.provDesc) {
                return -1;
                }
                return 0;
            });

            $.each(sorted, function(key, val) {
                if($('#address_region_code').val() == val.regCode) {
                    $('#address_province_code').append($('<option>', {
                        value: val.provCode,
                        text: val.provDesc,
                        selected: (val.provCode == pdefault) ? true : false, //default for Cavite
                    }));
                }
            });
        });
    }).trigger('change');

    $('#address_province_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_muncity_code').empty();
        $("#address_muncity_code").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', true);

        //Set Values for Hidden Box
        $('#address_province_text').val($('#address_province_code option:selected').text());

        $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.citymunDesc > b.citymunDesc) {
                    return 1;
                }
                if (a.citymunDesc < b.citymunDesc) {
                    return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_province_code').val() == val.provCode) {
                    $('#address_muncity_code').append($('<option>', {
                        value: val.citymunCode,
                        text: val.citymunDesc,
                        selected: (val.citymunCode == cdefault) ? true : false, //default for General Trias
                    })); 
                }
            });
        });
    }).trigger('change');

    $('#address_muncity_code').change(function (e) {
        e.preventDefault();
        //Empty and Disable
        $('#address_brgy_text').empty();
        $("#address_brgy_text").append('<option value="" selected disabled>Choose...</option>');

        //Re-disable Select
        $('#address_muncity_code').prop('disabled', false);
        $('#address_brgy_text').prop('disabled', false);

        //Set Values for Hidden Box
        $('#address_muncity_text').val($('#address_muncity_code option:selected').text());

        $.getJSON("{{asset('json/refbrgy.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.brgyDesc > b.brgyDesc) {
                return 1;
                }
                if (a.brgyDesc < b.brgyDesc) {
                return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                if($('#address_muncity_code').val() == val.citymunCode) {
                    $('#address_brgy_text').append($('<option>', {
                        value: val.brgyDesc.toUpperCase(),
                        text: val.brgyDesc.toUpperCase(),
                        selected: (val.brgyDesc.toUpperCase() == bdefault) ? true : false,
                    }));
                }
            });
        });
    }).trigger('change');

    /*
    $('#address_region_text').val('REGION IV-A (CALABARZON)');
    $('#address_province_text').val('CAVITE');
    $('#address_muncity_text').val('GENERAL TRIAS');
    */

    $('#howmanydose').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 1) {
            $('#vaccine1').removeClass('d-none');
            $('#vaccine2').addClass('d-none');
            $('#vaccine3').addClass('d-none');
            $('#vaccine4').addClass('d-none');

            $('#dose2_date').prop('required', false);
            $('#dose2_manufacturer').prop('required', false);
            $('#dose3_date').prop('required', false);
            $('#dose3_manufacturer').prop('required', false);
            $('#dose4_date').prop('required', false);
            $('#dose4_manufacturer').prop('required', false);

            $('#dose1_bakuna_center_text').prop('required', true);
            $('#dose2_bakuna_center_text').prop('required', false);
            $('#dose3_bakuna_center_text').prop('required', false);
            $('#dose4_bakuna_center_text').prop('required', false);
        }
        else if($(this).val() == 2) {
            $('#vaccine1').removeClass('d-none');
            $('#vaccine2').removeClass('d-none');
            $('#vaccine3').addClass('d-none');
            $('#vaccine4').addClass('d-none');

            $('#dose2_date').prop('required', true);
            $('#dose2_manufacturer').prop('required', true);
            $('#dose3_date').prop('required', false);
            $('#dose3_manufacturer').prop('required', false);
            $('#dose4_date').prop('required', false);
            $('#dose4_manufacturer').prop('required', false);

            $('#dose1_bakuna_center_text').prop('required', true);
            $('#dose2_bakuna_center_text').prop('required', true);
            $('#dose3_bakuna_center_text').prop('required', false);
            $('#dose4_bakuna_center_text').prop('required', false);
        }
        else if($(this).val() == 3) {
            $('#vaccine1').removeClass('d-none');
            $('#vaccine2').removeClass('d-none');
            $('#vaccine3').removeClass('d-none');
            $('#vaccine4').addClass('d-none');

            $('#dose2_date').prop('required', true);
            $('#dose2_manufacturer').prop('required', true);
            $('#dose3_date').prop('required', true);
            $('#dose3_manufacturer').prop('required', true);
            $('#dose4_date').prop('required', false);
            $('#dose4_manufacturer').prop('required', false);

            $('#dose1_bakuna_center_text').prop('required', true);
            $('#dose2_bakuna_center_text').prop('required', true);
            $('#dose3_bakuna_center_text').prop('required', true);
            $('#dose4_bakuna_center_text').prop('required', false);
        }
        else if($(this).val() == 4) {
            $('#vaccine1').removeClass('d-none');
            $('#vaccine2').removeClass('d-none');
            $('#vaccine3').removeClass('d-none');
            $('#vaccine4').removeClass('d-none');

            $('#dose2_date').prop('required', true);
            $('#dose2_manufacturer').prop('required', true);
            $('#dose3_date').prop('required', true);
            $('#dose3_manufacturer').prop('required', true);
            $('#dose4_date').prop('required', true);
            $('#dose4_manufacturer').prop('required', true);

            $('#dose1_bakuna_center_text').prop('required', true);
            $('#dose2_bakuna_center_text').prop('required', true);
            $('#dose3_bakuna_center_text').prop('required', true);
            $('#dose4_bakuna_center_text').prop('required', true);
        }

        if($(this).val() != null) {
            $('#uploaddiv').removeClass('d-none');
            $('#cfooter').removeClass('d-none');
        }
    }).trigger('change');
</script>
@endsection