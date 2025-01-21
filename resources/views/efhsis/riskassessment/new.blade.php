@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('raf_store')}}" method="POST">
        <div class="card">
            <div class="card-header"><b>New CVD/NCD Risk Assessment Form</b></div>
            <div class="card-body">
                @if(isset($s))

                @else

                @endif
                <div class="row">
                  <div class="col-md-3">
                    <div><label for="height">Height (cm)</label></div>
                    <div class="input-group mb-3">
                      <input type="number" step="0.1" class="form-control" name="height" id="height" min="1" max="600">
                      <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" data-toggle="modal" data-target="#heightConverter">Convert feet to cm</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="weight">Weight (kg)</label>
                      <input type="number" class="form-control" name="weight" id="weight" min="1" max="500" step="0.1">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="systolic">BP (Systolic)</label>
                      <input type="number" class="form-control" name="systolic" id="systolic">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="diastolic">BP (Diastolic)</label>
                      <input type="number" class="form-control" name="diastolic" id="diastolic">
                    </div>
                  </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Family History</div>
                            <div class="card-body">
                                <h6>Does patient have 1st degree relative with:</h6>
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
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="smoking">Smoking (Tobacco/Cigarette/Vape)</label>
                          <select class="form-control" name="smoking" id="smoking" required>
                            <option value="" disabled {{(is_null(old('smoking'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="NEVER">Never smoked</option>
                            <option value="STOPPED<1Y">Recently Stopped (Less than 1 year ago)</option>
                            <option value="STOPPED>1Y">Stopped 2 or more years ago</option>
                            <option value="CURRENT">Current Smoker</option>
                            <option value="MASSIVE">Massive Smoker</option>
                          </select>
                        </div>
                        <hr>
                        <div class="form-check">
                            <label class="form-check-label">
                              <input type="checkbox" class="form-check-input" name="alcohol_intake" id="alcohol_intake" value="Y">
                              Alcohol Intake?
                            </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="excessive_alcohol_intake" id="excessive_alcohol_intake" value="Y">
                            Excessive Alcohol Intake (Had 5 drinks in one occasion in the past month)
                          </label>
                        </div>

                        <hr>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="high_fatsalt_intake" id="high_fatsalt_intake" value="Y">
                            Eats processed/fast foods (e.g. instant noodles, hamburgers, fries, fried chicken skin, etc.) and ihaw-ihaw (e.g. isaw, adidas, etc.) weekly.
                          </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="vegetable_serving" id="vegetable_serving" value="Y">
                            Eats 3 servings of vegetables daily
                          </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="fruits_serving" id="fruits_serving" value="Y">
                            Eats 2-3 servings of fruits daily
                          </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" name="physical_activity" id="physical_activity" value="Y">
                            Does at least 2 and a half hours of a WEEK of moderate intensity physical activity
                          </label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="obese" id="obese" value="Y">
                        Obese
                      </label>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="overweight" id="overweight" value="Y">
                        Overweight
                      </label>
                    </div>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="central_adiposity" id="central_adiposity" value="Y">
                        Central Adiposity (Taba sa tiyan)
                      </label>
                    </div>
                    <div class="form-group">
                      <label for="waist_cm">Waist Circumference (cm)</label>
                      <input type="number" class="form-control" name="waist_cm" id="waist_cm" step=".1">
                    </div>
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="raised_bp" id="raised_bp" value="Y">
                        Raised BP
                      </label>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label for="diabetes">Was patient diagnosed as having diabetes?</label>
                      <select class="form-control" name="diabetes" id="diabetes" required>
                        <option value="" disabled {{(is_null(old('diabetes'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('diabetes') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                        <option value="N" {{(old('diabetes') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                        <option value="U" {{(old('diabetes') == 'U') ? 'selected' : ''}}>Do not know/Hindi alam</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="diabetes_medication">With Medications</label>
                      <select class="form-control" name="diabetes_medication" id="diabetes_medication" required>
                        <option value="" disabled {{(is_null(old('diabetes_medication'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('diabetes_medication') == 'Y') ? 'selected' : ''}}>With Medications</option>
                        <option value="N" {{(old('diabetes_medication') == 'N') ? 'selected' : ''}}>Without Medications</option>
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="polyphagia">Polyphagia (Laging gutom)</label>
                      <select class="form-control" name="polyphagia" id="polyphagia" required>
                        <option value="" disabled {{(is_null(old('polyphagia'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('polyphagia') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                        <option value="N" {{(old('polyphagia') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="polydipsia">Polydipsia (Laging uhaw)</label>
                      <select class="form-control" name="polydipsia" id="polydipsia" required>
                        <option value="" disabled {{(is_null(old('polydipsia'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('polydipsia') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                        <option value="N" {{(old('polydipsia') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="polyuria">Polyuria (Laging umiihi)</label>
                      <select class="form-control" name="polyuria" id="polyuria" required>
                        <option value="" disabled {{(is_null(old('polyuria'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('polyuria') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                        <option value="N" {{(old('polyuria') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                      </select>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label for="raised_bloodglucose">Raised Blood Glucose</label>
                      <select class="form-control" name="raised_bloodglucose" id="raised_bloodglucose" required>
                        <option value="" disabled {{(is_null(old('raised_bloodglucose'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('raised_bloodglucose') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                        <option value="N" {{(old('raised_bloodglucose') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="raised_bloodlipids">Raised Blood Lipids</label>
                      <select class="form-control" name="raised_bloodlipids" id="raised_bloodlipids" required>
                        <option value="" disabled {{(is_null(old('raised_bloodlipids'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('raised_bloodlipids') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                        <option value="N" {{(old('raised_bloodlipids') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="urine_protein">Presence of Urine Protein</label>
                      <select class="form-control" name="urine_protein" id="urine_protein" required>
                        <option value="" disabled {{(is_null(old('urine_protein'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('urine_protein') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                        <option value="N" {{(old('urine_protein') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="urine_ketones">Presence of Urine Ketones (for newly diagnosed DM)</label>
                      <select class="form-control" name="urine_ketones" id="urine_ketones" required>
                        <option value="" disabled {{(is_null(old('urine_ketones'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="Y" {{(old('urine_ketones') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                        <option value="N" {{(old('urine_ketones') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card mt-3">
                      <div class="card-header">Questionnaire to Determine Probable Angina, Heart Attack, Stroke or Transient Ischemic Attack</div>
                      <div class="card-body">
                        <div class="form-group">
                          <label for="heart_attack">Angina or Heart Attack</label>
                          <select class="form-control" name="heart_attack" id="heart_attack" required>
                            <option value="" disabled {{(is_null(old('heart_attack'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('heart_attack') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                            <option value="N" {{(old('heart_attack') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                          </select>
                        </div>

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
                              <div>Does the pain go away if you stand still or if you take tablet under tongue?</div>
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
                          <label for="stroke_ortia">
                            <div>Stroke and TIA</div>
                          </label>
                          <select class="form-control" name="stroke_ortia" id="stroke_ortia">
                            <option value="" disabled {{(is_null(old('stroke_ortia'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('stroke_ortia') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                            <option value="N" {{(old('stroke_ortia') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="question8">
                            <div>8. Have you ever had any of the following: difficulty in talking, weakness of arm and/or leg on one side of the body or numbness on one side of the body?</div>
                            <div>Nakaramdam ka na ba ng mga sumusunod? hirap sa pagsasalita, panghihina ng braso at/o ng binti o pamamanhid sa kalahating bahagi ng katawan.</div>
                          </label>
                          <select class="form-control" name="question8" id="question8" required>
                            <option value="" disabled {{(is_null(old('question8'))) ? 'selected' : ''}}>Choose...</option>
                            <option value="Y" {{(old('question8') == 'Y') ? 'selected' : ''}}>Yes/Oo</option>
                            <option value="N" {{(old('question8') == 'N') ? 'selected' : ''}}>No/Hindi</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="finding">Finding/s</label>
                      <textarea class="form-control" name="finding" id="finding" rows="3">{{old('finding')}}</textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="management">Management</label>
                      <select class="form-control" name="management" id="management" required>
                        <option value="" disabled {{(is_null(old('management'))) ? 'selected' : ''}}>Choose...</option>
                        <option value="STYLE MODIFICATION" {{(old('management') == 'STYLE MODIFICATION') ? 'selected' : ''}}>Style Modification</option>
                        <option value="MEDICATIONS" {{(old('management') == 'MEDICATIONS') ? 'selected' : ''}}>Medications</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="date_followup">Date of Follow-up</label>
                      <input type="text" class="form-control" name="date_followup" id="date_followup" style="text-transform: uppercase">
                    </div>
                    <div class="form-group">
                      <label for="meds">Meds</label>
                      <textarea class="form-control" name="meds" id="meds" rows="3">{{old('meds')}}</textarea>
                    </div>
                  </div>
                </div>
                

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">Submit</button>
            </div>
        </div>
    </form>
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
</script>
@endsection