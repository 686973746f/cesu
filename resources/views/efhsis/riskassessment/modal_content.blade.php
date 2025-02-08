
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
    <label for="sex"><span class="text-danger font-weight-bold">*</span>Gender</label>
    <select class="form-control" name="sex" id="sex" required>
        <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
        <option value="M" {{(old('gender') == 'M') ? 'selected' : ''}}>Male</option>
        <option value="F" {{(old('gender') == 'F') ? 'selected' : ''}}>Female</option>
    </select>
</div>
<div class="form-group">
    <label for="bdate"><b class="text-danger">*</b>Birthdate</label>
    <input type="date" class="form-control" name="bdate" id="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
</div>
@if(!is_null($f))
<input type="hidden" name="facility_code" value="{{$f->sys_code1}}" required>
@endif