@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('facility.update', ['id' => $data->id])}}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-header font-weight-bold">View Patient Information</div>
                <div class="card-body">
                    <div class="card mb-3">
                        <div class="card-header"><i class="fa fa-user mr-2" aria-hidden="true"></i>Personal Details</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="lname">Patient ID (#)</label>
                                <input type="text" class="form-control" value="{{$data->records->id}}" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" class="form-control" value="{{$data->records->lname}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fname">First Name (and Suffix)</label>
                                        <input type="text" class="form-control" value="{{$data->records->fname}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" class="form-control" value="{{(!is_null($data->records->mname)) ? $data->records->mname : 'N/A'}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bdate">Birthdate</label>
                                        <input type="date" class="form-control" value="{{$data->records->bdate}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender">Age / Gender</label>
                                        <input type="text" class="form-control" value="{{$data->records->getAge().' / '.$data->records->gender}}" readonly>
                                    </div>
                                    @if($data->records->gender == 'FEMALE')
                                    <div class="form-group">
                                        <label for="isPregnant">Is the Patient Pregnant?</label>
                                        <input type="text" class="form-control" value="{{($data->records->isPregnant == 1) ? 'YES' : 'NO'}}" readonly>
                                    </div>
                                    @if($data->records->isPregnant == 1)
                                    <div class="form-group">
                                        <label for="lmp">Last Menstrual Period (LMP)</label>
                                        <input type="text" class="form-control" value="{{($data->records->isPregnant == 1) ? date('m/d/Y', strtotime($data->PregnantLMP)) : 'N/A'}}" readonly>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cs">Civil Status</label>
                                        <input type="text" class="form-control" value="{{$data->records->cs}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nationality">Nationality</label>
                                        <input type="text" class="form-control" value="{{$data->records->nationality}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address_province">Province</label>
                                        <input type="text" class="form-control" value="{{$data->records->address_province}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address_city">City</label>
                                        <input type="text" class="form-control" value="{{$data->records->address_city}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address_brgy">Barangay</label>
                                        <input type="text" class="form-control" value="{{$data->records->address_brgy}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_houseno">House No./Lot/Building</label>
                                        <input type="text" class="form-control" value="{{$data->records->address_houseno}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_street">Street/Purok/Sitio</label>
                                        <input type="text" class="form-control" value="{{$data->records->address_street}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header"><i class="fas fa-syringe mr-2"></i>COVID-19 Vaccination Information</div>
                        <div class="card-body">
                            @if(!is_null($data->records->vaccinationDate1))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Name of Vaccine</label>
                                        <input type="text" class="form-control" name="" id="" value="{{$data->records->vaccinationName1}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="">1.) First Dose Date</label>
                                      <input type="date" class="form-control" name="" id="" value="{{$data->records->vaccinationDate1}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Vaccination Center/Facility</label>
                                        <input type="text" class="form-control" name="" id="" value="{{($data->records->vaccinationFacility1) ? $data->records->vaccinationFacility1 : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Region of Health Facility</label>
                                        <input type="text" class="form-control" name="" id="" value="{{($data->records->vaccinationRegion1) ? $data->records->vaccinationRegion1 : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Adverse Event/s</label>
                                        <input type="text" class="form-control" name="" id="" value="{{($data->records->haveAdverseEvents1 == 1) ? 'YES' : 'NO'}}" readonly>
                                    </div>
                                </div>
                            </div>
                            @if(!is_null($data->records->vaccinationDate2))
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="">2.) Second Dose Date</label>
                                      <input type="date" class="form-control" name="" id="" value="{{$data->records->vaccinationDate2}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Vaccination Center/Facility</label>
                                        <input type="text" class="form-control" name="" id="" value="{{($data->records->vaccinationFacility2) ? $data->records->vaccinationFacility2 : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Region of Health Facility</label>
                                        <input type="text" class="form-control" name="" id="" value="{{($data->records->vaccinationRegion2) ? $data->records->vaccinationRegion2 : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Adverse Event/s</label>
                                        <input type="text" class="form-control" name="" id="" value="{{($data->records->haveAdverseEvents2 == 1) ? 'YES' : 'NO'}}" readonly>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @else
                            <p class="text-center">Not Yet Vaccinated</p>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="symptoms"><span class="text-danger font-weight-bold">*</span>Signs and Symptoms <small>(Select all that apply)</small></label>
                      <select class="form-control" name="symptoms[]" id="symptoms" multiple>
                        <option value="Asymptomatic" {{(in_array('Asymptomatic', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Asymptomatic</option>
                        <option value="Fever" {{(in_array('Fever', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Fever</option>
                        <option value="Cough" {{(in_array('Cough', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Cough</option>
                        <option value="General Weakness" {{(in_array('General Weakness', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>General Weakness</option>
                        <option value="Fatigue" {{(in_array('Fatigue', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Fatigue</option>
                        <option value="Headache" {{(in_array('Headache', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Headache</option>
                        <option value="Myalgia" {{(in_array('Myalgia', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Myalgia</option>
                        <option value="Sore throat" {{(in_array('Sore throat', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Sore throat</option>
                        <option value="Coryza" {{(in_array('Coryza', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Coryza</option>
                        <option value="Dyspnea" {{(in_array('Dyspnea', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Dyspnea</option>
                        <option value="Anorexia" {{(in_array('Anorexia', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Anorexia</option>
                        <option value="Nausea" {{(in_array('Nausea', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Nausea</option>
                        <option value="Vomiting" {{(in_array('Vomiting', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Vomiting</option>
                        <option value="Diarrhea" {{(in_array('Diarrhea', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Diarrhea</option>
                        <option value="Altered Mental Status" {{(in_array('Altered Mental Status', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Altered Mental Status</option>
                        <option value="Anosmia (Loss of Smell)" {{(in_array('Anosmia (Loss of Smell)', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Anosmia (Loss of Smell)</option>
                        <option value="Ageusia (Loss of Taste)" {{(in_array('Ageusia (Loss of Taste)', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Ageusia (Loss of Taste)</option>
                        <option value="Others" {{(in_array('Others', old('symptoms', explode(',', $data->SAS)))) ? 'selected' : ''}}>Others (Specify)</option>
                      </select>
                    </div>
                    <div id="divFeverChecked">
                        <div class="form-group mt-2">
                          <label for="SASFeverDeg"><span class="text-danger font-weight-bold">*</span>Degrees (in Celcius)</label>
                          <input type="number" class="form-control" name="SASFeverDeg" id="SASFeverDeg" min="1" max="90" value="{{old('SASFeverDeg', $data->SASFeverDeg)}}">
                        </div>
                    </div>
                    <div id="divSASOtherChecked">
                        <div class="form-group mt-2">
                          <label for="SASOtherRemarks"><span class="text-danger font-weight-bold">*</span>Specify Findings <small>(Separate with commas [,])</small></label>
                          <input type="text" class="form-control" name="SASOtherRemarks" id="SASOtherRemarks" value="{{old('SASOtherRemarks', $data->SASOtherRemarks)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label for="comorbidities"><span class="text-danger font-weight-bold">*</span>Comorbidities <small>(Check all that apply if present)</small></label>
                      <select class="form-control" name="comorbidities[]" id="comorbidities" multiple>
                        <option value="Hypertension" {{(in_array('Hypertension', old('comorbidities', explode(',', $data->COMO)))) ? 'selected' : ''}}>Hypertension</option>
                        <option value="Diabetes" {{(in_array('Diabetes', old('comorbidities', explode(',', $data->COMO)))) ? 'selected' : ''}}>Diabetes</option>
                        <option value="Heart Disease" {{(in_array('Heart Disease', old('comorbidities', explode(',', $data->COMO)))) ? 'selected' : ''}}>Heart Disease</option>
                        <option value="Lung Disease" {{(in_array('Lung Disease', old('comorbidities', explode(',', $data->COMO)))) ? 'selected' : ''}}>Lung Disease</option>
                        <option value="Gastrointestinal" {{(in_array('Gastrointestinal', old('comorbidities', explode(',', $data->COMO)))) ? 'selected' : ''}}>Gastrointestinal</option>
                        <option value="Genito-urinary" {{(in_array('Genito-urinary', old('comorbidities', explode(',', $data->COMO)))) ? 'selected' : ''}}>Genito-urinary</option>
                        <option value="Neurological Disease" {{(in_array('Neurological Disease', old('comorbidities', explode(',', $data->COMO)))) ? 'selected' : ''}}>Neurological Disease</option>
                        <option value="Cancer" {{(in_array('Cancer', old('comorbidities', explode(',', $data->COMO)))) ? 'selected' : ''}}>Cancer</option>
                        <option value="Others" {{(in_array('Others', old('comorbidities', explode(',', $data->COMO)))) ? 'selected' : ''}}>Others (Specify)</option>
                      </select>
                    </div>
                    <div id="divComOthersChecked">
                        <div class="form-group mt-2">
                          <label for="COMOOtherRemarks"><span class="text-danger font-weight-bold">*</span>Specify Findings</label>
                          <input type="text" class="form-control" name="COMOOtherRemarks" id="COMOOtherRemarks" value="{{old('COMOOtherRemarks', $data->COMOOtherRemarks)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label for="facility_remarks">Remarks <small>(Optional)</small></label>
                      <input type="text" name="facility_remarks" id="facility_remarks" class="form-control" value="{{old('facility_remarks', $data->facility_remarks)}}">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                </div>
            </div>
        </form>
        <form action="{{route('facility.initdischarge', ['id' => $data->id])}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header font-weight-bold">Discharge Patient</div>
                <div class="card-body">
                    <div class="form-group">
                      <label for="dispoDate"><span class="text-danger font-weight-bold">*</span>Date of Discharge / Recovery</label>
                      <input type="date" class="form-control" name="dispoDate" id="dispoDate" min="{{date('Y-m-d', strtotime('-14 Days'))}}" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
                    </div>
                    <div class="form-group">
                      <label for="facility_remarks">Remarks <small>(Optional)</small></label>
                      <input type="text" class="form-control" name="facility_remarks" id="facility_remarks">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Note: You cannot revert this process once it is done. Click OK to Proceed.')">Discharge Patient</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('#symptoms').select2({
                theme: "bootstrap",
            });

            $('#comorbidities').select2({
                theme: "bootstrap",
            });
        });

        $('#comorbidities').change(function (e) { 
            e.preventDefault();
            if($("#comorbidities option[value=Others]:selected").length > 0) {
                $('#divComOthersChecked').show();
                $('#COMOOtherRemarks').prop('required', true);
            }
            else {
                $('#divComOthersChecked').hide();
                $('#COMOOtherRemarks').prop('required', false);
            }
        }).trigger('change');

        $('#symptoms').change(function (e) { 
            e.preventDefault();

            if($("#symptoms option[value=Fever]:selected").length > 0) {
                $('#divFeverChecked').show();
                $('#SASFeverDeg').prop('required', true);
            }
            else {
                $('#divFeverChecked').hide();
                $('#SASFeverDeg').prop('required', false);
            }

            if($("#symptoms option[value=Others]:selected").length > 0) {
                $('#divSASOtherChecked').show();
                $('#SASOtherRemarks').prop('required', true);
            }
            else {
                $('#divSASOtherChecked').hide();
                $('#SASOtherRemarks').prop('required', false);
            }
        }).trigger('change');
    </script>
@endsection