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
                            <li>Tungkol sa "Nawawalang Bakuna/Missing Dose", tanging mga bakunahan lamang sa General Trias ang aming mar-resolba. Kung ang bakunang nawawala ay hindi dito sa Gentri binakunahan ay makipag-ugnayan sa bayan kung saan ka binakunahan upang maayos nila ito.</li>
                            <li>Inirerekumenda naming mag-submit muna kayo ng "Update Record" ticket ng inyong isyu sa <a href="https://vaxcert.doh.gov.ph/#/">VaxCertPH Website</a> upang mas mabilis namin kayong matulungan. Ilagay ito sa <i>"VaxCertPH Ticket Reference No."</i> na makikita sa ibaba.</li>
                            <li>Mag-antay ng dalawa (2) hanggang tatlong (3) araw upang maayos namin ang inyong concern.  Maglagay ng aktibong mobile number at email address upang mabilis namin kayong makontak tungkol sa inyong concern.</li>
                        </ul>
                        <hr>
                        <ul>
                            <li>Ihanda ang litrato ng iyong <b>1.</b> Vaccination Card at <b>2.</b> Valid ID <i>(Passport, National ID, Postal ID, Philhealth ID, UMID, etc.)</i> at Affidavit <i>(Kung papalitan ang apelyido dahil kinasal)</i> na kailangan upang mai-sumite ang iyong form.</li>
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
                                      <option value="ABROAD" {{(old('use_type') == 'ABROAD') ? 'selected' : ''}}>Abroad</option>
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
                              <label for="vaxcert_refno">VaxCertPH Ticket Reference No.</label>
                              <input type="text" name="vaxcert_refno" id="vaxcert_refno" class="form-control" pattern="[0-9]" value="{{old('vaxcert_refno')}}">
                              <small class="text-muted">Paalala: Mas madali po namin kayong matutulungan kung meron na po kayo nito.</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="category"><span class="text-danger font-weight-bold">*</span>Category</label>
                                <select class="form-control" name="category" id="category" required>
                                      <option disabled {{(is_null(old('category'))) ? 'selected' : ''}}>Choose...</option>
                                      <option value="A1" {{(old('category') == 'A1') ? 'selected' : ''}}>A1</option>
                                      <option value="A1.8" {{(old('category') == 'A1.8') ? 'selected' : ''}}>A1.8</option>
                                      <option value="A1.9" {{(old('category') == 'A1') ? 'selected' : ''}}>A1.9</option>
                                      <option value="A2" {{(old('category') == 'A2') ? 'selected' : ''}}>A2</option>
                                      <option value="A3 - IMMUNOCOMPETENT" {{(old('category') == 'A3 - IMMUNOCOMPETENT') ? 'selected' : ''}}>A3 - Immunocompetent</option>
                                      <option value="A3 - IMMUNOCOMPROMISED" {{(old('category') == 'A3 - IMMUNOCOMPROMISED') ? 'selected' : ''}}>A3 - Immunocompromised</option>
                                      <option value="A4" {{(old('category') == 'A4') ? 'selected' : ''}}>A4</option>
                                      <option value="A5" {{(old('category') == 'A5') ? 'selected' : ''}}>A5</option>
                                      <option value="ADDITIONAL A1" {{(old('category') == 'ADDITIONAL A1') ? 'selected' : ''}}>Additional A1</option>
                                      <option value="EXPANDED A3" {{(old('category') == 'EXPANDED A3') ? 'selected' : ''}}>Expanded A3</option>
                                      <option value="PEDRIATRIC A3 (12-17 YEARS OLD)" {{(old('category') == 'PEDRIATRIC A3 (12-17 YEARS OLD)') ? 'selected' : ''}}>Pediatric A3 (12-17 years old)</option>
                                      <option value="PEDRIATRIC A3 (5-11 YEARS OLD)" {{(old('category') == 'PEDRIATRIC A3 (5-11 YEARS OLD)') ? 'selected' : ''}}>Pediatric A3 (5-11 years old)</option>
                                      <option value="ROAP" {{(old('category') == 'ROAP') ? 'selected' : ''}}>ROAP</option>
                                      <option value="ROPP (12-17 YEARS OLD)" {{(old('category') == 'ROPP (12-17 YEARS OLD)') ? 'selected' : ''}}>ROPP (12-17 years old)</option>
                                      <option value="ROPP (5-11 YEARS OLD)" {{(old('category') == 'ROPP (5-11 YEARS OLD)') ? 'selected' : ''}}>ROPP (5-11 years old)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                              <label for="vaxcard_uniqueid">Vaccination Card ID No./Unique Person ID <i>(Leave blank if empty)</i></label>
                              <input type="text" class="form-control" name="vaxcard_uniqueid" id="vaxcard_uniqueid" placeholder="ex: CC1234, RP1234, VM1234" value="{{old('vaxcard_uniqueid')}}">
                              <small class="text-muted">Nakikita ito sa kanang ibabaw na bahagi ng iyong Vaccination Card.</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="comorbidity">Comorbidity <i>(Leave blank if empty)</i></label>
                                <input type="text" class="form-control" name="comorbidity" id="comorbidity" placeholder="ex: Diabetes, Hypertension, Cancer" value="{{old('comorbidity')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
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
                                <label for="first_name"><span class="text-danger font-weight-bold">*</span>First Name (Unang Pangalan)</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{old('first_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="JUAN" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="middle_name">Middle Name (Gitnang Pangalan)</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{old('middle_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" placeholder="SANCHEZ">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="last_name"><span class="text-danger font-weight-bold">*</span>Last Name (Apelyido)</label>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contact_number"><span class="text-danger font-weight-bold">*</span>Mobile Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('mobile', '09')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}" required>
                            </div>
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
                                    <option value="AZ" {{(old('dose1_manufacturer') == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                    <option value="J&J" {{(old('dose1_manufacturer') == 'J&J') ? 'selected' : ''}}>Johnson and Johnson (J&J)/Janssen</option>
                                    <option value="Moderna" {{(old('dose1_manufacturer') == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                    <option value="Novavax" {{(old('dose1_manufacturer') == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                    <option value="Pfizer" {{(old('dose1_manufacturer') == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                    <option value="Sinohpharm" {{(old('dose1_manufacturer') == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                    <option value="Sinovac" {{(old('dose1_manufacturer') == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                    <option value="SputnikLight" {{(old('dose1_manufacturer') == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                                    <option value="Gamaleya" {{(old('dose1_manufacturer') == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
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
                                    <option value="AZ" {{(old('dose2_manufacturer') == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                    <option value="J&J" {{(old('dose2_manufacturer') == 'J&J') ? 'selected' : ''}}>Johnson and Johnson (J&J)/Janssen</option>
                                    <option value="Moderna" {{(old('dose2_manufacturer') == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                    <option value="Novavax" {{(old('dose2_manufacturer') == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                    <option value="Pfizer" {{(old('dose2_manufacturer') == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                    <option value="Sinohpharm" {{(old('dose2_manufacturer') == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                    <option value="Sinovac" {{(old('dose2_manufacturer') == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                    <option value="SputnikLight" {{(old('dose2_manufacturer') == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                                    <option value="Gamaleya" {{(old('dose2_manufacturer') == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
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
                                    <option value="AZ" {{(old('dose3_manufacturer') == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                    <option value="J&J" {{(old('dose3_manufacturer') == 'J&J') ? 'selected' : ''}}>Johnson and Johnson (J&J)/Janssen</option>
                                    <option value="Moderna" {{(old('dose3_manufacturer') == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                    <option value="Novavax" {{(old('dose3_manufacturer') == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                    <option value="Pfizer" {{(old('dose3_manufacturer') == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                    <option value="Sinohpharm" {{(old('dose3_manufacturer') == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                    <option value="Sinovac" {{(old('dose3_manufacturer') == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                    <option value="SputnikLight" {{(old('dose3_manufacturer') == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                                    <option value="Gamaleya" {{(old('dose3_manufacturer') == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
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
                                    <option value="AZ" {{(old('dose4_manufacturer') == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                    <option value="J&J" {{(old('dose4_manufacturer') == 'J&J') ? 'selected' : ''}}>Johnson and Johnson (J&J)/Janssen</option>
                                    <option value="Moderna" {{(old('dose4_manufacturer') == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                    <option value="Novavax" {{(old('dose4_manufacturer') == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                    <option value="Pfizer" {{(old('dose4_manufacturer') == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                    <option value="Sinohpharm" {{(old('dose4_manufacturer') == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                    <option value="Sinovac" {{(old('dose4_manufacturer') == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                    <option value="SputnikLight" {{(old('dose4_manufacturer') == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                                    <option value="Gamaleya" {{(old('dose4_manufacturer') == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
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
                                <label for="id_file"><span class="text-danger font-weight-bold">*</span>Upload Valid ID/Birth Certificate/Affidavit Picture</label>
                                <input type="file" class="form-control-file" name="id_file" id="id_file" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vaxcard_file"><span class="text-danger font-weight-bold">*</span>Upload Vaccination Card Picture</label>
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
        <p class="text-center">VaxCert Concern Ticketing System - Developed and Maintained by <u>Christian James Historillo</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
    </div>

    <div class="modal fade" id="selectchoice" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5>Welcome to VaxCert Concern Ticketing System <b>(General Trias Cavite Vaccination Sites Concerns ONLY)</b></h5>
                </div>
                <div class="modal-body">
                    <div id="choice1">
                        <div class="alert alert-info text-center" role="alert">
                            <h4 class="text-danger"><b>BASAHIN MUNA ANG MGA SUMUSUNOD BAGO MAGPATULOY:</b></h4>
                            Kung ikaw ay nais lamang kumuha ng VaxCert at wala namang problema sa record mo, hindi mo na kailangan magpatuloy dito at dumiretso na lang sa mismong VaxCert website upang makapag-generate: <b><a href="https://vaxcert.doh.gov.ph/#/">https://vaxcert.doh.gov.ph</a></b>
                            <hr>
                            Hindi po talaga nalabas ang Middle Name/Gitnang Pangalan kapag gagamitin sa Abroad ang VaxCert.
                            <hr>
                            Kung ang dose na may problema ay hindi po dito sa General Trias binakunahan, doon ka makikipag-coordinate <i>(via text/call or pumunta sa mismong opisina nila)</i> kung saan ka binakunahan dahil sila ang may permission sa system upang maayos ang record mo.
                            <hr>
                            Kung nawawala ang Vaccination Card, maaaring mag-request sa pinakamalapit na Vaccination Sites sa General Trias <i>(Ang schedule ng bakunahan ay naka-post sa <a href="https://www.facebook.com/GenTriOfficial/">General Trias City Facebook Page</a>)</i>
                        </div>
                        <button type="button" class="btn btn-success btn-block" data-dismiss="modal">New Concern/Mag-file ng Bagong Concern <i>(Hindi makita ng VaxCertPH ang Record/Nawawalang Dose/Maling Spelling ng Pangalan, Birthdate, etc.)</i></button>
                        @if(date('w') == 6 || date('w') == 0)
                        <h6>Paalala: Dahil ngayon ay {{(date('w') == 6) ? 'Sabado' : 'Linggo'}}, ang iyong concern ay maaasikaso pa sa darating na Lunes ng umaga.</h6>
                        @endif
                        <hr>
                        <button type="button" class="btn btn-secondary btn-block" id="clicktrack">Track Concern Status/Follow-up <i>(Currently on Maintenance)</i></button>
                    </div>
                    <form action="{{route('vaxcert_track')}}" method="GET" id="choice2" class="d-none">
                        <div class="form-group">
                            <label for="ref_code">Input reference code</label>
                            <input type="text" class="form-control" name="ref_code" id="ref_code" required>
                        </div>
                        <button type="button" id="goback" class="btn btn-primary btn-block">Go Back</button>
                    </form>
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