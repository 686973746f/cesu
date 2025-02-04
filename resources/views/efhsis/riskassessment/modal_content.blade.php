<div class="form-group">
    <label for="lname"><b class="text-danger">*</b>Last Name</label>
    <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" placeholder="DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
</div>
<div class="form-group">
    <label for="fname"><b class="text-danger">*</b>First Name</label>
    <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" placeholder="JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="mname">Middle Name <i>(If Applicable)</i></label>
            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" placeholder="SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="suffix">Suffix <i>(If Applicable)</i></label>
            <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" minlength="2" maxlength="3" placeholder="JR, SR, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
        </div>
    </div>
</div>
<div class="form-group">
    <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
    <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
</div>