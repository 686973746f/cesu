<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="edcs_investigateDate"><b class="text-danger">*</b>Date of Investigation</label>
            <input type="date" class="form-control" name="edcs_investigateDate" id="edcs_investigateDate" value="{{old('edcs_investigateDate')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="edcs_investigatorName"><b class="text-danger">*</b>Name of Investigator/s</label>
            <input type="text" class="form-control" name="edcs_investigatorName" id="edcs_investigatorName" style="text-transform: uppercase;" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="edcs_contactNo"><b class="text-danger">*</b>Contact Number</label>
            <input type="text" class="form-control" id="edcs_contactNo" name="edcs_contactNo" value="{{old('edcs_contactNo')}}" pattern="[0-9]{11}" placeholder="09*********" required>
        </div>
    </div>
</div>