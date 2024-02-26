@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>View Case</b> - {{$d->name}}</div>
                <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#newPatient">New Patient</button></div>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td scope="row"></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<form action="{{route('qes_new_record', $d->id)}}" method="POST">
    @csrf
    <div class="modal fade" id="newPatient" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Patient</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="lname"><b class="text-danger">*</b>Last Name</label>
                        <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="form-group">
                        <label for="fname"><b class="text-danger">*</b>First Name</label>
                        <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suffix">Suffix <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="3" placeholder="ex: JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="age"><b class="text-danger">*</b>Age</label>
                                <input type="number" class="form-control" name="age" id="age" value="{{old('age')}}" min="0" max="150" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                <select class="form-control" name="gender" id="gender" required>
                                    <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                    <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number')}}" pattern="[0-9]{11}" placeholder="09*********">
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
                    <div class="mb-3">
                        <label for="address_region_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Region</label>
                        <select class="form-control" name="address_region_code" id="address_region_code" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="address_province_code" class="form-label"><span class="text-danger font-weight-bold">*</span>Province</label>
                        <select class="form-control" name="address_province_code" id="address_province_code" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="address_muncity_code" class="form-label"><span class="text-danger font-weight-bold">*</span>City/Municipality</label>
                        <select class="form-control" name="address_muncity_code" id="address_muncity_code" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="address_brgy_text" class="form-label"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                        <select class="form-control" name="address_brgy_text" id="address_brgy_text" required>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_houseno" class="form-label">House No./Lot/Building</label>
                                <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address_street" class="form-label">Street/Subdivision/Purok/Sitio</label>
                                <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" pattern="(^[a-zA-Z0-9 ]+$)+">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="occupation">Occupation</label>
                                <input type="text" class="form-control" name="occupation" id="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="placeof_work_school">Place of Work/School</label>
                                <input type="text" class="form-control" name="placeof_work_school" id="placeof_work_school" value="{{old('placeof_work_school')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="form-group">
                      <label for="has_symptoms">Has Symptoms?</label>
                      <select class="form-control" name="has_symptoms" id="has_symptoms" required>
                        <option value="" disabled {{(is_null(old('has_symptoms'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('has_symptoms') == 'Y') ? 'selected' : ''}}>Yes</option>
                        <option value="N" {{(old('has_symptoms') == 'N') ? 'selected' : ''}}>No</option>
                      </select>
                    </div>
                    <div id="symptoms_div" class="d-none">
                        <div class="form-group">
                          <label for="onset_datetime"><b class="text-danger">*</b>Onset Date and Time</label>
                          <input type="datetime-local" class="form-control" name="onset_datetime" id="onset_datetime">
                        </div>
                        <div class="form-group">
                          <label for="illness_duration"><b class="text-danger">*</b>Illness Duration</label>
                          <input type="number" class="form-control" name="illness_duration" id="illness_duration">
                        </div>
                        <div class="form-group">
                            <label for="diagnosis_date"><b class="text-danger">*</b>Diagnosis Date</label>
                            <input type="date" class="form-control" name="diagnosis_date" id="diagnosis_date">
                        </div>
                        <div class="form-group">
                            <label for="hospitalized">Hospitalized?</label>
                            <select class="form-control" name="hospitalized" id="hospitalized" required>
                              <option value="" disabled {{(is_null(old('hospitalized'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="N" {{(old('hospitalized') == 'N') ? 'selected' : ''}}>No</option>
                              <option value="Y" {{(old('hospitalized') == 'Y') ? 'selected' : ''}}>Yes</option>
                            </select>
                        </div>
                        <div id="hospitalized_div" class="d-none">
                            <div class="form-group">
                                <label for="admission_date"><b class="text-danger">*</b>Date of Admission</label>
                                <input type="date" class="form-control" name="admission_date" id="admission_date">
                            </div>
                            <div class="form-group">
                                <label for="discharge_date"><b class="text-danger">*</b>Date of Discharge</label>
                                <input type="date" class="form-control" name="discharge_date" id="discharge_date">
                            </div>
                            <div class="form-group">
                                <label for="hospital_name"><b class="text-danger">*</b>Name of Hospital</label>
                                <input type="text" class="form-control" name="hospital_name" id="hospital_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="outcome"><b class="text-danger">*</b>Outcome</label>
                            <select class="form-control" name="outcome" id="outcome" required>
                              <option value="ALIVE" {{(old('outcome') == 'N') ? 'selected' : ''}}>ALIVE</option>
                              <option value="DIED" {{(old('outcome') == 'Y') ? 'selected' : ''}}>DIED</option>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="lbm_3xday"><b class="text-danger">*</b>LBM > 3x/day</label>
                            <select class="form-control" name="lbm_3xday" id="lbm_3xday" required>
                              <option value="" disabled {{(is_null(old('lbm_3xday'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('lbm_3xday') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('lbm_3xday') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fever"><b class="text-danger">*</b>Fever</label>
                            <select class="form-control" name="fever" id="fever" required>
                              <option value="" disabled {{(is_null(old('fever'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('fever') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('fever') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nausea"><b class="text-danger">*</b>Nausea</label>
                            <select class="form-control" name="nausea" id="nausea" required>
                              <option value="" disabled {{(is_null(old('nausea'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('nausea') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('nausea') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="vomiting"><b class="text-danger">*</b>Vomiting</label>
                            <select class="form-control" name="vomiting" id="vomiting" required>
                              <option value="" disabled {{(is_null(old('vomiting'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('vomiting') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('vomiting') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bodyweakness"><b class="text-danger">*</b>Body Weakness</label>
                            <select class="form-control" name="bodyweakness" id="bodyweakness" required>
                              <option value="" disabled {{(is_null(old('bodyweakness'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('bodyweakness') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('bodyweakness') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="abdominalcramps"><b class="text-danger">*</b>Abdominal Cramps</label>
                            <select class="form-control" name="abdominalcramps" id="abdominalcramps" required>
                              <option value="" disabled {{(is_null(old('abdominalcramps'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('abdominalcramps') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('abdominalcramps') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rectalpain"><b class="text-danger">*</b>Rectal Pain</label>
                            <select class="form-control" name="rectalpain" id="rectalpain" required>
                              <option value="" disabled {{(is_null(old('rectalpain'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('rectalpain') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('rectalpain') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tenesmus"><b class="text-danger">*</b>Tenesmus <i>(A frequent urge to go to the bathroom without being able to go)</i></label>
                            <select class="form-control" name="tenesmus" id="tenesmus" required>
                              <option value="" disabled {{(is_null(old('tenesmus'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('tenesmus') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('tenesmus') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bloodystool"><b class="text-danger">*</b>Bloody Stool</label>
                            <select class="form-control" name="bloodystool" id="bloodystool" required>
                              <option value="" disabled {{(is_null(old('bloodystool'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('bloodystool') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('bloodystool') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="brownish"><b class="text-danger">*</b>Brownish</label>
                            <select class="form-control" name="brownish" id="brownish" required>
                              <option value="" disabled {{(is_null(old('brownish'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('brownish') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('brownish') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="yellowish"><b class="text-danger">*</b>Yellowish</label>
                            <select class="form-control" name="yellowish" id="yellowish" required>
                              <option value="" disabled {{(is_null(old('brownish'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('yellowish') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('yellowish') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="greenish"><b class="text-danger">*</b>Greenish</label>
                            <select class="form-control" name="greenish" id="greenish" required>
                              <option value="" disabled {{(is_null(old('greenish'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('greenish') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('greenish') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="others"><b class="text-danger">*</b>Others</label>
                            <select class="form-control" name="others" id="others" required>
                              <option value="" disabled {{(is_null(old('others'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('others') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('others') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                        <div id="other_stool_div" class="d-none">
                            <div class="form-group">
                                <label for="others_specify"><b class="text-danger">*</b>Others, specify</label>
                                <input type="text" class="form-control" name="others_specify" id="others_specify" value="{{old('others_specify')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="volumeofstool"><b class="text-danger">*</b>Volume of Stool/Episode</label>
                            <select class="form-control" name="volumeofstool" id="volumeofstool" required>
                              <option value="" disabled {{(is_null(old('volumeofstool'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="SCANTY" {{(old('volumeofstool') == 'SCANTY') ? 'selected' : ''}}>SCANTY</option>
                              <option value="VOLUMINOUS" {{(old('volumeofstool') == 'VOLUMINOUS') ? 'selected' : ''}}>VOLUMINOUS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantify">Quantify</label>
                            <input type="text" class="form-control" name="quantify" id="quantify" value="{{old('quantify')}}" style="text-transform: uppercase;">
                        </div>
                        <hr>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="question1"><b class="text-danger">*</b>1. Before the LBM arises, do you boil your drinking water?</label>
                        <select class="form-control" name="question1" id="question1" required>
                          <option value="" disabled {{(is_null(old('question1'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('question1') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('question1') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question2"><b class="text-danger">*</b>2. Do you use chlorine in your drinking water?</label>
                        <select class="form-control" name="question2" id="question2" required>
                          <option value="" disabled {{(is_null(old('question2'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('question2') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('question2') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question3"><b class="text-danger">*</b>3. Do you wash your hands before eating?</label>
                        <select class="form-control" name="question3" id="question3" required>
                          <option value="" disabled {{(is_null(old('question3'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('question3') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('question3') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question4"><b class="text-danger">*</b>4. Do you wash your hands after using the toilet?</label>
                        <select class="form-control" name="question4" id="question4" required>
                          <option value="" disabled {{(is_null(old('question4'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('question4') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('question4') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question5"><b class="text-danger">*</b>5. Do you have your own toilet?</label>
                        <select class="form-control" name="question5" id="question5" required>
                          <option value="" disabled {{(is_null(old('question5'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('question5') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('question5') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div id="q5_div" class="d-none">
                        <div class="form-group">
                            <label for="question5_source"><b class="text-danger">*</b>If No, what type?</label>
                            <select class="form-control" name="question5_source" id="question5_source">
                              <option value="" disabled {{(is_null(old('question5_source'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="WATER SEALED" {{(old('question5_source') == 'WATER SEALED') ? 'selected' : ''}}>Water Sealed</option>
                              <option value="PIT PRIVY" {{(old('question5_source') == 'PIT PRIVY') ? 'selected' : ''}}>Pit Privy</option>
                              <option value="OTHERS" {{(old('question5_source') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                            </select>
                        </div>
                        <div id="q5_others_div" class="d-none">
                            <div class="form-group">
                                <label for="question5_others"><b class="text-danger">*</b>Specify other toilet</label>
                                <input type="text" class="form-control" name="question5_others" id="question5_others" value="{{old('question5_others')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="question6"><b class="text-danger">*</b>6. Do you use ice?</label>
                        <select class="form-control" name="question6" id="question6" required>
                          <option value="" disabled {{(is_null(old('question6'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('question6') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('question6') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div id="question6_div" class="d-none">
                        <div class="form-group">
                            <label for="question6_where"><b class="text-danger">*</b>If yes, source of the ice</label>
                            <select class="form-control" name="question6_where" id="question6_where">
                              <option value="" disabled {{(is_null(old('question6_where'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="HOMEMADE" {{(old('question6_where') == 'HOMEMADE') ? 'selected' : ''}}>Homemade</option>
                              <option value="BOUGHT" {{(old('question6_where') == 'BOUGHT') ? 'selected' : ''}}>Bought</option>
                              <option value="OTHERS" {{(old('question6_where') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                            </select>
                        </div>
                        <div id="question6_where_div" class="d-none">
                            <div class="form-group">
                                <label for="question6_source"><b class="text-danger">*</b>Where?</label>
                                <input type="text" class="form-control" name="question6_source" id="question6_source" value="{{old('question6_source')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="question7"><b class="text-danger">*</b>7. Source of drinking water</label>
                        <select class="form-control" name="question7" id="question7" required>
                          <option value="" disabled {{(is_null(old('question7'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="WATER SYSTEM" {{(old('question7') == 'WATER SYSTEM') ? 'selected' : ''}}>Water System</option>
                          <option value="DEEP WELL" {{(old('question7') == 'DEEP WELL') ? 'selected' : ''}}>Deep Well</option>
                          <option value="SPRING" {{(old('question7') == 'SPRING') ? 'selected' : ''}}>Spring</option>
                          <option value="BOTTLED" {{(old('question7') == 'BOTTLED') ? 'selected' : ''}}>Bottled</option>
                          <option value="REFILLING STATION" {{(old('question7') == 'REFILLING') ? 'selected' : ''}}>Refilling Station</option>
                          <option value="RAIN WATER" {{(old('question7') == 'RAIN WATER') ? 'selected' : ''}}>Rain Water</option>
                          <option value="OTHERS" {{(old('question7') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                        </select>
                    </div>
                    <div id="question7_div" class="d-none">
                        <div class="form-group">
                            <label for="question7_others"><b class="text-danger">*</b>Specify other source of drinking water</label>
                            <input type="text" class="form-control" name="question7_others" id="question7_others" value="{{old('question7_others')}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="question8"><b class="text-danger">*</b>8. Approximately, how many glasses of water do you consumed per day?</label>
                        <input type="number" class="form-control" name="question8" id="question8" value="{{old('question8')}}" style="text-transform: uppercase;" min="0" max="99" required>
                    </div>
                    <div class="form-group">
                        <label for="question9"><b class="text-danger">*</b>9. Do you attend a party prior to the occurrence of diarrhea?</label>
                        <select class="form-control" name="question9" id="question9" required>
                          <option value="" disabled {{(is_null(old('question9'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('question9') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('question9') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="question10">10. Market place you preferred</label>
                        <input type="text" class="form-control" name="question10" id="question10" value="{{old('question10')}}" style="text-transform: uppercase;">
                    </div>
                    <div class="form-group">
                        <label for="question11"><b class="text-danger">*</b>11. Are you a watcher of patient that was confined in the hospital due to diarrhea?</label>
                        <select class="form-control" name="question11" id="question11" required>
                          <option value="" disabled {{(is_null(old('question11'))) ? 'selected' : ''}}>Choose...</option>
                          <option value="Y" {{(old('question11') == 'Y') ? 'selected' : ''}}>Yes</option>
                          <option value="N" {{(old('question11') == 'N') ? 'selected' : ''}}>No</option>
                        </select>
                    </div>
                    <div id="question11_div" class="d-none">
                        <div class="form-group">
                            <label for="question12"><b class="text-danger">*</b>12. If yes, do you develop diarrhea?</label>
                            <select class="form-control" name="question12" id="question12">
                              <option value="" disabled {{(is_null(old('question12'))) ? 'selected' : ''}}>Choose...</option>
                              <option value="Y" {{(old('question12') == 'Y') ? 'selected' : ''}}>Yes</option>
                              <option value="N" {{(old('question12') == 'N') ? 'selected' : ''}}>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header"><b>AM Snack/s</b></div>
                        <div class="card-body">
                            <div id="duplica_am">
                                <div class="mgClone">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="">AM Snack Name</label>
                                              <input type="text" class="form-control am_snacks_names" name="am_snacks_names[]" id="am_snacks_names" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Time</label>
                                                <input type="time" class="form-control am_snacks_datetime" name="am_snacks_datetime[]" id="am_snacks_datetime">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success btn-block" id="cloneBtn_am">Add AM Snack</button>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header"><b>Lunch</b></div>
                        <div class="card-body">
                            <div id="duplica_lunch">
                                <div class="mgClone">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="">Name of the food</label>
                                          <input type="text" class="form-control lunch_names" name="lunch_names[]" id="lunch_names" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Time</label>
                                            <input type="time" class="form-control lunch_datetime" name="lunch_datetime[]" id="lunch_datetime">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-success btn-block" id="cloneBtn_lunch">Add Lunch</button>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header"><b>PM Snack/s</b></div>
                        <div class="card-body">
                            <div id="duplica_pm">
                                <div class="mgClone">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="">PM Snack Name</label>
                                              <input type="text" class="form-control pm_snacks_names" name="pm_snacks_names[]" id="pm_snacks_names" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Time</label>
                                                <input type="time" class="form-control pm_snacks_datetime" name="pm_snacks_datetime[]" id="pm_snacks_datetime">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success btn-block" id="cloneBtn_pm">Add PM Snack</button>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header"><b>Dinner</b></div>
                        <div class="card-body">
                            <div id="duplica_dinner">
                                <div class="mgClone">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="">Dinner</label>
                                              <input type="text" class="form-control dinner_names" name="dinner_names[]" id="dinner_names" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Time</label>
                                                <input type="time" class="form-control dinner_datetime" name="dinner_datetime[]" id="dinner_datetime">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success btn-block" id="cloneBtn_dinner">Add Dinner</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function(){
        $('#cloneBtn_am').click(function(){
            var clone = $('#duplica_am').clone();
            clone.attr('id', ''); // Remove the ID attribute to prevent duplication
            clone.find('.mgClone').addClass('mt-3');
            clone.find('.am_snacks_names').val(''); // Clear input values
            clone.find('.am_snacks_datetime').val(''); // Clear input values
            clone.append('<button type="button" class="btn btn-danger btn-block deleteBtn">Delete</button>'); // Add delete button
            $('#duplica_am').after(clone);
        });

        $('#cloneBtn_lunch').click(function(){
            var clone = $('#duplica_lunch').clone();
            clone.attr('id', ''); // Remove the ID attribute to prevent duplication
            clone.find('.mgClone').addClass('mt-3');
            clone.find('.lunch_names').val(''); // Clear input values
            clone.find('.lunch_names_datetime').val(''); // Clear input values
            clone.append('<button type="button" class="btn btn-danger btn-block deleteBtn">Delete</button>'); // Add delete button
            $('#duplica_lunch').after(clone);
        });

        $('#cloneBtn_pm').click(function(){
            var clone = $('#duplica_pm').clone();
            clone.attr('id', ''); // Remove the ID attribute to prevent duplication
            clone.find('.mgClone').addClass('mt-3');
            clone.find('.pm_snacks_names').val(''); // Clear input values
            clone.find('.pm_snacks_datetime').val(''); // Clear input values
            clone.append('<button type="button" class="btn btn-danger btn-block deleteBtn">Delete</button>'); // Add delete button
            $('#duplica_pm').after(clone);
        });

        $('#cloneBtn_dinner').click(function(){
            var clone = $('#duplica_dinner').clone();
            clone.attr('id', ''); // Remove the ID attribute to prevent duplication
            clone.find('.mgClone').addClass('mt-3');
            clone.find('.dinner_names').val(''); // Clear input values
            clone.find('.dinner_datetime').val(''); // Clear input values
            clone.append('<button type="button" class="btn btn-danger btn-block deleteBtn">Delete</button>'); // Add delete button
            $('#duplica_dinner').after(clone);
        });

        $(document).on('click', '.deleteBtn', function(){
            $(this).prev('.mgClone').remove(); // Remove the closest card-body element
            $(this).remove();
        });
    });

    //Select2 Init for Address Bar
    $('#address_region_code, #address_province_code, #address_muncity_code, #address_brgy_text').select2({
        theme: 'bootstrap',
    });

    $('#has_symptoms').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#symptoms_div').removeClass('d-none');
        }
        else {
            $('#symptoms_div').addClass('d-none');
        }
    }).trigger('change');

    $('#hospitalized').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#hospitalized_div').removeClass('d-none');
        }
        else {
            $('#hospitalized_div').addClass('d-none');
        }
    }).trigger('change');

    $('#question5').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'N') {
            $('#q5_div').removeClass('d-none');
            $('#question5_souce').prop('required', true);
        }
        else {
            $('#q5_div').addClass('d-none');
            $('#question5_souce').prop('required', false);
        }
    }).trigger('change');

    $('#question5_souce').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'OTHERS') {
            $('#q5_others_div').removeClass('d-none');
            $('#question5_others').prop('required', true);
        }
        else {
            $('#q5_others_div').addClass('d-none');
            $('#question5_others').prop('required', false);
        }
    }).trigger('change');

    $('#question6').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#question6_div').removeClass('d-none');
            $('#question6_where').prop('required', true);
        }
        else {
            $('#question6_div').addClass('d-none');
            $('#question6_where').prop('required', false);
        }
    }).trigger('change');

    $('#question6_where').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'OTHERS') {
            $('#question6_where_div').removeClass('d-none');
            $('#question6_source').prop('required', true);
        }
        else {
            $('#question6_where_div').addClass('d-none');
            $('#question6_source').prop('required', false);
        }
    }).trigger('change');

    $('#question7').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'OTHERS') {
            $('#question7_div').removeClass('d-none');
            $('#question7_others').prop('required', true);
        }
        else {
            $('#question7_div').addClass('d-none');
            $('#question7_others').prop('required', false);
        }
    }).trigger('change');

    $('#question11').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#question11_div').removeClass('d-none');
            $('#question12').prop('required', true);
        }
        else {
            $('#question11_div').addClass('d-none');
            $('#question12').prop('required', false);
        }
    }).trigger('change');

    $(document).ready(function () {
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
    });
</script>
@endsection