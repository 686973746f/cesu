@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">VaxCert Update Record Form</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="concern_type">Concern Type</label>
                            <select class="form-control" name="concern_type" id="concern_type" required>
                                  <option disabled {{(is_null(old('concern_type'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="MISSING DOSE" {{(old('concern_type') == 'MISSING DOSE') ? 'selected' : ''}}>Missing Dose</option>
                                  <option value="CORRECTION" {{(old('concern_type') == 'CORRECTION') ? 'selected' : ''}}>Correction (Wrong Name/Birthdate/etc.)</option>
                                  <option value="OTHERS" {{(old('concern_type') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="category">Category</label>
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
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="vaxcert_refno">VaxCertPH Ticket Reference No.</label>
                          <input type="text" name="vaxcert_refno" id="vaxcert_refno" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="concern_msg">Specific Concern Message</label>
                  <textarea class="form-control" name="concern_msg" id="concern_msg" rows="3"></textarea>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
							<label for="first_name">First Name (Unang Pangalan)</label>
							<input type="text" class="form-control" id="first_name" name="first_name" value="{{old('first_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
						</div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
							<label for="middle_name">Middle Name (Gitnang Pangalan)</label>
							<input type="text" class="form-control" id="middle_name" name="middle_name" value="{{old('middle_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;">
						</div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
							<label for="last_name"><span class="text-danger font-weight-bold">*</span>Last Name (Apelyido)</label>
							<input type="text" class="form-control" id="last_name" name="last_name" value="{{old('last_name')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" required>
						</div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
							<label for="suffix">Suffix <small>(ex. JR, II, III, etc.)</small></label>
							<input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix')}}" minlength="2" maxlength="6" style="text-transform: uppercase;">
						</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
							<label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
							<input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
						</div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
							<label for="contact_number"><span class="text-danger font-weight-bold">*</span>Mobile Number</label>
							<input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('mobile', '09')}}" pattern="[0-9]{11}" placeholder="09*********" required>
						</div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
							<label for="email">Email Address</label>
							<input type="email" class="form-control" name="email" id="email" value="{{old('email')}}">
						</div>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                  <label for="howmanydose"><span class="text-danger font-weight-bold">*</span>Number of dose finished</label>
                  <select class="form-control" name="howmanydose" id="howmanydose" required>
                    <option disabled {{(is_null(old('howmanydose'))) ? 'selected' : ''}}>Choose...</option>
                    <option value="1" {{(old('howmanydose') == 1) ? 'selected' : ''}}>1st Dose Only</option>
                    <option value="2" {{(old('howmanydose') == 2) ? 'selected' : ''}}>1st and 2nd Dose</option>
                    <option value="3" {{(old('howmanydose') == 3) ? 'selected' : ''}}>1st, 2nd, and 3rd Dose</option>
                    <option value="4" {{(old('howmanydose') == 4) ? 'selected' : ''}}>1st, 2nd, 3rd, and 4th Dose</option>
                  </select>
                </div>
                <div id="vaccine1">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose1_date"><span class="text-danger font-weight-bold">*</span>1ST Dose Date</label>
                                <input type="date" class="form-control" name="dose1_date" id="dose1_date" value="{{old('dose1_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="dose1_manufacturer"><span class="text-danger font-weight-bold">*</span>1ST Dose Manufacturer</label>
                            <select class="form-control" name="dose1_manufacturer" id="dose1_manufacturer" required>
                                <option disabled {{(is_null(old('dose1_manufacturer'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Sinovac" {{(old('dose1_manufacturer') == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                <option value="AZ" {{(old('dose1_manufacturer') == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                <option value="Pfizer" {{(old('dose1_manufacturer') == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                <option value="Moderna" {{(old('dose1_manufacturer') == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                <option value="Gamaleya" {{(old('dose1_manufacturer') == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
                                <option value="Novavax" {{(old('dose1_manufacturer') == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                <option value="J&J" {{(old('dose1_manufacturer') == 'J&J') ? 'selected' : ''}}>Johnson and Johnson/Janssen</option>
                                <option value="Sinohpharm" {{(old('dose1_manufacturer') == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                <option value="SputnikLight" {{(old('dose1_manufacturer') == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="dose1_bakuna_center_text">Vaccination Site/Bakunahan</label>
                              <input type="text" class="form-control" name="dose1_bakuna_center_text" id="dose1_bakuna_center_text">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose1_batchno">Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose1_batchno" id="dose1_batchno">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose1_vaccinator_last_name">Vaccinator Surname</label>
                                <input type="text" class="form-control" name="dose1_vaccinator_last_name" id="dose1_vaccinator_last_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose1_vaccinator_first_name">Vaccinator First Name</label>
                                <input type="text" class="form-control" name="dose1_vaccinator_first_name" id="dose1_vaccinator_first_name">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="vaccine2" class="d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose2_date"><span class="text-danger font-weight-bold">*</span>2ND Dose Date</label>
                                <input type="date" class="form-control" name="dose2_date" id="dose2_date" value="{{old('dose2_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="dose2_manufacturer"><span class="text-danger font-weight-bold">*</span>2ND Dose Manufacturer</label>
                            <select class="form-control" name="dose2_manufacturer" id="dose2_manufacturer">
                                <option disabled {{(is_null(old('dose2_manufacturer'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Sinovac" {{(old('dose2_manufacturer') == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                <option value="AZ" {{(old('dose2_manufacturer') == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                <option value="Pfizer" {{(old('dose2_manufacturer') == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                <option value="Moderna" {{(old('dose2_manufacturer') == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                <option value="Gamaleya" {{(old('dose2_manufacturer') == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
                                <option value="Novavax" {{(old('dose2_manufacturer') == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                <option value="J&J" {{(old('dose2_manufacturer') == 'J&J') ? 'selected' : ''}}>Johnson and Johnson/Janssen</option>
                                <option value="Sinohpharm" {{(old('dose2_manufacturer') == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                <option value="SputnikLight" {{(old('dose2_manufacturer') == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="dose2_bakuna_center_text">Vaccination Site/Bakunahan</label>
                              <input type="text" class="form-control" name="dose2_bakuna_center_text" id="dose2_bakuna_center_text">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose2_batchno">Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose2_batchno" id="dose2_batchno">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose2_vaccinator_last_name">Vaccinator Surname</label>
                                <input type="text" class="form-control" name="dose2_vaccinator_last_name" id="dose2_vaccinator_last_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose2_vaccinator_first_name">Vaccinator First Name</label>
                                <input type="text" class="form-control" name="dose2_vaccinator_first_name" id="dose2_vaccinator_first_name">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="vaccine3" class="d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose3_date"><span class="text-danger font-weight-bold">*</span>3RD Dose (Booster 1) Date</label>
                                <input type="date" class="form-control" name="dose3_date" id="dose3_date" value="{{old('dose3_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="dose3_manufacturer"><span class="text-danger font-weight-bold">*</span>3RD Dose (Booster 1) Manufacturer</label>
                            <select class="form-control" name="dose3_manufacturer" id="dose3_manufacturer">
                                <option disabled {{(is_null(old('dose3_manufacturer'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Sinovac" {{(old('dose3_manufacturer') == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                <option value="AZ" {{(old('dose3_manufacturer') == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                <option value="Pfizer" {{(old('dose3_manufacturer') == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                <option value="Moderna" {{(old('dose3_manufacturer') == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                <option value="Gamaleya" {{(old('dose3_manufacturer') == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
                                <option value="Novavax" {{(old('dose3_manufacturer') == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                <option value="J&J" {{(old('dose3_manufacturer') == 'J&J') ? 'selected' : ''}}>Johnson and Johnson/Janssen</option>
                                <option value="Sinohpharm" {{(old('dose3_manufacturer') == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                <option value="SputnikLight" {{(old('dose3_manufacturer') == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="dose3_bakuna_center_text">Vaccination Site/Bakunahan</label>
                              <input type="text" class="form-control" name="dose3_bakuna_center_text" id="dose3_bakuna_center_text">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose3_batchno">Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose3_batchno" id="dose3_batchno">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose3_vaccinator_last_name">Vaccinator Surname</label>
                                <input type="text" class="form-control" name="dose3_vaccinator_last_name" id="dose3_vaccinator_last_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose3_vaccinator_first_name">Vaccinator First Name</label>
                                <input type="text" class="form-control" name="dose3_vaccinator_first_name" id="dose3_vaccinator_first_name">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="vaccine4" class="d-none">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose4_date"><span class="text-danger font-weight-bold">*</span>4TH Dose (Booster 2) Date</label>
                                <input type="date" class="form-control" name="dose4_date" id="dose4_date" value="{{old('dose4_date')}}" min="2021-01-01" max="{{date('Y-m-d')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="dose4_manufacturer"><span class="text-danger font-weight-bold">*</span>4TH Dose (Booster 2) Manufacturer</label>
                            <select class="form-control" name="dose4_manufacturer" id="dose4_manufacturer">
                                <option disabled {{(is_null(old('dose4_manufacturer'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Sinovac" {{(old('dose4_manufacturer') == 'Sinovac') ? 'selected' : ''}}>Sinovac</option>
                                <option value="AZ" {{(old('dose4_manufacturer') == 'AZ') ? 'selected' : ''}}>AstraZeneca</option>
                                <option value="Pfizer" {{(old('dose4_manufacturer') == 'Pfizer') ? 'selected' : ''}}>Pfizer</option>
                                <option value="Moderna" {{(old('dose4_manufacturer') == 'Moderna') ? 'selected' : ''}}>Moderna</option>
                                <option value="Gamaleya" {{(old('dose4_manufacturer') == 'Gamaleya') ? 'selected' : ''}}>Sputnik V/Gamaleya</option>
                                <option value="Novavax" {{(old('dose4_manufacturer') == 'Novavax') ? 'selected' : ''}}>Novavax</option>
                                <option value="J&J" {{(old('dose4_manufacturer') == 'J&J') ? 'selected' : ''}}>Johnson and Johnson/Janssen</option>
                                <option value="Sinohpharm" {{(old('dose4_manufacturer') == 'Sinohpharm') ? 'selected' : ''}}>Sinopharm</option>
                                <option value="SputnikLight" {{(old('dose4_manufacturer') == 'SputnikLight') ? 'selected' : ''}}>Sputnik Light</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="dose4_bakuna_center_text">Vaccination Site/Bakunahan</label>
                              <input type="text" class="form-control" name="dose4_bakuna_center_text" id="dose4_bakuna_center_text">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose4_batchno">Batch/Lot No.</label>
                                <input type="text" class="form-control" name="dose4_batchno" id="dose4_batchno">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose4_vaccinator_last_name">Vaccinator Surname</label>
                                <input type="text" class="form-control" name="dose4_vaccinator_last_name" id="dose4_vaccinator_last_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="dose4_vaccinator_first_name">Vaccinator First Name</label>
                                <input type="text" class="form-control" name="dose4_vaccinator_first_name" id="dose4_vaccinator_first_name">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="id_file"><span class="text-danger font-weight-bold">*</span>Upload ID Picture</label>
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
            </div>
        </div>
    </div>
    <script>
        $('#howmanydose').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 1) {
                $('#vaccine2').addClass('d-none');
                $('#vaccine3').addClass('d-none');
                $('#vaccine4').addClass('d-none');

                $('#dose2_date').prop('required', false);
                $('#dose2_manufacturer').prop('required', false);
                $('#dose3_date').prop('required', false);
                $('#dose3_manufacturer').prop('required', false);
                $('#dose4_date').prop('required', false);
                $('#dose4_manufacturer').prop('required', false);
            }
            else if($(this).val() == 2) {
                $('#vaccine2').removeClass('d-none');
                $('#vaccine3').addClass('d-none');
                $('#vaccine4').addClass('d-none');

                $('#dose2_date').prop('required', true);
                $('#dose2_manufacturer').prop('required', true);
                $('#dose3_date').prop('required', false);
                $('#dose3_manufacturer').prop('required', false);
                $('#dose4_date').prop('required', false);
                $('#dose4_manufacturer').prop('required', false);
            }
            else if($(this).val() == 3) {
                $('#vaccine2').removeClass('d-none');
                $('#vaccine3').removeClass('d-none');
                $('#vaccine4').addClass('d-none');

                $('#dose2_date').prop('required', true);
                $('#dose2_manufacturer').prop('required', true);
                $('#dose3_date').prop('required', true);
                $('#dose3_manufacturer').prop('required', true);
                $('#dose4_date').prop('required', false);
                $('#dose4_manufacturer').prop('required', false);
            }
            else if($(this).val() == 4) {
                $('#vaccine2').removeClass('d-none');
                $('#vaccine3').removeClass('d-none');
                $('#vaccine4').removeClass('d-none');

                $('#dose2_date').prop('required', true);
                $('#dose2_manufacturer').prop('required', true);
                $('#dose3_date').prop('required', true);
                $('#dose3_manufacturer').prop('required', true);
                $('#dose4_date').prop('required', true);
                $('#dose4_manufacturer').prop('required', true);
            }
        });
    </script>
@endsection