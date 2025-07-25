@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-center">
            <img src="{{asset('assets/images/CHO_LETTERHEAD_WITH_CESU.png')}}" class="mb-3 img-fluid" style="width: 50rem;">
        </div>
        <form action="{{route('vaxcert_walkin_process')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-3">
                <div class="card-header"><b>Maligayang pagdating sa VaxCert Concern Ticketing System</b></div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <p>{{Str::plural('Error', $errors->count())}} detected in creating VaxCert Concern Ticket:</p>
                        <hr>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </div>
                    @endif
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="alert alert-info" role="alert">
                        <ul>
                            <h4 class="text-danger"><b>BASAHIN ANG MGA SUMUSUNOD BAGO MAGPATULOY:</b></h4>
                            <li>Lahat ng field na may asterisk (<span class="text-danger font-weight-bold">*</span>) ay kailangang sagutan. Ilagay ang kumpleto at totoong impormasyon na hinihingi. I-double check ang mga inilagay na detalye bago isumite.</li>
                            <li>Mag-antay ng dalawa (2) hanggang tatlong (3) araw upang maayos namin ang inyong concern.  Ilagay ang iyong aktibong mobile number at email address sa pag-fill out sa ibaba upang mabilis namin kayong mabalitaan tungkol sa inyong concern.</li>
                        </ul>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="concern_type"><span class="text-danger font-weight-bold">*</span>Concern Type</label>
                                <select class="form-control" name="concern_type" id="concern_type" required>
                                      <option disabled {{(is_null(old('concern_type'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="MISSING DOSE" {{(old('concern_type') == 'MISSING DOSE') ? 'selected' : ''}}>Missing Dose/May kulang na Dose</option>
                                      <option value="CORRECTION" {{(old('concern_type') == 'CORRECTION') ? 'selected' : ''}}>Correction/Itatama ang Detalye (Wrong Name/Birthdate/etc.)</option>
                                      <option value="NO RECORD" {{(old('concern_type') == 'NO RECORD') ? 'selected' : ''}}>No Record/Nawawala o hindi makita ang Record</option>
                                      <option value="OTHERS" {{(old('concern_type') == 'OTHERS') ? 'selected' : ''}}>Others/Iba pa</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="use_type"><span class="text-danger font-weight-bold">*</span>Saan gagamitin ang VaxCert</label>
                                <select class="form-control" name="use_type" id="use_type" required>
                                      <option disabled {{(is_null(old('use_type'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="ABROAD" {{(old('use_type') == 'ABROAD') ? 'selected' : ''}}>Abroad/International</option>
                                      <option value="LOCAL" {{(old('use_type') == 'LOCAL') ? 'selected' : ''}}>Local Travel</option>
                                </select>
                            </div>
                            <div class="form-group d-none" id="ifabroad">
                                <label for="passport_no"><span class="text-danger font-weight-bold">*</span>Passport No.</label>
                                <input type="text" class="form-control" name="passport_no" id="passport_no" placeholder="ex. P12345" value="{{old('passport_no')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category"><span class="text-danger font-weight-bold">*</span>Category</label>
                                <select class="form-control" name="category" id="category" required>
                                      <option disabled {{(is_null(old('category'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="A1" {{(old('category') == 'A1') ? 'selected' : ''}}>A1 (Workers in Frontline Health Services)</option>
                                      <option value="A1.8" {{(old('category') == 'A1.8') ? 'selected' : ''}}>A1.8 (OFW living within 4 months)</option>
                                      <option value="A1.9" {{(old('category') == 'A1') ? 'selected' : ''}}>A1.9 (Family members of A1.1 to A1.3, newly hired priority A1)</option>
                                      <option value="ADDITIONAL A1" {{(old('category') == 'ADDITIONAL A1') ? 'selected' : ''}}>Additional A1 (All adult population eligible to be categorized as Priority Group A1)</option>
                                      <option value="A2" {{(old('category') == 'A2') ? 'selected' : ''}}>A2 (All Senior Citizens)</option>
                                      <option value="A3 - IMMUNOCOMPETENT" {{(old('category') == 'A3 - IMMUNOCOMPETENT') ? 'selected' : ''}}>A3 - Immunocompetent (HIV; 3rd Dose to be considered fully vaccinated)</option>
                                      <option value="A3 - IMMUNOCOMPROMISED" {{(old('category') == 'A3 - IMMUNOCOMPROMISED') ? 'selected' : ''}}>A3 - Immunocompromised (Asthma; if approved, will be considered a booster)</option>
                                      <option value="EXPANDED A3" {{(old('category') == 'EXPANDED A3') ? 'selected' : ''}}>Expanded A3 (Pregnant with Comorbidity)</option>
                                      <option value="A4" {{(old('category') == 'A4') ? 'selected' : ''}}>A4 (Frontline personnel in essential sectors, including uniformed personnel)</option>
                                      <option value="A5" {{(old('category') == 'A5') ? 'selected' : ''}}>A5 (Indigent Population)</option>
                                      <option value="PEDRIATRIC A3 (12-17 YEARS OLD)" {{(old('category') == 'PEDRIATRIC A3 (12-17 YEARS OLD)') ? 'selected' : ''}}>Pediatric A3 (12-17 years old with comorbidity)</option>
                                      <option value="PEDRIATRIC A3 (5-11 YEARS OLD)" {{(old('category') == 'PEDRIATRIC A3 (5-11 YEARS OLD)') ? 'selected' : ''}}>Pediatric A3 (5-11 years old with comorbidity)</option>
                                      <option value="ROAP" {{(old('category') == 'ROAP') ? 'selected' : ''}}>ROAP - Rest of Adult Population</option>
                                      <option value="ROPP (12-17 YEARS OLD)" {{(old('category') == 'ROPP (12-17 YEARS OLD)') ? 'selected' : ''}}>ROPP - Rest of Pediatric Population (12-17 years old)</option>
                                      <option value="ROPP (5-11 YEARS OLD)" {{(old('category') == 'ROPP (5-11 YEARS OLD)') ? 'selected' : ''}}>ROPP - Rest of Pediatric Population (5-11 years old)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 d-none">
                            <div class="form-group">
                              <label for="vaxcert_refno">VaxCertPH Ticket Reference No. <i>(Optional)</i></label>
                              <input type="text" name="vaxcert_refno" id="vaxcert_refno" class="form-control" pattern="[0-9]" value="{{old('vaxcert_refno')}}">
                              <small class="text-muted">Paalala: Mas madali po namin kayong matutulungan kung meron na po kayo nito. Binibigay ito ng VaxCertPH Website kapag nag-submit kayo ng "Update Record" Ticket sa kanila.</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="vaxcard_uniqueid">Vaccination Card ID No./Unique Person ID <i>(Leave blank if empty)</i></label>
                              <input type="text" class="form-control" name="vaxcard_uniqueid" id="vaxcard_uniqueid" placeholder="ex: CC1234, RP1234, VM1234" value="{{old('vaxcard_uniqueid')}}">
                              <small class="text-muted">Nakikita ito sa kanang ibabaw na bahagi ng iyong Vaccination Card.</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="comorbidity">Comorbidity <i>(Leave blank if empty)</i></label>
                                <input type="text" class="form-control" name="comorbidity" id="comorbidity" placeholder="ex: Diabetes, Hypertension, Cancer" value="{{old('comorbidity')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pwd_yn"><span class="text-danger font-weight-bold">*</span>Person with Disability (PWD)</label>
                                <select class="form-control" name="pwd_yn" id="pwd_yn" required>
                                  <option value="N" {{(old('pwd_yn') == 'N') ? 'selected' : ''}}>No</option>
                                  <option value="Y" {{(old('pwd_yn') == 'Y') ? 'selected' : ''}}>Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="concern_msg"><span class="text-danger font-weight-bold">*</span>Specific Concern Message/Isulat dito ang problema na nais ipaayos sa inyong VaxCert</label>
                      <textarea class="form-control" name="concern_msg" id="concern_msg" rows="3" placeholder="Ipaliwanag dito ang isyu na nais ipa-resolba saamin. (Halimbawa: Hindi nalabas ang aking First Dose, Mali ang spelling ng pangalan ko, Mali ang Birthday ko, atbp.)" required>{{old('concern_msg')}}</textarea>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="first_name"><span class="text-danger font-weight-bold">*</span>First Name/Unang Pangalan</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{old('first_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="JUAN" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="middle_name">Middle Name/Gitnang Pangalan (Iwanang blanko kung wala)</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{old('middle_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="SANCHEZ">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="last_name"><span class="text-danger font-weight-bold">*</span>Surname/Apelyido</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{old('last_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="DELA CRUZ" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Suffix <small>(ex. JR, SR, II, III, etc.)</small></label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix')}}" minlength="2" maxlength="6" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                              <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex/Kasarian</label>
                              <select class="form-control" name="gender" id="gender" required>
                                <option disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="M" {{(old('gender') == 'M') ? 'selected' : ''}}>Male/Lalaki</option>
                                <option value="F" {{(old('gender') == 'F') ? 'selected' : ''}}>Female/Babae</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact_number"><span class="text-danger font-weight-bold">*</span>Mobile Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('mobile', '09')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email"><span class="text-danger font-weight-bold">*</span>Email Address</label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}" required>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Ilagay ang aktibong Mobile Number at Email Address upang mabilis namin kayong makausap tungkol sa update.</small>
                        </div>
                    </div>
                    <div id="ifguardian" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="glast_name"><span class="text-danger font-weight-bold">*</span>Guardian's Surname/Apelyido ng Magulang o Bantay</label>
                                    <input type="text" class="form-control" id="glast_name" name="glast_name" value="{{old('glast_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gfirst_name"><span class="text-danger font-weight-bold">*</span>Guardian's First Name/Unang Pangalan ng Magulang o Bantay</label>
                                    <input type="text" class="form-control" id="gfirst_name" name="gfirst_name" value="{{old('gfirst_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div id="address_text" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="address_region_text" name="address_region_text" value="{{old('address_region_text')}}" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="address_province_text" name="address_province_text" value="{{old('address_province_text')}}" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="address_muncity_text" name="address_muncity_text" value="{{old('address_muncity_text')}}" readonly>
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
                            <option disabled {{(is_null(old('howmanydose'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="1" {{(old('howmanydose') == 1) ? 'selected' : ''}}>1st Dose Only</option>
                            <option value="2" {{(old('howmanydose') == 2) ? 'selected' : ''}}>1st and 2nd Dose</option>
                            <option value="3" {{(old('howmanydose') == 3) ? 'selected' : ''}}>1st, 2nd, and 3rd Dose (1st Booster)</option>
                            <option value="4" {{(old('howmanydose') == 4) ? 'selected' : ''}}>1st, 2nd, 3rd (1st Booster), and 4th Dose (2nd Booster)</option>
                        </select>
                    </div>
                    <div id="vaccine1" class="d-none">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dose1_date"><span class="text-danger font-weight-bold">*</span><b>1ST Dose Date</b></label>
                                    <input type="date" class="form-control" name="dose1_date" id="dose1_date" value="{{old('dose1_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="dose1_manufacturer"><span class="text-danger font-weight-bold">*</span>1ST Dose Manufacturer</label>
                                <select class="form-control" name="dose1_manufacturer" id="dose1_manufacturer" required>
                                    <option disabled {{(is_null(old('dose1_manufacturer'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach(App\Models\VaxcertConcern::getVaccineBrandsList() as $vl)
                                    <option value="{{$vl['code']}}" {{(old('dose1_manufacturer') == $vl['code']) ? 'selected' : ''}}>{{$vl['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dose1_inmainlgu_yn"><span class="text-danger font-weight-bold">*</span>1ST Dose Vaccinated HERE in GenTri?</label>
                                    <select class="form-control" name="dose1_inmainlgu_yn" id="dose1_inmainlgu_yn" required>
                                      <option disabled {{(is_null(old('dose1_inmainlgu_yn'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="Y" {{(old('dose1_inmainlgu_yn') == 'Y') ? 'selected' : ''}}>Oo/Yes</option>
                                      <option value="N" {{(old('dose1_inmainlgu_yn') == 'N') ? 'selected' : ''}}>Hindi/No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="dose1_bakuna_center_text"><span class="text-danger font-weight-bold">*</span>Vaccination Site/Lugar kung saan binakunahan</label>
                                  <input type="text" class="form-control" name="dose1_bakuna_center_text" id="dose1_bakuna_center_text" style="text-transform: uppercase;">
                                </div>
                                <small class="text-muted">Note: Hindi po parte ng katawan ang isusulat dito.</small>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose1_batchno">Batch/Lot No.</label>
                                    <input type="text" class="form-control" name="dose1_batchno" id="dose1_batchno" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose1_vaccinator_last_name">Vaccinator Surname</label>
                                    <input type="text" class="form-control" name="dose1_vaccinator_last_name" id="dose1_vaccinator_last_name" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose1_vaccinator_first_name">Vaccinator First Name</label>
                                    <input type="text" class="form-control" name="dose1_vaccinator_first_name" id="dose1_vaccinator_first_name" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="vaccine2" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dose2_date"><span class="text-danger font-weight-bold">*</span><b>2ND Dose Date</b></label>
                                    <input type="date" class="form-control" name="dose2_date" id="dose2_date" value="{{old('dose2_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="dose2_manufacturer"><span class="text-danger font-weight-bold">*</span>2ND Dose Manufacturer</label>
                                <select class="form-control" name="dose2_manufacturer" id="dose2_manufacturer">
                                    <option disabled {{(is_null(old('dose2_manufacturer'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach(App\Models\VaxcertConcern::getVaccineBrandsList() as $vl)
                                    <option value="{{$vl['code']}}" {{(old('dose2_manufacturer') == $vl['code']) ? 'selected' : ''}}>{{$vl['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dose2_inmainlgu_yn"><span class="text-danger font-weight-bold">*</span>2ND Dose Vaccinated HERE in GenTri?</label>
                                    <select class="form-control" name="dose2_inmainlgu_yn" id="dose2_inmainlgu_yn" required>
                                      <option disabled {{(is_null(old('dose2_inmainlgu_yn'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="Y" {{(old('dose2_inmainlgu_yn') == 'Y') ? 'selected' : ''}}>Oo/Yes</option>
                                      <option value="N" {{(old('dose2_inmainlgu_yn') == 'N') ? 'selected' : ''}}>Hindi/No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="dose2_bakuna_center_text"><span class="text-danger font-weight-bold">*</span>Vaccination Site/Lugar kung saan binakunahan</label>
                                  <input type="text" class="form-control" name="dose2_bakuna_center_text" id="dose2_bakuna_center_text" style="text-transform: uppercase;">
                                </div>
                                <small class="text-muted">Note: Hindi po parte ng katawan ang isusulat dito.</small>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose2_batchno">Batch/Lot No.</label>
                                    <input type="text" class="form-control" name="dose2_batchno" id="dose2_batchno" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose2_vaccinator_last_name">Vaccinator Surname</label>
                                    <input type="text" class="form-control" name="dose2_vaccinator_last_name" id="dose2_vaccinator_last_name" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose2_vaccinator_first_name">Vaccinator First Name</label>
                                    <input type="text" class="form-control" name="dose2_vaccinator_first_name" id="dose2_vaccinator_first_name" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="vaccine3" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dose3_date"><span class="text-danger font-weight-bold">*</span><b>3RD Dose (Booster 1) Date</b></label>
                                    <input type="date" class="form-control" name="dose3_date" id="dose3_date" value="{{old('dose3_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="dose3_manufacturer"><span class="text-danger font-weight-bold">*</span>3RD Dose (Booster 1) Manufacturer</label>
                                <select class="form-control" name="dose3_manufacturer" id="dose3_manufacturer">
                                    <option disabled {{(is_null(old('dose3_manufacturer'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach(App\Models\VaxcertConcern::getVaccineBrandsList() as $vl)
                                    <option value="{{$vl['code']}}" {{(old('dose3_manufacturer') == $vl['code']) ? 'selected' : ''}}>{{$vl['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dose3_inmainlgu_yn"><span class="text-danger font-weight-bold">*</span>3RD Dose Vaccinated HERE in GenTri?</label>
                                    <select class="form-control" name="dose3_inmainlgu_yn" id="dose3_inmainlgu_yn" required>
                                      <option disabled {{(is_null(old('dose3_inmainlgu_yn'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="Y" {{(old('dose3_inmainlgu_yn') == 'Y') ? 'selected' : ''}}>Oo/Yes</option>
                                      <option value="N" {{(old('dose3_inmainlgu_yn') == 'N') ? 'selected' : ''}}>Hindi/No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="dose3_bakuna_center_text"><span class="text-danger font-weight-bold">*</span>Vaccination Site/Lugar kung saan binakunahan</label>
                                  <input type="text" class="form-control" name="dose3_bakuna_center_text" id="dose3_bakuna_center_text" style="text-transform: uppercase;">
                                </div>
                                <small class="text-muted">Note: Hindi po parte ng katawan ang isusulat dito.</small>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose3_batchno">Batch/Lot No.</label>
                                    <input type="text" class="form-control" name="dose3_batchno" id="dose3_batchno" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose3_vaccinator_last_name">Vaccinator Surname</label>
                                    <input type="text" class="form-control" name="dose3_vaccinator_last_name" id="dose3_vaccinator_last_name" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose3_vaccinator_first_name">Vaccinator First Name</label>
                                    <input type="text" class="form-control" name="dose3_vaccinator_first_name" id="dose3_vaccinator_first_name" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="vaccine4" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dose4_date"><span class="text-danger font-weight-bold">*</span><b>4TH Dose (Booster 2) Date</b></label>
                                    <input type="date" class="form-control" name="dose4_date" id="dose4_date" value="{{old('dose4_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="dose4_manufacturer"><span class="text-danger font-weight-bold">*</span>4TH Dose (Booster 2) Manufacturer</label>
                                <select class="form-control" name="dose4_manufacturer" id="dose4_manufacturer">
                                    <option disabled {{(is_null(old('dose4_manufacturer'))) ? 'selected' : ''}}>Choose...</option>
                                    @foreach(App\Models\VaxcertConcern::getVaccineBrandsList() as $vl)
                                    <option value="{{$vl['code']}}" {{(old('dose4_manufacturer') == $vl['code']) ? 'selected' : ''}}>{{$vl['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dose4_inmainlgu_yn"><span class="text-danger font-weight-bold">*</span>4TH Dose Vaccinated HERE in GenTri?</label>
                                    <select class="form-control" name="dose4_inmainlgu_yn" id="dose4_inmainlgu_yn" required>
                                      <option disabled {{(is_null(old('dose4_inmainlgu_yn'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="Y" {{(old('dose4_inmainlgu_yn') == 'Y') ? 'selected' : ''}}>Oo/Yes</option>
                                      <option value="N" {{(old('dose4_inmainlgu_yn') == 'N') ? 'selected' : ''}}>Hindi/No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for="dose4_bakuna_center_text"><span class="text-danger font-weight-bold">*</span>Vaccination Site/Lugar kung saan binakunahan</label>
                                  <input type="text" class="form-control" name="dose4_bakuna_center_text" id="dose4_bakuna_center_text" style="text-transform: uppercase;">
                                </div>
                                <small class="text-muted">Note: Hindi po parte ng katawan ang isusulat dito.</small>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose4_batchno">Batch/Lot No.</label>
                                    <input type="text" class="form-control" name="dose4_batchno" id="dose4_batchno" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose4_vaccinator_last_name">Vaccinator Surname</label>
                                    <input type="text" class="form-control" name="dose4_vaccinator_last_name" id="dose4_vaccinator_last_name" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dose4_vaccinator_first_name">Vaccinator First Name</label>
                                    <input type="text" class="form-control" name="dose4_vaccinator_first_name" id="dose4_vaccinator_first_name" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="uploaddiv" class="d-none">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="id_file"><span class="text-danger font-weight-bold">*</span>Upload Valid ID/Birth Certificate/Affidavit Picture here</label>
                                <input type="file" class="form-control-file" name="id_file" id="id_file" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vaxcard_file"><span class="text-danger font-weight-bold">*</span>Upload Vaccination Card/s Picture here (<b class="text-danger">Note:</b> Isama sa picture lahat ng dose na mayroon ka)</label>
                                    <input type="file" class="form-control-file" name="vaxcard_file" id="vaxcard_file" required>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-center text-muted">Tanging mga file na JPG, PNG, at PDF lamang ang maaaring i-upload na may maximum size na 10MB.</h6>
                        <hr>
                        <ul>
                            <b>Data Privacy Statement</b>
                            <li>Ang inyong personal na impormasyon ay kinakailangan upang maabot ang layunin ng tanggapan, kasama na ang pagbibigay ng inyong vaccination certificate.</li>
                            <li>Sa pagsumite ng inyong data, sumasang-ayon kayong maipatupad ang mga nabanggit na polisiya at mekanismo na nakabatay sa Data Privacy Act ng 2012 (R.A. No. 10173) at iba pang kaugnay na batas.</li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer text-center d-none" id="cfooter">
                    <h6><i>Paki double check ang mga nilagay na detalye bago mag-sumite.</i></h6>
                    <hr>
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
        </form>
        <p class="text-center">For inquiries: 0919 066 43 24/25/27 | (046) 509 - 5289 | <a>cesugentrias.vaxcert@gmail.com</a> | <a href="https://www.facebook.com/cesugentrias">Facebook Page</a></p>
        <hr>
        <h6 class="text-center"><b>Office Hours:</b> Monday - Friday <i>(except Holidays)</i>, 8AM - 5PM</h6>
        <hr>
        <p class="text-center">GenTrias LGU VaxCert Concern Ticketing System - Developed and Maintained by <u>CJH</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
    </div>

    <div class="modal fade" id="selectchoice" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5>Welcome to VaxCert Concern Ticketing System <b>(General Trias Cavite Vaccination Sites Concerns ONLY)</b></h5>
                </div>
                <div class="modal-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div id="choice1">
                        <div class="alert alert-info" role="alert">
                            <h4 class="text-danger text-center"><b>BASAHIN MUNA ANG MGA SUMUSUNOD BAGO MAGPATULOY:</b></h4>
                            <hr>
                            <!--
                            <h6>Kung ikaw ay nais lamang kumuha ng VaxCert at wala namang problema sa record mo, hindi mo na kailangan magpatuloy dito at dumiretso na lang sa mismong VaxCert website upang makapag-generate.</h6>
                            <h6>Pindutin lang ang link na ito ☛ <b><a href="https://vaxcert.doh.gov.ph/#/">https://vaxcert.doh.gov.ph</a></b></h6>
                            <hr>
                            -->
                            <h6 class="text-center">Sa ngayon, ang ika-limang dose o ang ikatlong booster (na Bivalent) ay hindi pa talaga nalabas sa VaxCertPH.</h6>
                            <hr>
                            <h6 class="text-center">Hindi po talaga nalabas ang Middle Name/Gitnang Pangalan kapag gagamitin sa Abroad ang VaxCert.</h6>
                            <hr>
                            Ihanda ang picture ng iyong:
                            <ul>
                                <li><b>Vaccination Card</b> (<i><b class="text-danger">Note:</b> Pakisigurado na kasama sa picture lahat ng bakunang natanggap mo</i>)</li>
                                <li><b>Valid IDs</b> katulad ng:
                                    <ul>
                                        <li>Passport, National ID, Postal ID, Philhealth ID, SSS/UMID, Voters ID, etc. <i>(As long as may nakasulat na Birthdate)</i></li>
                                        <li>Kung wala sa mga nabanggit, maaaring mag-provide ng <b>Birth Certificate</b></li>
                                        <li>Para sa magpapapalit ng Apelyido/Surname dahil sa Kasal o Annulment, mag-provide ng picture ng iyong Affidavit.</li>
                                    </ul>
                                </li>
                            </ul>
                            <div class="text-center">
                                <hr>
                                <b>LIBRE PO</b> ang magpaayos ng VaxCert Record.
                            </div>
                        </div>
                        <button type="button" class="btn btn-success btn-block" data-dismiss="modal">Magpatuloy</button>
                        @if(date('w') == 6 || date('w') == 0)
                        <h6>Paalala: Dahil ngayon ay {{(date('w') == 6) ? 'Sabado' : 'Linggo'}}, ang iyong concern ay maaasikaso pa sa darating na Lunes.</h6>
                        @endif
                        <hr>
                        <button type="button" class="btn btn-primary btn-block mt-3" id="clicktrack">Nag-submit na ako dito dati pa at gusto kong mag Follow-up ng aking Ticket</button>
                    </div>
                    <div id="choice2" class="d-none text-center">
                        <form action="{{route('vaxcert_followup')}}" method="POST" autocomplete="off">
                            @csrf
                            <div class="form-group text-left">
                              <label for="inputTicketNumber"><b class="text-danger">*</b>Pakilagay ang iyong Ticket Number</label>
                              <input type="text" class="form-control" name="inputTicketNumber" id="inputTicketNumber" aria-describedby="helpId" placeholder="example: AB12CD" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Verify</button>
                        </form>
                        <!--
                        <p>Kung makalipas ng 2-3 araw at wala kayong na-receive na update mula saamin, maaari niyo kami ma-contact sa pamamagitan ng:</p>
                        <h6><b>Email:</b> cesugentri.vaxcert@gmail.com</h6>
                        <h6><b>Text/Call/Viber:</b> +63919 066 4327</h6>
                        <h6 class="mt-5"><b>Office Hours:</b> Monday - Friday <i>(except Holidays)</i>, 8AM - 5PM</h6>
                        -->
                        <hr>
                        <button type="button" id="goback" class="btn btn-secondary btn-block">Go Back</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#selectchoice').modal({backdrop: 'static', keyboard: false});
        $('#selectchoice').modal('show');

        $('#clicktrack').click(function (e) { 
            e.preventDefault();
            $('#choice1').addClass('d-none');
            $('#choice2').removeClass('d-none');
        });

        $('#goback').click(function (e) { 
            e.preventDefault();
            $('#choice1').removeClass('d-none');
            $('#choice2').addClass('d-none');
        });
        
        //Select2 Init for Address Bar
        $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
            theme: 'bootstrap',
        });

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
                    selected: (val.regCode == '04') ? true : false, //default is Region IV-A
                }));
            });
        }).fail(function(jqxhr, textStatus, error) {
            // Error callback
            var err = textStatus + ", " + error;
            console.log("Failed to load Region JSON: " + err);
            window.location.reload(); // Reload the page
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
                            selected: (val.provCode == '0421') ? true : false, //default for Cavite
                        }));
                    }
                });
            }).fail(function(jqxhr, textStatus, error) {
                // Error callback
                var err = textStatus + ", " + error;
                console.log("Failed to load Region JSON: " + err);
                window.location.reload(); // Reload the page
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
                            selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
                        })); 
                    }
                });
            }).fail(function(jqxhr, textStatus, error) {
                // Error callback
                var err = textStatus + ", " + error;
                console.log("Failed to load CityMun JSON: " + err);
                window.location.reload(); // Reload the page
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
                        }));
                    }
                });
            }).fail(function(jqxhr, textStatus, error) {
                // Error callback
                var err = textStatus + ", " + error;
                console.log("Failed to load Province BRGY: " + err);
                window.location.reload(); // Reload the page
            });
        }).trigger('change');

        $('#address_region_text').val('REGION IV-A (CALABARZON)');
        $('#address_province_text').val('CAVITE');
        $('#address_muncity_text').val('GENERAL TRIAS');

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

        $('#dose1_manufacturer').change(function (e) { 
            e.preventDefault();
            var selectedManufacturer = $(this).val();
            if ($('#howmanydose').val() == 2 || $('#howmanydose').val() == 3 || $('#howmanydose').val() == 4) {
                $('#dose2_manufacturer').val(selectedManufacturer);
            }
        });

        $('#use_type').change(function (e) { 
            e.preventDefault();
            if($(this).val() == null || $(this).val() == 'LOCAL') {
                $('#ifabroad').addClass('d-none');
                $('#passport_no').prop('required', false);
            }
            else {
                $('#ifabroad').removeClass('d-none');
                $('#passport_no').prop('required', true);
            }
        }).trigger('change');

        $(document).ready(function(){
            $('#bdate').change(function(){
                var dob = new Date($(this).val());
                var today = new Date();
                var age = today.getFullYear() - dob.getFullYear();
                var m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }

                if (age < 18) {
                    $('#ifguardian').removeClass('d-none');
                    $('#gfirst_name').prop('required', true);
                    $('#glast_name').prop('required', true);
                }
                else {
                    $('#ifguardian').addClass('d-none');
                    $('#gfirst_name').prop('required', false);
                    $('#glast_name').prop('required', false);
                }
            });
        });

        $('#dose1_manufacturer').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'J&J') {
                $('#vaccine2').addClass('d-none');
                $('#dose2_date').prop('required', false);
                $('#dose2_manufacturer').prop('required', false);
                $('#dose2_inmainlgu_yn').prop('required', false);
                $('#dose2_bakuna_center_text').prop('required', false);
                $('#dose2_batchno').prop('required', false);
                $('#dose2_vaccinator_last_name').prop('required', false);
                $('#dose2_vaccinator_first_name').prop('required', false);
            }
            else {
                $('#howmanydose').change();
            }
        });
    </script>
@endsection