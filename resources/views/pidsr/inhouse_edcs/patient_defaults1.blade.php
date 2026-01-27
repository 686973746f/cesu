<hr>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="Admitted"><span class="text-danger font-weight-bold">*</span><span id="admitted_select_text">Admitted?</span></label>
            <select class="form-control" name="Admitted" id="Admitted" required>
                <option value="" disabled {{(is_null(old('Admitted'))) ? 'selected' : ''}}>Choose...</option>
                <option value="Y" {{(old('Admitted') == 'Y') ? 'selected' : ''}}>Yes</option>
                <option value="N" {{(old('Admitted') == 'N') ? 'selected' : ''}}>No</option>
            </select>
        </div>
        <div id="hospitalizedDiv" class="d-none">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="sys_hospitalized_name"><b class="text-danger">*</b>Name of Hospital/Health Facility</label>
                        <input type="text" class="form-control" name="sys_hospitalized_name" id="sys_hospitalized_name" style="text-transform: uppercase;">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="DAdmit"><b class="text-danger">*</b>Date Admitted</label>
                        <input type="date" class="form-control" name="DAdmit" id="DAdmit" value="{{old('DAdmit')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sys_hospitalized_dateend">Date Discharged</label>
                        <input type="date" class="form-control" name="sys_hospitalized_dateend" id="sys_hospitalized_dateend" value="{{old('sys_hospitalized_dateend')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="DOnset"><b class="text-danger">*</b><span id="onset_text">Date Onset of Illness (Kailan nagsimula ang sintomas)</span></label>
            <input type="date" class="form-control" name="DOnset" id="DOnset" value="{{old('DOnset')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
        </div>
    </div>
</div>

<script>
    $('#Admitted').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#hospitalizedDiv').removeClass('d-none');
            $('#sys_hospitalized_name').prop('required', true);
            $('#DAdmit').prop('required', true);
        }
        else {
            $('#hospitalizedDiv').addClass('d-none');
            $('#sys_hospitalized_name').prop('required', false);
            $('#DAdmit').prop('required', false);
        }
    }).trigger('change');
</script>