@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{$route}}" method="POST">
      @csrf
        <div class="card">
            <div class="card-header">
              <div><b>New CVD/NCD Risk Assessment Form</b></div>
              @if(!is_null($f))
              <div>BHS: {{$f->name}}</div>
              @endif
            </div>
            <div class="card-body">
              @if(session('msg'))
              <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                  {{session('msg')}}
              </div>
              @endif
              @if(!is_null($f))
              <input type="hidden" name="facility_code" value="{{$f->sys_code1}}" required>
              @endif
              <div class="alert alert-info" role="alert">
                <b class="text-danger">Note:</b> All fields marked with an asterisk (<b class="text-danger">*</b>) are required to be filled out. You agree to accomplish the Risk Assessment Form with your true, correct, and complete data. For ticking the field, checking means Yes or You Agree to the question.
              </div>
              @if(!Auth::guest())
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="assessment_date"><b class="text-danger">*</b>Date of Assessment/Consultation</label>
                    <input type="date" class="form-control" name="assessment_date" id="assessment_date" value="{{old('assessment_date', date('Y-m-d'))}}" min="1900-01-01" max="{{date('Y-m-d')}}" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="assessed_by"><b class="text-danger">*</b>Assessed By</label>
                    <input type="text" class="form-control" name="assessed_by" id="assessed_by" value="{{old('assessed_by')}}" style="text-transform: uppercase;" required>
                  </div>
                </div>
              </div>
              <hr>
              @endif
                <div class="row">
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="lname"><b class="text-danger">*</b>Last Name</label>
                          <input type="text" class="form-control" name="lname" id="lname" value="{{request()->input('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required readonly tabindex="-1">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="fname"><b class="text-danger">*</b>First Name</label>
                          <input type="text" class="form-control" name="fname" id="fname" value="{{request()->input('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required readonly tabindex="-1">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="mname">Middle Name</label>
                          <input type="text" class="form-control" name="mname" id="mname" value="{{request()->input('mname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" readonly tabindex="-1">
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                          <label for="suffix">Name Extension</label>
                          <input type="text" class="form-control" name="suffix" id="suffix" value="{{request()->input('suffix')}}" minlength="2" maxlength="3" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" readonly tabindex="-1">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
                          <input type="date" class="form-control" name="bdate" id="bdate" value="{{request()->input('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required readonly tabindex="-1">
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                        <label for="sex"><b class="text-danger">*</b>Gender</label>
                        <input type="text" class="form-control" name="sex" id="sex" value="{{request()->input('sex')}}" required readonly>
                      </div>

                      @if(request()->input('sex') == 'F')
                      <div class="form-group">
                        <label for="is_pregnant"><b class="text-danger">*</b>Pregnant</label>
                        <select class="form-control" name="is_pregnant" id="is_pregnant" required>
                          <option value="" disabled {{(is_null(old('is_pregnant'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('is_pregnant') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                          <option value="N" {{(old('is_pregnant') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                        </select>
                      </div>
                      @endif
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="contact_number"><b class="text-danger">*</b>Contact Number</label>
                          <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********" required>
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group" id="brgyDiv">
                          <label for="brgy_id" class="form-label"><b class="text-danger">*</b>Barangay</label>
                          <select class="form-control" name="brgy_id" id="brgy_id" required>
                              <option value="" disabled {{(is_null(old('brgy_id'))) ? 'selected' : ''}}>Choose...</option>
                              @foreach ($brgy_list as $b)
                                  <option value="{{$b->id}}">{{$b->name}}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="street_purok" class="form-label"><b class="text-danger">*</b>House/Lot No./Street/Purok/Subdivision</label>
                          <input type="text" class="form-control" id="street_purok" name="street_purok" style="text-transform: uppercase;" value="{{old('street_purok')}}" placeholder="ex. S1 B2 L3 PHASE 4 SUBDIVISION HOMES" required>
                      </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="occupation" class="form-label">Occupation (Trabaho)</label>
                      <input type="text" class="form-control" id="occupation" name="occupation" style="text-transform: uppercase;" value="{{old('occupation')}}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="educational_attainment" class="form-label"><b class="text-danger">*</b>Educational Attainment (Antas ng Edukasyon)</label>
                      <select class="form-control" name="educational_attainment" id="educational_attainment" required>
                        <option value="" disabled {{(is_null(old('educational_attainment'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="no_formal_education" {{(old('educational_attainment') == 'no_formal_education')}}>No Formal Education</option>
                        <option value="elementary_undergraduate" {{(old('educational_attainment') == 'elementary_undergraduate')}}>Elementary Undergraduate</option>
                        <option value="elementary_graduate" {{(old('educational_attainment') == 'elementary_graduate')}}>Elementary Graduate</option>
                        <option value="highschool_undergraduate" {{(old('educational_attainment') == 'highschool_undergraduate')}}>High School Undergraduate</option>
                        <option value="highschool_graduate" {{(old('educational_attainment') == 'highschool_graduate')}}>High School Graduate</option>
                        <option value="shs_undergraduate" {{(old('educational_attainment') == 'shs_undergraduate')}}>Senior High School Undergraduate</option>
                        <option value="shs_graduate" {{(old('educational_attainment') == 'shs_graduate')}}>Senior High School Graduate</option>
                        <option value="vocational_course" {{(old('educational_attainment') == 'vocational_course')}}>Vocational Course</option>
                        <option value="college_undergraduate" {{(old('educational_attainment') == 'college_undergraduate')}}>College Undergraduate</option>
                        <option value="college_graduate" {{(old('educational_attainment') == 'college_graduate')}}>College Graduate</option>
                        <option value="postgraduate" {{(old('educational_attainment') == 'postgraduate')}}>Postgraduate (Master's/Doctorate)</option>
                      </select>
                    </div>
                  </div>
                </div>
                
                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-6">
                        <div><label for="height"><b class="text-danger">*</b>Height/Tangkad (cm)</label></div>
                        <div class="input-group mb-3">
                          <input type="number" step="0.01" class="form-control" name="height" id="height" min="1" max="600" required>
                          <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#heightConverter">Convert feet to cm</button>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="weight"><b class="text-danger">*</b>Weight/Timbang (kg)</label>
                          <input type="number" class="form-control" name="weight" id="weight" min="1" max="500" step="0.1" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="systolic"><b class="text-danger">*</b>BP (Systolic)</label>
                          <input type="number" class="form-control" name="systolic" id="systolic" value="{{old('systolic')}}" max="300" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="diastolic"><b class="text-danger">*</b>BP (Diastolic)</label>
                          <input type="number" class="form-control" name="diastolic" id="diastolic" value="{{old('diastolic')}}" max="200" required>
                        </div>
                      </div>
                    </div>
                    <div class="alert alert-info" role="alert">
                      <b>Note:</b> Raised BP/Hypertension is automatically determined by the system based on the Blood Pressure input.
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><b>Family History</b></div>
                            <div class="card-body">
                                <h6>Do you have 1st Degree Relative with (Ikaw ba ay may magulang o kapatid na may sumusunod):</h6>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" name="fh_hypertension" id="fh_hypertension" value="Y">
                                      Hypertension
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" name="fh_stroke" id="fh_stroke" value="Y">
                                      Stroke
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" name="fh_heartattack" id="fh_heartattack" value="Y">
                                      Heart Attack
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" name="fh_diabetes" id="fh_diabetes" value="Y">
                                      Diabetes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" name="fh_asthma" id="fh_asthma" value="Y">
                                      Asthma
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" name="fh_cancer" id="fh_cancer" value="Y">
                                      Cancer
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                      <input type="checkbox" class="form-check-input" name="fh_kidneydisease" id="fh_kidneydisease" value="Y">
                                      Kidney Disease
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                          <div class="card-header"><b>Weight Classification</b></div>
                          <div class="card-body">
                            <div class="alert alert-info" role="alert">
                              <b>Note:</b> Weight Classification (Normal/Obese/Overweight) and BMI is automatically determined by the system based on the Height and Weight input.
                            </div>
                            <div class="form-group">
                              <label for="waist_cm"><b class="text-danger">*</b>Waist Circumference / Sukat ng Bewang (cm)</label>
                              <input type="number" class="form-control" name="waist_cm" id="waist_cm" step=".1" required>
                            </div>                            
                          </div>
                        </div>
                        <div class="card mt-3">
                          <div class="card-header"><b>Presence or absence of Diabetes</b></div>
                          <div class="card-body">
                            <div class="form-group">
                              <label for="diabetes"><b class="text-danger">*</b>Are you diagnosed as having diabetes?</label>
                              <select class="form-control" name="diabetes" id="diabetes" required>
                                <option value="" disabled {{(is_null(old('diabetes'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('diabetes') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                <option value="N" {{(old('diabetes') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                <option value="U" {{(old('diabetes') == 'U') ? 'selected' : ''}}>Do not know/Hindi alam</option>
                              </select>
                            </div>
                            <div id="medication_div" class="d-none">
                              <div class="form-group">
                                <label for="diabetes_medication"><b class="text-danger">*</b>Medications</label>
                                <select class="form-control" name="diabetes_medication" id="diabetes_medication">
                                  <option value="" disabled {{(is_null(old('diabetes_medication'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('diabetes_medication') == 'Y') ? 'selected' : ''}}>With Medications/Kasalukuyang Nainom ng Gamot</option>
                                  <option value="N" {{(old('diabetes_medication') == 'N') ? 'selected' : ''}}>Without Medications/Wala pang gamutan</option>
                                </select>
                              </div>
                            </div>
                            <div id="diabetes_div" class="d-none">
                              <hr>
                              <h6><b>Ikaw ay nakakaranas ng mga sumusunod na sintomas:</b></h6>
                              <div class="form-group">
                                <label for="polyphagia"><b class="text-danger">*</b>Polyphagia (Laging gutom)</label>
                                <select class="form-control" name="polyphagia" id="polyphagia">
                                  <option value="" disabled {{(is_null(old('polyphagia'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('polyphagia') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                  <option value="N" {{(old('polyphagia') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="polydipsia"><b class="text-danger">*</b>Polydipsia (Laging uhaw)</label>
                                <select class="form-control" name="polydipsia" id="polydipsia">
                                  <option value="" disabled {{(is_null(old('polydipsia'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('polydipsia') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                  <option value="N" {{(old('polydipsia') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="polyuria"><b class="text-danger">*</b>Polyuria (Laging umiihi)</label>
                                <select class="form-control" name="polyuria" id="polyuria">
                                  <option value="" disabled {{(is_null(old('polyuria'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('polyuria') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                  <option value="N" {{(old('polyuria') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        @if(request()->input('sex') == 'F')
                        <div class="card mt-3">
                          <div class="card-header"><b>Breast Examination (For Women)</b></div>
                          <div class="card-body">
                            <div class="form-group">
                              <label for="female_hasbreastmass"><b class="text-danger">*</b>May nakakakapang bukol sa suso/dibdib?</label>
                              <select class="form-control" name="female_hasbreastmass" id="female_hasbreastmass" required>
                                <option value="" disabled {{(is_null(old('female_hasbreastmass'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('female_hasbreastmass') == 'Y') ? 'selected' : ''}}>Yes/Meron</option>
                                <option value="N" {{(old('female_hasbreastmass') == 'N') ? 'selected' : ''}}>No/Wala</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        @endif
                        @if($age_check >= 60)
                        <div class="card mt-3">
                          <div class="card-header"><b>Visual Acuity Screening</b></div>
                          <div class="card-body">
                            <div class="form-group">
                              <label for="senior_blurryeyes"><b class="text-danger">*</b>May panlalabo sa mata?</label>
                              <select class="form-control" name="senior_blurryeyes" id="senior_blurryeyes" required>
                                <option value="" disabled {{(is_null(old('senior_blurryeyes'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('female_hasbreastmass') == 'Y') ? 'selected' : ''}}>Yes/Meron</option>
                                <option value="N" {{(old('female_hasbreastmass') == 'N') ? 'selected' : ''}}>No/Wala</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="senior_diagnosedeyedisease"><b class="text-danger">*</b>Ikaw ba ay na-diagnose na may Sakit sa Mata (Eye Disease)?</label>
                              <select class="form-control" name="senior_diagnosedeyedisease" id="senior_diagnosedeyedisease" required>
                                <option value="" disabled {{(is_null(old('senior_diagnosedeyedisease'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('female_hasbreastmass') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                <option value="N" {{(old('female_hasbreastmass') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        @endif
                        <hr>
                        <div class="form-group">
                          <label for="raised_bloodglucose"><b class="text-danger">*</b>Raised Blood Glucose</label>
                          <select class="form-control" name="raised_bloodglucose" id="raised_bloodglucose" required>
                            <option value="" disabled {{(is_null(old('raised_bloodglucose'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('raised_bloodglucose') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                            <option value="N" {{(old('raised_bloodglucose') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                          </select>
                        </div>
                        <div id="fbsrbs_div" class="d-none">
                          <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="fbs_rbs"><b class="text-danger">*</b>FBS/RBS</label>
                                  <input type="text" class="form-control" name="fbs_rbs" id="fbs_rbs" value="{{old('fbs_rbs')}}">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="fbs_rbs_date"><b class="text-danger">*</b>Date Taken</label>
                                  <input type="date" class="form-control" name="fbs_rbs_date" id="fbs_rbs_date" value="{{old('fbs_rbs_date')}}" max="{{date('Y-m-d')}}">
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="raised_bloodlipids"><b class="text-danger">*</b>Raised Blood Lipids</label>
                          <select class="form-control" name="raised_bloodlipids" id="raised_bloodlipids" required>
                            <option value="" disabled {{(is_null(old('raised_bloodlipids'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('raised_bloodlipids') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                            <option value="N" {{(old('raised_bloodlipids') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                          </select>
                        </div>
                        <div id="cholesterol_div" class="d-none">
                          <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="cholesterol"><b class="text-danger">*</b>Cholesterol</label>
                                  <input type="text" class="form-control" name="cholesterol" id="cholesterol" value="{{old('cholesterol')}}">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="cholesterol_date"><b class="text-danger">*</b>Date Taken</label>
                                  <input type="date" class="form-control" name="cholesterol_date" id="cholesterol_date" value="{{old('cholesterol_date')}}" max="{{date('Y-m-d')}}">
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="urine_protein"><b class="text-danger">*</b>Presence of Urine Protein</label>
                          <select class="form-control" name="urine_protein" id="urine_protein" required>
                            <option value="" disabled {{(is_null(old('urine_protein'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('urine_protein') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                            <option value="N" {{(old('urine_protein') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                          </select>
                        </div>
                        <div id="protein_div" class="d-none">
                          <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="protein"><b class="text-danger">*</b>Urine Protein</label>
                                  <input type="text" class="form-control" name="protein" id="protein" value="{{old('protein')}}">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="protein_date"><b class="text-danger">*</b>Date Taken</label>
                                  <input type="date" class="form-control" name="protein_date" id="protein_date" value="{{old('protein_date')}}" max="{{date('Y-m-d')}}">
                                </div>
                              </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="urine_ketones"><b class="text-danger">*</b>Presence of Urine Ketones (for newly diagnosed DM)</label>
                          <select class="form-control" name="urine_ketones" id="urine_ketones" required>
                            <option value="" disabled {{(is_null(old('urine_ketones'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('urine_ketones') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                            <option value="N" {{(old('urine_ketones') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                          </select>
                        </div>
                        <div id="ketones_div" class="d-none">
                          <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="ketones"><b class="text-danger">*</b>Urine Ketones</label>
                                  <input type="text" class="form-control" name="ketones" id="ketones" value="{{old('ketones')}}">
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label for="ketones_date"><b class="text-danger">*</b>Date Taken</label>
                                  <input type="date" class="form-control" name="ketones_date" id="ketones_date" value="{{old('ketones_date')}}" max="{{date('Y-m-d')}}">
                                </div>
                              </div>
                          </div>
                        </div>

                        @if(!Auth::guest())
                        <div class="form-group">
                          <label for="finding">Finding/s</label>
                          <textarea class="form-control" name="finding" id="finding" rows="3">{{old('finding')}}</textarea>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                      <div class="card">
                        <div class="card-header"><b>Smoking</b></div>
                        <div class="card-body">
                          <div class="form-group">
                            <label for="smoking"><b class="text-danger">*</b>Smoking (Tobacco/Cigarette/Vape)</label>
                            <select class="form-control" name="smoking" id="smoking" required>
                              <option value="" disabled {{(is_null(old('smoking'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="NEVER">Never smoked / Hindi talaga naninigarilyo</option>
                              <option value="STOPPED<1Y">Recently Stopped (Less than 1 year ago) / Huminto na nung nakaraang taon</option>
                              <option value="STOPPED>1Y">Stopped 2 or more years ago / Huminto na dalawang taon na nakakalipas</option>
                              <option value="CURRENT">Current Smoker / Kasalukuyang Naninigarilyo</option>
                              <option value="MASSIVE">Massive Smoker / Malakas Manigarilyo</option>
                            </select>
                          </div>
                        </div>
                      </div>
                        <div class="card mt-3">
                          <div class="card-header"><b>Alcohol Intake</b></div>
                          <div class="card-body">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="alcohol_intake" id="alcohol_intake" value="Y">
                                Alcohol Intake? / Umiinom ng Alak, Nakakalasing na Inumin
                              </label>
                            </div>
                            <div id="excessAlcohol_div" class="d-none">
                              <div class="form-check mt-3">
                                <label class="form-check-label">
                                  <input type="checkbox" class="form-check-input" name="excessive_alcohol_intake" id="excessive_alcohol_intake" value="Y">
                                  <div>Excessive Alcohol Intake (Had 5 drinks in one occasion in the past month)</div>
                                  <div>Malakas Uminom ng mga Alak (Nakakainom ng 5 Alak sa isang okasyon lamang ng nakaraang buwan)</div>
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="card mt-3">
                          <div class="card-header"><b>High Fat / High Salt Food Intake</b></div>
                          <div class="card-body">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="high_fatsalt_intake" id="high_fatsalt_intake" value="Y">
                                Eats processed/fast foods (e.g. instant noodles, hamburgers, fries, fried chicken skin, etc.) and ihaw-ihaw (e.g. isaw, adidas, etc.) weekly.
                              </label>
                            </div>
                          </div>
                        </div>

                        <div class="card mt-3">
                          <div class="card-header"><b>Dietary Fiber Intake</b></div>
                          <div class="card-body">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="vegetable_serving" id="vegetable_serving" value="Y">
                                Eats 3 servings of vegetables daily (Nakakain ng mga gulay 3 beses sa araw-araw)
                              </label>
                            </div>

                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="fruits_serving" id="fruits_serving" value="Y">
                                Eats 2-3 servings of fruits daily (Nakakakain ng mga prutas dalawa o tatlong beses sa araw-araw)
                              </label>
                            </div>
                          </div>
                        </div>

                        <div class="card mt-3">
                          <div class="card-header"><b>Physical Activity</b></div>
                          <div class="card-body">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="physical_activity" id="physical_activity" value="Y">
                                Does at least 2 and a half hours of moderate intensity physical activity PER WEEK (Nage-ehersisyo na umaabot sa dalawa't kalahating oras KADA LINGGO)
                              </label>
                            </div>
                          </div>
                        </div>

                        <div class="card mt-3">
                          <div class="card-header"><b>Questionnaire to Determine Probable Angina, Heart Attack, Stroke or Transient Ischemic Attack</b></div>
                          <div class="card-body">
                            <div class="form-group">
                              <label for="question1">
                                <div>1. Have you had any pain or discomfort or any pressure or heaviness in your chest?</div>
                                <div>Nakaramdam ka ba ng pananakit o kabigatan sa iyong dibdib?</div>
                              </label>
                              <select class="form-control" name="question1" id="question1" required>
                                <option value="" disabled {{(is_null(old('question1'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('question1') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                <option value="N" {{(old('question1') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                              </select>
                            </div>
                            <div id="q2_div" class="d-none">
                              <div class="form-group">
                                <label for="question2">
                                  <div>2. Do you get the pain in the center of the chest or left chest or left arm?</div>
                                  <div>Ang sakit ba ay nasa gitna ng dibdib, sa kaliwang bahagi ng dibdib o sa kaliwang braso?</div>
                                </label>
                                <select class="form-control" name="question2" id="question2">
                                  <option value="" disabled {{(is_null(old('question2'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('question2') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                  <option value="N" {{(old('question2') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                </select>
                              </div>
                            </div>
                            <div id="addtl_questions" class="d-none">
                              <div class="form-group">
                                <label for="question3">
                                  <div>3. Do you get it when you walk uphill or hurry?</div>
                                  <div>Nararamdaman mo ba ito kung ikaw ay nagmamadali o naglalakad nang mabilis o paakyat?</div>
                                </label>
                                <select class="form-control" name="question3" id="question3">
                                  <option value="" disabled {{(is_null(old('question3'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('question3') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                  <option value="N" {{(old('question3') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="question4">
                                  <div>4. Do you slowdown if you get the pain while walking?</div>
                                  <div>Tumitigil ka ba sa paglalakad kapag sumakit ang iyong dibdib?</div>
                                </label>
                                <select class="form-control" name="question4" id="question4">
                                  <option value="" disabled {{(is_null(old('question4'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('question4') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                  <option value="N" {{(old('question4') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="question5">
                                  <div>5. Does the pain go away if you stand still or if you take tablet under tongue?</div>
                                  <div>Nawawala ba ang sakit kapag ikaw ay di kumilos o kapag naglagay ka ng gamot sa ilalim ng iyong dila?</div>
                                </label>
                                <select class="form-control" name="question5" id="question5">
                                  <option value="" disabled {{(is_null(old('question5'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('question5') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                  <option value="N" {{(old('question5') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="question6">
                                  <div>6. Does the pain go away in less than 10 minutes?</div>
                                  <div>Nawawala ba ang sakit sa loob ng 10 minuto?</div>
                                </label>
                                <select class="form-control" name="question6" id="question6">
                                  <option value="" disabled {{(is_null(old('question6'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('question6') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                  <option value="N" {{(old('question6') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="question7">
                                  <div>7. Have you ever had a severe chest pain across the front of your chest lasting for half an hour or more?</div>
                                  <div>Nakakaramdam ka na ba ng pananakit ng dibdib na tumatagal ng kalahating oras o higit pa?</div>
                                </label>
                                <select class="form-control" name="question7" id="question7">
                                  <option value="" disabled {{(is_null(old('question7'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="Y" {{(old('question7') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                  <option value="N" {{(old('question7') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                                </select>
                              </div>
                            </div>
                            <div class="form-group">
                              <hr>
                              <label for="question8">
                                <div>8. Have you ever had any of the following: difficulty in talking, weakness of arm and/or leg on one side of the body or numbness on one side of the body?</div>
                                <div>Nakaramdam ka na ba ng mga sumusunod: hirap sa pagsasalita, panghihina ng braso at/o ng binti o pamamanhid sa kalahating bahagi ng katawan.</div>
                              </label>
                              <select class="form-control" name="question8" id="question8" required>
                                <option value="" disabled {{(is_null(old('question8'))) ? 'selected' : ''}}>Choose...</option>
                                <option value="Y" {{(old('question8') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                                <option value="N" {{(old('question8') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                              </select>
                            </div>
                          </div>
                        </div>

                        <div class="form-group mt-3">
                          <label for="management"><b class="text-danger">*</b>Management</label>
                          <select class="form-control" name="management" id="management" required>
                            <option value="" disabled {{(is_null(old('management'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="STYLE MODIFICATION" {{(old('management') == 'STYLE MODIFICATION') ? 'selected' : ''}}>Style Modification</option>
                            <option value="MEDICATIONS" {{(old('management') == 'MEDICATIONS') ? 'selected' : ''}}>Medications</option>
                            <option value="N/A" {{(old('management') == 'N/A') ? 'selected' : ''}}>None</option>
                          </select>
                        </div>
                        @if(!Auth::guest())
                        <div class="form-group">
                          <label for="date_followup">Date of Follow-up</label>
                          <input type="text" class="form-control" name="date_followup" id="date_followup" style="text-transform: uppercase">
                        </div>
                        <div class="form-group">
                          <label for="meds">Meds</label>
                          <textarea class="form-control" name="meds" id="meds" rows="3">{{old('meds')}}</textarea>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success btn-block" id="submitBtn">Submit (CTRL + S)</button>
            </div>
        </div>
    </form>
    <div class="text-center mt-3">
      <small>Developed and Maintained by CJH for General Trias City Health Office.</small>
    </div>
</div>

<div class="modal fade" id="heightConverter" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Foot to Centimeter Converter</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="feet"><b class="text-danger">*</b>Feet</label>
                <input type="number" class="form-control" name="feet" id="feet">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="inches"><b class="text-danger">*</b>Inches</label>
                <input type="number" class="form-control" name="inches" id="inches">
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" name="convertBtn" id="convertBtn" class="btn btn-success btn-block">Convert</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).bind('keydown', function(e) {
      if(e.ctrlKey && (e.which == 83)) {
          e.preventDefault();
          $('#submitBtn').trigger('click');
          $('#submitBtn').prop('disabled', true);
          setTimeout(function() {
              $('#submitBtn').prop('disabled', false);
          }, 2000);
          return false;
      }
  });

  $(document).ready(function () {
      $('#convertBtn').click(function () {
          // Get values from input fields
          const feet = parseInt($('#feet').val());
          const inches = parseInt($('#inches').val());

          // Validate input
          if (isNaN(feet) || isNaN(inches) || feet < 0 || inches < 0) {
              alert('Please enter valid values for feet and inches.');
              return;
          }

          // Convert height to centimeters
          const totalInches = (feet * 12) + inches;
          const cm = totalInches * 2.54;

          // Display result
          $('#height').val(cm.toFixed(2));

          $('#heightConverter').modal('toggle');
      });
  });
</script>

<script>
  
  
  $('#question1').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'N' || $(this).val() == '') {
      $('#addtl_questions').addClass('d-none');
      $('#q2_div').addClass('d-none');
    }
    else {
      if($('#question2') == 'N' || $(this).val() == '') {
        $('#addtl_questions').addClass('d-none');
      }
      $('#q2_div').removeClass('d-none');
    }
  });

  $('#question2').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'N' || $(this).val() == '') {
      $('#addtl_questions').addClass('d-none');
      $('#question3').prop('required', false);
      $('#question4').prop('required', false);
      $('#question5').prop('required', false);
      $('#question6').prop('required', false);
      $('#question7').prop('required', false);
    }
    else {
      $('#addtl_questions').removeClass('d-none');
      $('#question3').prop('required', true);
      $('#question4').prop('required', true);
      $('#question5').prop('required', true);
      $('#question6').prop('required', true);
      $('#question7').prop('required', true);
    }
  });

  $('#raised_bloodglucose').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'Y') {
      $('#fbsrbs_div').removeClass('d-none');
      $('#fbs_rbs').prop('required', true);
      $('#fbs_rbs_date').prop('required', true);
    }
    else {
      $('#fbsrbs_div').addClass('d-none');
      $('#fbs_rbs').prop('required', false);
      $('#fbs_rbs_date').prop('required', false);
    }
  }).trigger('change');

  $('#raised_bloodlipids').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'Y') {
      $('#cholesterol_div').removeClass('d-none');
      $('#cholesterol').prop('required', true);
      $('#cholesterol_date').prop('required', true);
    }
    else {
      $('#cholesterol_div').addClass('d-none');
      $('#cholesterol').prop('required', false);
      $('#cholesterol_date').prop('required', false);
    }
  }).trigger('change');

  $('#urine_protein').change(function (e) { 
    e.preventDefault();
    if($(this).val() == 'Y') {
      $('#protein_div').removeClass('d-none');
      $('#protein').prop('required', true);
      $('#protein_date').prop('required', true);
    }
    else {
      $('#protein_div').addClass('d-none');
      $('#protein').prop('required', false);
      $('#protein_date').prop('required', false);
    }
  }).trigger('change');

  $('#urine_ketones').change(function (e) {
    e.preventDefault();
    if($(this).val() == 'Y') {
      $('#ketones_div').removeClass('d-none');
      $('#ketones').prop('required', true);
      $('#ketones_date').prop('required', true);
    }
    else {
      $('#ketones_div').addClass('d-none');
      $('#ketones').prop('required', false);
      $('#ketones_date').prop('required', false);
    }
  }).trigger('change');

  $('#diabetes').change(function (e) { 
    e.preventDefault();

    if($(this).val() == 'Y') {
      $('#medication_div').removeClass('d-none');
      $('#diabetes_medication').prop('required', true);

      $('#diabetes_div').addClass('d-none');
      $('#polyphagia').prop('required', false);
      $('#polydipsia').prop('required', false);
      $('#polyuria').prop('required', false);
    }
    else if($(this).val() === null) {
      $('#medication_div').addClass('d-none');
      $('#diabetes_medication').prop('required', false);

      $('#diabetes_div').addClass('d-none');
      $('#polyphagia').prop('required', false);
      $('#polydipsia').prop('required', false);
      $('#polyuria').prop('required', false);
    }
    else { //No and Unknown
      $('#medication_div').addClass('d-none');
      $('#diabetes_medication').prop('required', false);

      $('#diabetes_div').removeClass('d-none');
      $('#polyphagia').prop('required', true);
      $('#polydipsia').prop('required', true);
      $('#polyuria').prop('required', true);
    }
  }).trigger('change');

  $('#alcohol_intake').change(function(){
      if ($(this).is(':checked')) {
        $('#excessAlcohol_div').removeClass('d-none');
      } else {
        $('#excessAlcohol_div').addClass('d-none');
      }
  }).trigger('change');
</script>
@endsection