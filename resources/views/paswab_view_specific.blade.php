@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card">
            <div class="card-header font-weight-bold text-info">View Pa-swab Record</div>
            <div class="card-body">
                <div class="card mb-3">
                    <div class="card-header font-weight-bold">1. Consultation Details</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="">Type of Client</label>
                                  <input type="text" class="form-control" value="{{$data->getPatientType()}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="interviewDate">Date of Interview</label>
                                    <input type="date" class="form-control" value="{{$data->interviewDate}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">For Hospitalization</label>
                                    <input type="text" class="form-control" value="{{($data->isForHospitalization == 1) ? 'YES' : 'NO'}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">For Antigen</label>
                                    <input type="text" class="form-control" value="{{($data->forAntigen == 1) ? 'YES' : 'NO'}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Personal Message to CESU Staff/Encoders (Optional)</label>
                            <textarea class="form-control" name="" id="" rows="3" readonly>{{!is_null($data->patientmsg) ? $data->patientmsg : 'N/A'}}</textarea>
                        </div>
                    </div>   
                </div>
                <div class="card mb-3">
                    <div class="card-header font-weight-bold">2. Personal Information</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lname">Last Name</label>
                                    <input type="text" class="form-control font-weight-bold text-primary" value="{{$data->lname}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fname">First Name (and Suffix)</label>
                                    <input type="text" class="form-control font-weight-bold text-primary" value="{{$data->fname}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mname">Middle Name</label>
                                    <input type="text" class="form-control font-weight-bold text-primary" value="{{$data->mname}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bdate">Birthdate</label>
                                    <input type="date" class="form-control" value="{{$data->bdate}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender">Age / Gender</label>
                                    <input type="text" class="form-control" value="{{$data->getAge().' / '.$data->gender}}" readonly>
                                </div>
                                @if($data->gender == 'FEMALE')
                                <div class="form-group">
                                    <label for="isPregnant">Is the Patient Pregnant?</label>
                                    <input type="text" class="form-control" value="{{($data->isPregnant == 1) ? 'YES' : 'NO'}}" readonly>
                                </div>
                                @if($data->isPregnant == 1)
                                <div class="form-group">
                                    <label for="lmp">Last Menstrual Period (LMP)</label>
                                    <input type="text" class="form-control" value="{{($data->isPregnant == 1) ? date('m/d/Y', strtotime($data->ifPregnantLMP)).' - '.$data->diff4Humans($data->ifPregnantLMP) : 'N/A'}}" readonly>
                                </div>
                                @endif
                                @endif
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="cs">Civil Status</label>
                                    <input type="text" class="form-control" value="{{$data->cs}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="nationality">Nationality</label>
                                    <input type="text" class="form-control" value="{{$data->nationality}}" readonly>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile">Mobile Number</label>
                                    <input type="text" class="form-control" value="{{$data->mobile}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="philhealth">Philhealth Number</label>
                                    <input type="text" class="form-control" value="{{!is_null($data->philhealth) ? $data->philhealth : 'N/A'}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phoneno">Telephone Number (& Area Code)</label>
                                    <input type="text" class="form-control" value="{{!is_null($data->phoneno) ? $data->phoneno : 'N/A'}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="text" class="form-control" value="{{!is_null($data->email) ? $data->email : 'N/A'}}" readonly>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address_province">Province</label>
                                    <input type="text" class="form-control" value="{{$data->address_province}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label for="address_city">City</label>
                                  <input type="text" class="form-control" value="{{$data->address_city}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label for="address_brgy">Barangay</label>
                                  <input type="text" class="form-control" value="{{$data->address_brgy}}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_houseno">House No./Lot/Building</label>
                                    <input type="text" class="form-control" value="{{$data->address_houseno}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_street">Street/Purok/Sitio</label>
                                    <input type="text" class="form-control" value="{{$data->address_street}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header font-weight-bold">3. Occupation Details</div>
                    <div class="card-body">
                        <div id="occupationRow">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                      <label for="occupation">Occupation</label>
                                      <input type="text" class="form-control" value="{{(!is_null($data->occupation)) ? $data->occupation : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="occupation_name">Name of Workplace</label>
                                        <input type="text" class="form-control" value="{{(!is_null($data->occupation_name)) ? $data->occupation_name : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="natureOfWork">Nature of Work</label>
                                        <input type="text" class="form-control" value="{{(!is_null($data->natureOfWork)) ? $data->natureOfWork : 'N/A'}}" readonly>
                                    </div>
                                    @if($data->natureOfWork == 'OTHERS')
                                    <div class="form-group">
                                        <label for="natureOfWorkIfOthers">Please specify</label>
                                        <input type="text" class="form-control" value="{{(!is_null($data->natureOfWorkIfOthers)) ? $data->natureOfWorkIfOthers : 'N/A'}}" readonly>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header font-weight-bold">4. COVID-19 Vaccination Information</div>
                    <div class="card-body">
                        @if(!is_null($data->vaccinationDate1))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Name of Vaccine</label>
                                    <input type="text" class="form-control" name="" id="" value="{{$data->vaccinationName1}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="">1.) First Dose Date</label>
                                  <input type="date" class="form-control" name="" id="" value="{{$data->vaccinationDate1}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Vaccination Center/Facility</label>
                                    <input type="text" class="form-control" name="" id="" value="{{(!is_null($data->vaccinationFacility1)) ? $data->vaccinationFacility1 : 'N/A'}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Region of Health Facility</label>
                                    <input type="text" class="form-control" name="" id="" value="{{(!is_null($data->vaccinationRegion1)) ? $data->vaccinationRegion1 : 'N/A'}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Adverse Event/s</label>
                                    <input type="text" class="form-control" name="" id="" value="{{($data->haveAdverseEvents1 == 1) ? 'YES' : 'NO'}}" readonly>
                                </div>
                            </div>
                        </div>
                        @if(!is_null($data->vaccinationDate2))
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for="">2.) Second Dose Date</label>
                                  <input type="date" class="form-control" name="" id="" value="{{$data->vaccinationDate2}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Vaccination Center/Facility</label>
                                    <input type="text" class="form-control" name="" id="" value="{{(!is_null($data->vaccinationFacility2)) ? $data->vaccinationFacility2 : 'N/A'}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Region of Health Facility</label>
                                    <input type="text" class="form-control" name="" id="" value="{{(!is_null($data->vaccinationRegion2)) ? $data->vaccinationRegion2 : 'N/A'}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Adverse Event/s</label>
                                    <input type="text" class="form-control" name="" id="" value="{{($data->haveAdverseEvents2 == 1) ? 'YES' : 'NO'}}" readonly>
                                </div>
                            </div>
                        </div>
                        @endif
                        @else
                        <p class="text-center">Not Yet Vaccinated</p>
                        @endif
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header font-weight-bold">5. Clinical Information</div>
                    <div class="card-body">
                        <div id="ifHaveSymptoms">
                            <div class="form-group">
                                <label for="dateOnsetOfIllness">Date of Onset of Illness (Kailan Nagsimula ang Sintomas)</label>
                                <input type="date" class="form-control" value="{{(!is_null($data->dateOnsetOfIllness)) ? $data->dateOnsetOfIllness : 'N/A'}}" readonly>
                            </div>
                            <div class="card">
                                <div class="card-header">Signs and Symptoms</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Asymptomatic"
                                                  name="sasCheck[]"
                                                  id="signsCheck1"
                                                  {{(in_array("Asymptomatic", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck1">Asymptomatic</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Fever"
                                                  name="sasCheck[]"
                                                  id="signsCheck2"
                                                  {{(in_array("Fever", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck2">Fever</label>
                                            </div>
                                            @if(in_array("Fever", explode(",", $data->SAS)))
                                            <div id="divFeverChecked">
                                                <div class="form-group mt-2">
                                                  <label for="SASFeverDeg">Degrees (in Celcius)</label>
                                                  <input type="text" class="form-control" value="{{$data->SASFeverDeg}}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Cough"
                                                  name="sasCheck[]"
                                                  id="signsCheck3"
                                                  {{(in_array("Cough", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck3">Cough</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="General Weakness"
                                                  name="sasCheck[]"
                                                  id="signsCheck4"
                                                  {{(in_array("General Weakness", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck4">General Weakness</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Fatigue"
                                                  name="sasCheck[]"
                                                  id="signsCheck5"
                                                  {{(in_array("Fatigue", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck5">Fatigue</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Headache"
                                                  name="sasCheck[]"
                                                  id="signsCheck6"
                                                  {{(in_array("Headache", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck6">Headache</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Myalgia"
                                                  name="sasCheck[]"
                                                  id="signsCheck7"
                                                  {{(in_array("Myalgia", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck7">Myalgia</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Sore throat"
                                                  name="sasCheck[]"
                                                  id="signsCheck8"
                                                  {{(in_array("Sore throat", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck8">Sore Throat</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Coryza"
                                                  name="sasCheck[]"
                                                  id="signsCheck9"
                                                  {{(in_array("Coryza", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck9">Coryza</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Dyspnea"
                                                  name="sasCheck[]"
                                                  id="signsCheck10"
                                                  {{(in_array("Dyspnea", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck10">Dyspnea</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Anorexia"
                                                  name="sasCheck[]"
                                                  id="signsCheck11"
                                                  {{(in_array("Anorexia", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck11">Anorexia</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Nausea"
                                                  name="sasCheck[]"
                                                  id="signsCheck12"
                                                  {{(in_array("Nausea", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck12">Nausea</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Vomiting"
                                                  name="sasCheck[]"
                                                  id="signsCheck13"
                                                  {{(in_array("Vomiting", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck13">Vomiting</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Diarrhea"
                                                  name="sasCheck[]"
                                                  id="signsCheck14"
                                                  {{(in_array("Diarrhea", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck14">Diarrhea</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Altered Mental Status"
                                                  name="sasCheck[]"
                                                  id="signsCheck15"
                                                  {{(in_array("Altered Mental Status", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck15">Altered Mental Status</label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Anosmia (Loss of Smell)"
                                                  name="sasCheck[]"
                                                  id="signsCheck16"
                                                  {{(in_array("Anosmia (Loss of Smell)", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck16">Anosmia <small>(loss of smell, w/o any identified cause)</small></label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Ageusia (Loss of Taste)"
                                                  name="sasCheck[]"
                                                  id="signsCheck17"
                                                  {{(in_array("Ageusia (Loss of Taste)", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck17">Ageusia <small>(loss of taste, w/o any identified cause)</small></label>
                                            </div>
                                            <div class="form-check">
                                                <input
                                                  class="form-check-input"
                                                  type="checkbox"
                                                  value="Others"
                                                  name="sasCheck[]"
                                                  id="signsCheck18"
                                                  {{(in_array("Others", explode(",", $data->SAS))) ? 'checked' : ''}}
                                                  onclick="return false;"
                                                />
                                                <label class="form-check-label" for="signsCheck18">Others</label>
                                            </div>
                                            @if(in_array("Others", explode(",", $data->SAS)))
                                            <div id="divSASOtherChecked">
                                                <div class="form-group mt-2">
                                                  <label for="SASOtherRemarks">Specify Findings</label>
                                                  <input type="text" class="form-control" value="{{$data->SASOtherRemarks}}" readonly>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card mb-3">
                            <div class="card-header">Comorbidities</div>
                            <div class="card-body">
                                <div class="row comoOpt">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="None"
                                              name="comCheck[]"
                                              id="comCheck1"
                                              required
                                              onclick="return false;"
                                              {{(in_array("None", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck1">None</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="Hypertension"
                                              name="comCheck[]"
                                              id="comCheck2"
                                              required
                                              onclick="return false;"
                                              {{(in_array("Hypertension", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck2">Hypertension</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="Diabetes"
                                              name="comCheck[]"
                                              id="comCheck3"
                                              required
                                              onclick="return false;"
                                              {{(in_array("Diabetes", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck3">Diabetes</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="Heart Disease"
                                              name="comCheck[]"
                                              id="comCheck4"
                                              required
                                              onclick="return false;"
                                              {{(in_array("Heart Disease", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck4">Heart Disease</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="Lung Disease"
                                              name="comCheck[]"
                                              id="comCheck5"
                                              required
                                              onclick="return false;"
                                              {{(in_array("Lung Disease", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck5">Lung Disease</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="Gastrointestinal"
                                              name="comCheck[]"
                                              id="comCheck6"
                                              required
                                              onclick="return false;"
                                              {{(in_array("Gastrointestinal", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck6">Gastrointestinal</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="Genito-urinary"
                                              name="comCheck[]"
                                              id="comCheck7"
                                              required
                                              onclick="return false;"
                                              {{(in_array("Genito-urinary", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck7">Genito-urinary</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="Neurological Disease"
                                              name="comCheck[]"
                                              id="comCheck8"
                                              required
                                              onclick="return false;"
                                              {{(in_array("Neurological Disease", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck8">Neurological Disease</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="Cancer"
                                              name="comCheck[]"
                                              id="comCheck9"
                                              required
                                              onclick="return false;"
                                              {{(in_array("Cancer", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck9">Cancer</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                              class="form-check-input"
                                              type="checkbox"
                                              value="Others"
                                              name="comCheck[]"
                                              id="comCheck10"
                                              onclick="return false;"
                                              required
                                              {{(in_array("Others", explode(",", $data->COMO))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="comCheck10">Others</label>
                                        </div>
                                        @if(in_array("Others", explode(",", $data->COMO)))
                                        <div id="divComOthersChecked">
                                            <div class="form-group mt-2">
                                              <label for="COMOOtherRemarks">Specify Findings</label>
                                              <input type="text" class="form-control" value="{{$data->COMOOtherRemarks}}" readonly>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header font-weight-bold">6. Chest X-ray Details</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                          <label for="">Date done</label>
                                          <input type="date" class="form-control" value="{{(!is_null($data->imagingDoneDate)) ? $data->imagingDoneDate : NULL}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                          <label for="imagingDone">Chest X-Ray Type</label>
                                          <input type="text" class="form-control" value="{{$data->imagingDone}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="imagingResult">Results</label>
                                          <input type="text" class="form-control" value="{{(!is_null($data->imagingResult)) ? $data->imagingResult : NULL}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                    </div>
                                    <div class="col-md-4">
                                        @if($data->imagingDone == 'OTHERS')
                                        <div id="divImagingOthers">
                                            <div class="form-group">
                                              <label for="imagingOtherFindings">Specify findings</label>
                                              <input type="text" class="form-control" value="{{(!is_null($data->imagingOtherFindings)) ? $data->imagingOtherFindings : NULL}}" readonly>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header font-weight-bold">7. Exposure History</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="expoitem1">History of exposure to known probable and/or confirmed COVID-19 case 14 days before the onset of signs and symptoms?  OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                                    <input type="text" class="form-control" value="{{($data->expoitem1 == '1') ? 'Yes' : 'No'}}" readonly>
                                </div>
                                @if($data->expoitem1 == '1')
                                <div id="divExpoitem1">
                                    <div class="form-group">
                                        <label for="">Date of Last Contact</label>
                                        <input type="text" class="form-control" value="{{date('m/d/Y - l', strtotime($data->expoDateLastCont))}}" readonly>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">List the Names of your Close Contact</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="contact1Name">Name of Close Contact #1</label>
                                                      <input type="text" class="form-control" value="{{(!is_null($data->contact1Name)) ? $data->contact1Name : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact1No">Mobile Number of Close Contact #1</label>
                                                        <input type="text" class="form-control" value="{{(!is_null($data->contact1No)) ? $data->contact1No : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="contact2Name">Name of Close Contact #2</label>
                                                      <input type="text" class="form-control" value="{{(!is_null($data->contact2Name)) ? $data->contact2Name : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact2No">Mobile Number of Close Contact #2</label>
                                                        <input type="text" class="form-control" value="{{(!is_null($data->contact2No)) ? $data->contact2No : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="contact3Name">Name of Close Contact #3</label>
                                                      <input type="text" class="form-control" value="{{(!is_null($data->contact3Name)) ? $data->contact3Name : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact3No">Mobile Number of Close Contact #3</label>
                                                        <input type="text" class="form-control" value="{{(!is_null($data->contact3No)) ? $data->contact3No : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="contact4Name">Name of Close Contact #4</label>
                                                      <input type="text" class="form-control" value="{{(!is_null($data->contact4Name)) ? $data->contact4Name : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="contact4No">Mobile Number of Close Contact #4</label>
                                                        <input type="text" class="form-control" value="{{(!is_null($data->contact4No)) ? $data->contact4No : 'N/A'}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#acceptmodal"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i>Approve</button>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectmodal"><i class="fa fa-times-circle mr-2" aria-hidden="true"></i>Reject</button>
            </div>
        </div>
    </div>
    
    <form action="/forms/paswab/{{$data->id}}/approve" method="POST">
        @csrf
        <div class="modal fade" id="acceptmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Accept Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Referral Code</label>
                            <input type="text" class="form-control" readonly value="{{(!is_null($data->linkCode)) ? $data->linkCode : 'N/A'}}">
                        </div>
                        <div class="form-group">
                            <label for="">Name of Patient / Age / Gender</label>
                            <input type="text" class="form-control font-weight-bold text-primary" readonly value="{{$data->getName()}} / {{$data->getAge()}} / {{substr($data->gender,0,1)}}">
                        </div>
                        <div class="form-group">
                            <label for="">Patient Address</label>
                            <input type="text" class="form-control" readonly value="{{$data->address_street}}, BRGY. {{$data->address_brgy}}, {{$data->address_city}}, {{$data->address_province}}">
                        </div>
                        <div class="form-group">
                            <label for="">Pregnant / LMP</label>
                            <input type="text" class="form-control" readonly value="{{($data->isPregnant == 1) ? 'YES / '.date('m/d/Y', strtotime($data->ifPregnantLMP)).' - '.$data->diff4Humans($data->ifPregnantLMP) : 'NO'}}">
                        </div>
                        <div class="form-group">
                            <label for="">Type of Client</label>
                            <input type="text" class="form-control" readonly value="{{$data->getPatientType()}}">
                        </div>
                        <div class="form-group">
                            <label for="">Vaccinated</label>
                            <input type="text" class="form-control" readonly value="{{(!is_null($data->vaccinationDate1)) ? 'YES ('.$data->vaccinationName1.') - ' : 'NO'}}{{(!is_null($data->vaccinationDate1)) ? (!is_null($data->vaccinationDate2)) ? '2nd Dose' : '1st Dose' : ''}}">
                        </div>
                        <div class="form-group">
                            <label for="">Date of Last Exposure</label>
                            <input type="text" class="form-control" readonly value="{{(!is_null($data->expoDateLastCont)) ? date('m/d/Y (l)', strtotime($data->expoDateLastCont)).' - '.$data->toDateTimeString() : 'N/A'}}">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="interviewerName"><span class="text-danger font-weight-bold">*</span>Name of Interviewer</label>
                            <input type="text" class="form-control" name="interviewerName" id="interviewerName" value="{{($data->getDefaultInterviewerName())}}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="testDateCollected1"><span class="text-danger font-weight-bold">*</span>Date of Swab Collection</label>
                            <input type="date" class="form-control" name="testDateCollected1" id="testDateCollected1" min="{{date('Y-m-d')}}" max="{{date('Y-12-31')}}" value="{{old('testDateCollected1')}}" required>
                        </div>
                        @if($data->forAntigen != 1)
                        <div class="form-group">
                            <label for="testType1"><span class="text-danger font-weight-bold">*</span>Type of Test</label>
                            <select class="form-control" name="testType1" id="testType1" required>
                                <option value="" disabled {{(is_null(old('testType1'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="OPS" {{(old('testType1') == 'OPS') ? 'selected' : ''}}>RT-PCR (OPS)</option>
                                <option value="NPS" {{(old('testType1') == 'NPS') ? 'selected' : ''}}>RT-PCR (NPS)</option>
                                <option value="OPS AND NPS" {{(old('testType1') == 'OPS AND NPS') ? 'selected' : ''}}>RT-PCR (OPS and NPS)</option>
                                <option value="ANTIGEN" {{(old('testType1') == 'ANTIGEN') ? 'selected' : ''}}>Antigen Test</option>
                                <option value="ANTIBODY" {{(old('testType1') == 'ANTIBODY') ? 'selected' : ''}}>Antibody Test</option>
                                <option value="OTHERS" {{(old('testType1') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                            </select>
                        </div>
                        <div id="divTypeOthers1">
                            <div class="form-group">
                                <label for="testTypeOtherRemarks1"><span class="text-danger font-weight-bold">*</span>Specify Type/Reason</label>
                                <input type="text" class="form-control" name="testTypeOtherRemarks1" id="testTypeOtherRemarks1" value="{{old('testTypeOtherRemarks1')}}">
                            </div>
                            <div id="ifAntigen1">
                                <div class="form-group">
                                    <label for="antigenKit1"><span class="text-danger font-weight-bold">*</span>Antigen Kit</label>
                                    <input type="text" class="form-control" name="antigenKit1" id="antigenKit1" value="{{old('antigenKit1')}}">
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="form-group">
                          <label for="testType1"><span class="text-danger font-weight-bold">*</span>Type of Test</label>
                          <input type="text" class="form-control" name="testType1" id="testType1" value="ANTIGEN" readonly>
                        </div>
                        <div class="form-group">
                            <label for="testTypeOtherRemarks1"><span class="text-danger font-weight-bold">*</span>Specify Type/Reason</label>
                            <input type="text" class="form-control" name="testTypeOtherRemarks1" id="testTypeOtherRemarks1" value="{{old('testTypeOtherRemarks1')}}">
                        </div>
                        <div id="ifAntigen1">
                            <div class="form-group">
                                <label for="antigenKit1"><span class="text-danger font-weight-bold">*</span>Antigen Kit</label>
                                <input type="text" class="form-control" name="antigenKit1" id="antigenKit1" value="{{old('antigenKit1')}}">
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form action="/forms/paswab/{{$data->id}}/reject" method="POST">
        @csrf
        <div class="modal fade" id="rejectmodal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Request</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="reason">Specify Reason for Rejection</label>
                          <input type="text" class="form-control" name="reason" id="reason" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-times-circle mr-2" aria-hidden="true"></i>Reject Record</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        @if($data->forAntigen != 1)
        $('#testType1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'OTHERS' || $(this).val() == 'ANTIGEN') {
                $('#divTypeOthers1').show();
                $('#testTypeOtherRemarks1').prop('required', true);
                if($(this).val() == 'ANTIGEN') {
                    $('#ifAntigen1').show();
                    $('#antigenKit1').prop('required', true);
                }
                else {
                    $('#ifAntigen1').hide();
                    $('#antigenKit1').prop('required', false);
                }
            }
            else {
                $('#divTypeOthers1').hide();
                $('#testTypeOtherRemarks1').empty();
                $('#testTypeOtherRemarks1').prop('required', false);

                $('#ifAntigen1').hide();
                $('#antigenKit1').prop('required', false);
            }
        }).trigger('change');
        @endif
    </script>
@endsection