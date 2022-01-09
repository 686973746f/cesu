@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('selfreport.finishAssessment', ['id' => $data->id])}}" method="POST">
            @csrf
            <div class="card border-info">
                <div class="card-header bg-info text-white font-weight-bold">Assess Positive Patient ({{$data->getName()}} <small>#{{$data->id}}</small>)</div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <p><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>Assess the patient by using the Contact Information Provided by Patient (Mobile Number or Email) at the bottom.</p>
                        <p>After completing the assessment, the patient record will be counted in the official list of Active Cases and will be added in the official patient records.</p>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">1. Patient Information</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" class="form-control font-weight-bold" value="{{$data->lname}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fname">First Name (and Suffix)</label>
                                        <input type="text" class="form-control font-weight-bold" value="{{$data->fname}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" class="form-control font-weight-bold" value="{{$data->mname}}" readonly>
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
                        <div class="card-header font-weight-bold">2. Occupation Details</div>
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
                        <div class="card-header font-weight-bold">3. COVID-19 Vaccination Information</div>
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
                        <div class="card-header font-weight-bold">4. Clinical Information</div>
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
                                                  id="comCheck1"
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
                                                  id="comCheck2"
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
                                                  id="comCheck3"
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
                                                  id="comCheck4"
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
                                                  id="comCheck5"
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
                                                  id="comCheck6"
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
                                                  id="comCheck7"
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
                                                  id="comCheck8"
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
                                                  id="comCheck9"
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
                                                  id="comCheck10"
                                                  onclick="return false;"
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
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">5. Laboratory Information (Swab Details and Result)</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Have you ever tested positive using RT-PCR before?</label>
                                        <input type="text" class="form-control" value="{{($data->testedPositiveUsingRTPCRBefore == 1) ? 'YES' : 'NO'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Number of previous RT-PCR swabs done</label>
                                        <input type="text" class="form-control" value="{{$data->testedPositiveNumOfSwab}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div id="divIfTestedPositiveUsingRTPCR">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date of Specimen Collection</label>
                                            <input type="date" class="form-control" value="{{$data->testedPositiveSpecCollectedDate}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Laboratory</label>
                                            <input type="text" class="form-control" value="{{$data->testedPositiveLab}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Collected</label>
                                        <input type="date" class="form-control" value="{{$data->testDateCollected1}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date released</label>
                                        <input type="date" class="form-control" value="{{$data->testDateReleased1}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Laboratory</label>
                                        <input type="text" class="form-control" value="{{$data->testLaboratory1}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Type of test</label>
                                        <select class="form-control" id="testType1" disabled>
                                            <option value="OPS" {{($data->testType1 == 'OPS') ? 'selected' : ''}}>RT-PCR (OPS)</option>
                                            <option value="NPS" {{($data->testType1 == 'NPS') ? 'selected' : ''}}>RT-PCR (NPS)</option>
                                            <option value="OPS AND NPS" {{($data->testType1 == 'OPS AND NPS') ? 'selected' : ''}}>RT-PCR (OPS and NPS)</option>
                                            <option value="ANTIGEN" {{($data->testType1 == 'ANTIGEN') ? 'selected' : ''}}>Antigen Test</option>
                                            <option value="ANTIBODY" {{($data->testType1 == 'ANTIBODY') ? 'selected' : ''}}>Antibody Test</option>
                                            <option value="OTHERS" {{($data->testType1 == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                        </select>
                                    </div>
                                    <div id="divTypeOthers1">
                                        <div class="form-group">
                                            <label>Specify Reason</label>
                                            <input type="text" class="form-control" value="{{!is_null($data->testTypeAntigenRemarks1) ? $data->testTypeAntigenRemarks1 : ($data->testTypeOtherRemarks1 ? $data->testTypeOtherRemarks1 : 'N/A')}}" readonly>
                                        </div>
                                    </div>
                                    <div id="ifAntigen1">
                                        <div class="form-group">
                                            <label>Antigen Kit</label>
                                            <input type="text" class="form-control" value="{{(!is_null($data->antigenKit1)) ? $data->antigenKit1 : 'N/A'}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <label>Submitted Positive Swab Test Result:</label>
                                <a href="{{route('selfreport.viewdocument', ['id' => $data->id])}}" class="btn btn-primary">View Document</a>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">5. Chest X-ray Details</div>
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
                    <div class="card border-primary">
                        <div class="card-header font-weight-bold text-white bg-primary">7. Assessment for Patient</div>
                        <div class="card-body">
                            <div class="alert alert-info" role="alert">
                                <strong class="text-danger">Note:</strong> All fields marked with an asterisk (<span class="text-danger font-weight-bold">*</span>) are required.
                            </div>
                            <div class="card mb-3">
                                <div class="card-header font-weight-bold">Disposition at Time of Report / Quarantine Status</div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="dispositionType"><span class="text-danger font-weight-bold">*</span>Status</label>
                                        <select class="form-control" name="dispositionType" id="dispositionType">
                                            <option value="1" {{(old('dispositionType') == 1) ? 'selected' : ''}}>Admitted in hospital</option>
                                            <option value="6" {{(old('dispositionType') == 6) ? 'selected' : ''}}>Admitted in General Trias Isolation Facility</option>
                                            <option value="2" {{(old('dispositionType') == 2) ? 'selected' : ''}}>Admitted in OTHER isolation/quarantine facility</option>
                                            <option value="3" {{(old('dispositionType') == 3 || is_null(old('dispositionType'))) ? 'selected' : ''}}>In home isolation/quarantine</option>
                                            <option value="4" {{(old('dispositionType') == 4) ? 'selected' : ''}}>Discharged to home</option>
                                            <option value="5" {{(old('dispositionType') == 5) ? 'selected' : ''}}>Others</option>
                                        </select>
                                    </div>
                                    <div id="divYes5">
                                        <div class="form-group">
                                            <label for="dispositionName" id="dispositionlabel"></label>
                                            <input type="text" class="form-control" name="dispositionName" id="dispositionName" value="{{old('dispositionName')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div id="divYes6">
                                        <div class="form-group">
                                            <label for="dispositionDate" id="dispositiondatelabel"></label>
                                            <input type="datetime-local" class="form-control" name="dispositionDate" id="dispositionDate" value="{{old('dispositionDate', date('Y-m-d\TH:i'))}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3">
                                <div class="card-header font-weight-bold">Exposure History</div>
                                <div class="card-body">
                                    <div class="form-group">
                                      <label for="expoitem1"><span class="text-danger font-weight-bold">*</span>History of exposure to known probable and/or confirmed COVID-19 case 14 days before the onset of signs and symptoms?  OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                                      <select class="form-control" name="expoitem1" id="expoitem1" required>
                                            <option value="2" {{(old('expoitem1') == 2) ? 'selected' : ''}}>No</option>
                                            <option value="1" {{(old('expoitem1') == 1) ? 'selected' : ''}}>Yes</option>
                                            <option value="3" {{(old('expoitem1') == 3) ? 'selected' : ''}}>Unknown</option>
                                      </select>
                                    </div>
                                    <div id="divExpoitem1">
                                        <div class="form-group">
                                          <label for=""><span class="text-danger font-weight-bold">*</span>Date of Last Contact</label>
                                          <input type="date" class="form-control" name="expoDateLastCont" id="expoDateLastCont" max="{{date('Y-m-d')}}" value="{{old('expoDateLastCont')}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="expoitem2"><span class="text-danger font-weight-bold">*</span>Has the patient been in a place with a known COVID-19 transmission 14 days before the onset of signs and symptoms? OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                                        <select class="form-control" name="expoitem2" id="expoitem2" required>
                                          <option value="0" {{(old('expoitem2') == 2) ? 'selected' : ''}}>No</option>
                                          <option value="1" {{(old('expoitem2') == 1) ? 'selected' : ''}}>Yes, Local</option>
                                          <option value="2" {{(old('expoitem2') == 2) ? 'selected' : ''}}>Yes, International</option>
                                          <option value="3" {{(old('expoitem2') == 3) ? 'selected' : ''}}>Unknown exposure</option>
                                        </select>
                                    </div>
                                    <div id="divTravelInt">
                                        <div class="form-group">
                                            <label for="intCountry"><span class="text-danger font-weight-bold">*</span>If International Travel, country of origin</label>
                                            <select class="form-control" name="intCountry" id="intCountry">
                                                <option value="" {{(is_null(old('intCountry'))) ? 'selected disabled' : ''}}>Choose...</option>
                                                  @foreach ($countries as $country)
                                                      @if($country != 'Philippines')
                                                          <option value="{{$country}}" {{(old('intCountry') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                                      @endif
                                                  @endforeach
                                            </select>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card mb-3">
                                                    <div class="card-header">Inclusive travel dates</div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                  <label for="intDateFrom">From</label>
                                                                  <input type="date" class="form-control" name="intDateFrom" id="intDateFrom" value="{{old('intDateFrom')}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="intDateTo">From</label>
                                                                    <input type="date" class="form-control" name="intDateTo" id="intDateTo" value="{{old('intDateTo')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="intWithOngoingCovid">With ongoing COVID-19 community transmission?</label>
                                                    <select class="form-control" name="intWithOngoingCovid" id="intWithOngoingCovid">
                                                        <option value="NO" {{(old('intWithOngoingCovid') == "NO") ? 'selected' : ''}}>No</option>
                                                        <option value="YES" {{(old('intWithOngoingCovid') == "YES") ? 'selected' : ''}}>Yes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                          <label for="intVessel">Airline/Sea vessel</label>
                                                          <input type="text" class="form-control" name="intVessel" id="intVessel" value="{{old('intVessel')}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="intVesselNo">Flight/Vessel Number</label>
                                                            <input type="text" class="form-control" name="intVesselNo" id="intVesselNo" value="{{old('intVesselNo')}}" style="text-transform: uppercase;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="intDateDepart">Date of departure</label>
                                                            <input type="date" class="form-control" name="intDateDepart" id="intDateDepart" value="{{old('intDateDepart')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="intDateArrive">Date of arrival in PH</label>
                                                            <input type="date" class="form-control" name="intDateArrive" id="intDateArrive" value="{{old('intDateArrive')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="divTravelLoc">
                                        <div class="card">
                                            <div class="card-header">
                                                If Local Travel, specify travel places (<i>Check all that apply, provide name of facility, address, and inclusive travel dates</i>)
                                            </div>
                                            <div class="card-body">
                                                <div class="form-check">
                                                  <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited1" value="Health Facility" {{(is_array(old('placevisited')) && in_array("Health Facility", old('placevisited'))) ? 'checked' : ''}}>
                                                    Health Facility
                                                  </label>
                                                </div>
                                                <div id="divLocal1" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName1">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName1" id="locName1" value="{{old('locName1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress1">Location</label>
                                                                <input class="form-control" type="text" name="locAddress1" id="locAddress1" value="{{old('locAddress1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom1">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom1" id="locDateFrom1" value="{{old('locDateFrom1')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo1">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo1" id="locDateTo1" value="{{old('locDateTo1')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid1">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid1" id="locWithOngoingCovid1">
                                                                <option value="NO" {{(old('locWithOngoingCovid1') == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid1') == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited2" value="Closed Settings" {{(is_array(old('placevisited')) && in_array("Cloed Settings", old('placevisited'))) ? 'checked' : ''}}>
                                                      Closed Settings
                                                    </label>
                                                </div>
                                                <div id="divLocal2" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName2">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName2" id="locName2" value="{{old('locName2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress2">Location</label>
                                                                <input class="form-control" type="text" name="locAddress2" id="locAddress2" value="{{old('locAddress2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom2">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom2" id="locDateFrom2" value="{{old('locDateFrom2')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo2">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo2" id="locDateTo2" value="{{old('locDateTo2')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid2">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid2" id="locWithOngoingCovid2">
                                                                <option value="NO" {{(old('locWithOngoingCovid2') == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid2') == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited3" value="School" {{(is_array(old('placevisited')) && in_array("School", old('placevisited'))) ? 'checked' : ''}}>
                                                      School
                                                    </label>
                                                </div>
                                                <div id="divLocal3" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName3">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName3" id="locName3" value="{{old('locName3')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress3">Location</label>
                                                                <input class="form-control" type="text" name="locAddress3" id="locAddress3" value="{{old('locAddress3')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom3">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom3" id="locDateFrom3" value="{{old('locDateFrom3')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo3">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo3" id="locDateTo3" value="{{old('locDateTo3')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid3">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid3" id="locWithOngoingCovid3">
                                                                <option value="NO" {{(old('locWithOngoingCovid3') == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid3') == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited4" value="Workplace" {{(is_array(old('placevisited')) && in_array("Workplace", old('placevisited'))) ? 'checked' : ''}}>
                                                      Workplace
                                                    </label>
                                                </div>
                                                <div id="divLocal4" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName4">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName4" id="locName4" value="{{old('locName4')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress4">Location</label>
                                                                <input class="form-control" type="text" name="locAddress4" id="locAddress4" value="{{old('locAddress4')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom4">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom4" id="locDateFrom4" value="{{old('locDateFrom4')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo4">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo4" id="locDateTo4" value="{{old('locDateTo4')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid4">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid4" id="locWithOngoingCovid4">
                                                                <option value="NO" {{(old('locWithOngoingCovid4') == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid4') == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited5" value="Market" {{(is_array(old('placevisited')) && in_array("Market", old('placevisited'))) ? 'checked' : ''}}>
                                                      Market
                                                    </label>
                                                </div>
                                                <div id="divLocal5" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName5">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName5" id="locName5" value="{{old('locName5')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress5">Location</label>
                                                                <input class="form-control" type="text" name="locAddress5" id="locAddress5" value="{{old('locAddress5')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom5">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom5" id="locDateFrom5" value="{{old('locDateFrom5')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo5">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo5" id="locDateTo5" value="{{old('locDateTo5')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid5">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid5" id="locWithOngoingCovid5">
                                                                <option value="NO" {{(old('locWithOngoingCovid5') == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid5') == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited6" value="Social Gathering" {{(is_array(old('placevisited')) && in_array("Social Gathering", old('placevisited'))) ? 'checked' : ''}}>
                                                      Social Gathering
                                                    </label>
                                                </div>
                                                <div id="divLocal6" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName6">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName6" id="locName6" value="{{old('locName6')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress6">Location</label>
                                                                <input class="form-control" type="text" name="locAddress6" id="locAddress6" value="{{old('locAddress6')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom6">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom6" id="locDateFrom6" value="{{old('locDateFrom6')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo6">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo6" id="locDateTo6" value="{{old('locDateTo6')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid6">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid6" id="locWithOngoingCovid6">
                                                                <option value="NO" {{(old('locWithOngoingCovid6') == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid6') == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited7" value="Others" {{(is_array(old('placevisited')) && in_array("Others", old('placevisited'))) ? 'checked' : ''}}>
                                                      Others
                                                    </label>
                                                </div>
                                                <div id="divLocal7" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locName7">Name of Place</label>
                                                              <input class="form-control" type="text" name="locName7" id="locName7" value="{{old('locName7')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locAddress7">Location</label>
                                                                <input class="form-control" type="text" name="locAddress7" id="locAddress7" value="{{old('locAddress7')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card">
                                                                <div class="card-header">Inclusive Travel Dates</div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateFrom7">From</label>
                                                                                <input class="form-control" type="date" name="locDateFrom7" id="locDateFrom7" value="{{old('locDateFrom7')}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label for="locDateTo7">To</label>
                                                                                <input class="form-control" type="date" name="locDateTo7" id="locDateTo7" value="{{old('locDateTo7')}}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                              <label for="locWithOngoingCovid7">With ongoing COVID-19 Community Transmission?</label>
                                                              <select class="form-control" name="locWithOngoingCovid7" id="locWithOngoingCovid7">
                                                                <option value="NO" {{(old('locWithOngoingCovid7') == "NO") ? 'selected' : ''}}>No</option>
                                                                <option value="YES" {{(old('locWithOngoingCovid7') == "YES") ? 'selected' : ''}}>Yes</option>
                                                              </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                      <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited8" value="Transport Service" {{(is_array(old('placevisited')) && in_array("Transport Service", old('placevisited'))) ? 'checked' : ''}}>
                                                      Transport Service
                                                    </label>
                                                </div>
                                                <div id="divLocal8" class="mt-3">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                              <label for="localVessel1">1. Airline/Sea vessel/Bus line/Train</label>
                                                              <input type="text" class="form-control" name="localVessel1" id="localVessel1" value="{{old('localVessel1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localVesselNo1">Flight/Vessel/Bus No.</label>
                                                                <input type="text" class="form-control" name="localVesselNo1" id="localVesselNo1" value="{{old('localVesselNo1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localOrigin1">Place of Origin</label>
                                                                <input type="text" class="form-control" name="localOrigin1" id="localOrigin1" value="{{old('localOrigin1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateDepart1">Departure Date</label>
                                                                <input type="date" class="form-control" name="localDateDepart1" id="localDateDepart1" value="{{old('localDateDepart1')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDest1">Destination</label>
                                                                <input type="text" class="form-control" name="localDest1" id="localDest1" value="{{old('localDest1')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateArrive1">Date of Arrival</label>
                                                                <input type="text" class="form-control" name="localDateArrive1" id="localDateArrive1" value="{{old('localDateArrive1')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                              <label for="localVessel2">2. Airline/Sea vessel/Bus line/Train</label>
                                                              <input type="text" class="form-control" name="localVessel2" id="localVessel2" value="{{old('localVessel2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localVesselNo2">Flight/Vessel/Bus No.</label>
                                                                <input type="text" class="form-control" name="localVesselNo2" id="localVesselNo2" value="{{old('localVesselNo2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localOrigin2">Place of Origin</label>
                                                                <input type="text" class="form-control" name="localOrigin2" id="localOrigin2" value="{{old('localOrigin2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateDepart2">Departure Date</label>
                                                                <input type="date" class="form-control" name="localDateDepart2" id="localDateDepart2" value="{{old('localDateDepart2')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDest2">Destination</label>
                                                                <input type="text" class="form-control" name="localDest2" id="localDest2" value="{{old('localDest2')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="localDateArrive2">Date of Arrival</label>
                                                                <input type="date" class="form-control" name="localDateArrive2" id="localDateArrive2" value="{{old('localDateArrive2')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mt-3">
                                        <div class="card-header">List the names of persons who were with you two days prior to onset of illness until this date and their contact numbers.</div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="alert alert-info" role="alert">
                                                        <p>- If symptomatic, provide names and contact numbers of persons who were with the patient two days prior to onset of illness until this date.</p>
                                                        <p>- If asymptomatic, provide names and contact numbers of persons who were with the patient on the day specimen was submitted for testing until this date.</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="card">
                                                        <div class="card-header">Name</div>
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                              <input type="text" class="form-control" name="contact1Name" id="contact1Name" value="{{old('contact1Name')}}" style="text-transform: uppercase;">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact2Name" id="contact2Name" value="{{old('contact2Name')}}" style="text-transform: uppercase;">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact3Name" id="contact3Name" value="{{old('contact3Name')}}" style="text-transform: uppercase;">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact4Name" id="contact4Name" value="{{old('contact4Name')}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <div class="card-header">Contact Number</div>
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact1No" id="contact1No" value="{{old('contact1No')}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact2No" id="contact2No" value="{{old('contact2No')}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact3No" id="contact3No" value="{{old('contact3No')}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" name="contact4No" id="contact4No" value="{{old('contact3No')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($editable)
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i>Complete Assessment</button>
                </div>
                @endif
            </div>
        </form>
    </div>
    <script>
        $('#dispositionType').change(function (e) {
            e.preventDefault();
            $('#dispositionDate').prop("type", "datetime-local");
            
            if($(this).val() == '1' || $(this).val() == '2') {
                $('#dispositionName').prop('required', true);
                $('#dispositionDate').prop('required', true);
            }
            else if ($(this).val() == '3' || $(this).val() == '4') {
                $('#dispositionName').prop('required', false);
                $('#dispositionDate').prop('required', true);
            }
            else if ($(this).val() == '5') {
                $('#dispositionName').prop('required', true);
                $('#dispositionDate').prop('required', false);
            }
            else if ($(this).val() == '6') {
                $('#dispositionName').prop('required', false);
                $('#dispositionDate').prop('required', true);
            }
            else if($(this).val().length == 0){
                $('#dispositionName').prop('required', false);
                $('#dispositionDate').prop('required', false);
            }

            if($(this).val() == '1') {
                $('#divYes5').show();
                $('#divYes6').show();

                $('#dispositionlabel').text("Name of Hospital");
                $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
            }
            else if($(this).val() == '2') {
                $('#divYes5').show();
                $('#divYes6').show();

                $('#dispositionlabel').text("Name of Facility");
                $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
            }
            else if($(this).val() == '3') {
                $('#divYes5').hide();
                $('#divYes6').show();

                $('#dispositiondatelabel').text("Date and Time isolated/quarantined at home");
            }
            else if($(this).val() == '4') {
                $('#divYes5').hide();
                $('#divYes6').show();

                $('#dispositionDate').prop("type", "date");

                $('#dispositiondatelabel').text("Date of Discharge");
            }
            else if($(this).val() == '5') {
                $('#divYes5').show();
                $('#divYes6').hide();

                $('#dispositionlabel').text("State Reason");
            }
            else if($(this).val() == '6') {
                $('#divYes5').hide();
                $('#divYes6').show();

                $('#dispositiondatelabel').text("Date and Time Started");
            }
            else if($(this).val().length == 0){
                $('#divYes5').hide();
                $('#divYes6').hide();
            }
        }).trigger('change');

        $('#expoitem1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "1") {
                $('#divExpoitem1').show();
                $('#expoDateLastCont').prop('required', true);
            }
            else {
                $('#divExpoitem1').hide();
                $('#expoDateLastCont').val(null);
                $('#expoDateLastCont').prop('required', false);
            }
        }).trigger('change');

        $('#expoitem2').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 0 || $(this).val() == 3) {
                $('#divTravelInt').hide();
                $('#divTravelLoc').hide();
            }
            else if($(this).val() == 1) {
                $('#divTravelInt').hide();

                $('#intCountry').prop('required', false);
                $('#intDateFrom').prop('required', false);
                $('#intDateTo').prop('required', false);
                $('#intWithOngoingCovid').prop('required', false);
                $('#intVessel').prop('required', false);
                $('#intVesselNo').prop('required', false);
                $('#intDateDepart').prop('required', false);
                $('#intDateArrive').prop('required', false);
                
                $('#divTravelLoc').show();
            }
            else if($(this).val() == 2) {
                $('#divTravelInt').show();

                $('#intCountry').prop('required', true);
                $('#intDateFrom').prop('required', false);
                $('#intDateTo').prop('required', false);
                $('#intWithOngoingCovid').prop('required', false);
                $('#intVessel').prop('required', false);
                $('#intVesselNo').prop('required', false);
                $('#intDateDepart').prop('required', false);
                $('#intDateArrive').prop('required', false);

                $('#divTravelLoc').hide();
            }
        }).trigger('change');

        $('#placevisited1').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal1').show();

                $('#locName1').prop('required', true);
                $('#locAddress1').prop('required', true);
                $('#locDateFrom1').prop('required', true);
                $('#locDateTo1').prop('required', true);
                $('#locWithOngoingCovid1').prop('required', true);
            }
            else {
                $('#divLocal1').hide();

                $('#locName1').prop('required', false);
                $('#locAddress1').prop('required', false);
                $('#locDateFrom1').prop('required', false);
                $('#locDateTo1').prop('required', false);
                $('#locWithOngoingCovid1').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited2').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal2').show();

                $('#locName2').prop('required', true);
                $('#locAddress2').prop('required', true);
                $('#locDateFrom2').prop('required', true);
                $('#locDateTo2').prop('required', true);
                $('#locWithOngoingCovid2').prop('required', true);
            }
            else {
                $('#divLocal2').hide();

                $('#locName2').prop('required', false);
                $('#locAddress2').prop('required', false);
                $('#locDateFrom2').prop('required', false);
                $('#locDateTo2').prop('required', false);
                $('#locWithOngoingCovid2').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited3').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal3').show();

                $('#locName3').prop('required', true);
                $('#locAddress3').prop('required', true);
                $('#locDateFrom3').prop('required', true);
                $('#locDateTo3').prop('required', true);
                $('#locWithOngoingCovid3').prop('required', true);
            }
            else {
                $('#divLocal3').hide();

                $('#locName3').prop('required', false);
                $('#locAddress3').prop('required', false);
                $('#locDateFrom3').prop('required', false);
                $('#locDateTo3').prop('required', false);
                $('#locWithOngoingCovid3').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited4').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal4').show();

                $('#locName4').prop('required', true);
                $('#locAddress4').prop('required', true);
                $('#locDateFrom4').prop('required', true);
                $('#locDateTo4').prop('required', true);
                $('#locWithOngoingCovid4').prop('required', true);
            }
            else {
                $('#divLocal4').hide();

                $('#locName4').prop('required', false);
                $('#locAddress4').prop('required', false);
                $('#locDateFrom4').prop('required', false);
                $('#locDateTo4').prop('required', false);
                $('#locWithOngoingCovid4').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited5').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal5').show();

                $('#locName5').prop('required', true);
                $('#locAddress5').prop('required', true);
                $('#locDateFrom5').prop('required', true);
                $('#locDateTo5').prop('required', true);
                $('#locWithOngoingCovid5').prop('required', true);
            }
            else {
                $('#divLocal5').hide();

                $('#locName5').prop('required', false);
                $('#locAddress5').prop('required', false);
                $('#locDateFrom5').prop('required', false);
                $('#locDateTo5').prop('required', false);
                $('#locWithOngoingCovid5').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited6').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal6').show();

                $('#locName6').prop('required', true);
                $('#locAddress6').prop('required', true);
                $('#locDateFrom6').prop('required', true);
                $('#locDateTo6').prop('required', true);
                $('#locWithOngoingCovid6').prop('required', true);
            }
            else {
                $('#divLocal6').hide();

                $('#locName6').prop('required', false);
                $('#locAddress6').prop('required', false);
                $('#locDateFrom6').prop('required', false);
                $('#locDateTo6').prop('required', false);
                $('#locWithOngoingCovid6').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited7').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal7').show();

                $('#locName7').prop('required', true);
                $('#locAddress7').prop('required', true);
                $('#locDateFrom7').prop('required', true);
                $('#locDateTo7').prop('required', true);
                $('#locWithOngoingCovid7').prop('required', true);
            }
            else {
                $('#divLocal7').hide();

                $('#locName7').prop('required', false);
                $('#locAddress7').prop('required', false);
                $('#locDateFrom7').prop('required', false);
                $('#locDateTo7').prop('required', false);
                $('#locWithOngoingCovid7').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited8').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal8').show();

                //baguhin kapag kailangan kapag naka-check
                $('#localVessel1').prop('required', false);
                $('#localVesselNo1').prop('required', false);
                $('#localOrigin1').prop('required', false);
                $('#localDateDepart1').prop('required', false);
                $('#localDest1').prop('required', false);
                $('#localDateArrive1').prop('required', false);

                $('#localVessel2').prop('required', false);
                $('#localVesselNo2').prop('required', false);
                $('#localOrigin2').prop('required', false);
                $('#localDateDepart2').prop('required', false);
                $('#localDest2').prop('required', false);
                $('#localDateArrive2').prop('required', false);
            }
            else {
                $('#divLocal8').hide();

                $('#localVessel1').prop('required', false);
                $('#localVesselNo1').prop('required', false);
                $('#localOrigin1').prop('required', false);
                $('#localDateDepart1').prop('required', false);
                $('#localDest1').prop('required', false);
                $('#localDateArrive1').prop('required', false);

                $('#localVessel2').prop('required', false);
                $('#localVesselNo2').prop('required', false);
                $('#localOrigin2').prop('required', false);
                $('#localDateDepart2').prop('required', false);
                $('#localDest2').prop('required', false);
                $('#localDateArrive2').prop('required', false);

                $('localVessel1').val("");
                $('localVesselNo1').val("");
                $('localOrigin1').val("");
                $('localDateDepart1').val("");
                $('localDest1').val("");
                $('localDateArrive1').val("");

                $('localVessel2').val("");
                $('localVesselNo2').val("");
                $('localOrigin2').val("");
                $('localDateDepart2').val("");
                $('localDest2').val("");
                $('localDateArrive2').val("");
            }
        }).trigger('change');

        $('#testType1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'OTHERS' || $(this).val() == 'ANTIGEN') {
                $('#divTypeOthers1').show();
                if($(this).val() == 'ANTIGEN') {
                    $('#ifAntigen1').show();
                }
                else {
                    $('#ifAntigen1').hide();
                }
            }
            else {
                $('#divTypeOthers1').hide();
                $('#testTypeOtherRemarks1').empty();

                $('#ifAntigen1').hide();
            }
        }).trigger('change');
    </script>
@endsection