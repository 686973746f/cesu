<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="entry_date"><b class="text-danger">*</b>Date Admitted/Seen/Consulted</label>
            <input type="date" class="form-control" name="entry_date" id="entry_date" value="{{request()->input('entry_date')}}" min="{{date('Y-m-d', strtotime('-1 Year'))}}" max="{{date('Y-m-d')}}" tabindex="-1" readonly>
        </div>
        <div class="form-group">
            <label for="PatientNumber">Patient No.</label>
            <input type="text" class="form-control" name="PatientNumber" id="PatientNumber" value="{{old('PatientNumber')}}" style="text-transform: uppercase;">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="sys_interviewer_name"><b class="text-danger">*</b>Name of Reporter/Interviewer</label>
            <input type="text" class="form-control" name="sys_interviewer_name" id="sys_interviewer_name" value="{{old('sys_interviewer_name', $f->edcs_defaultreporter_name)}}" style="text-transform: uppercase;" required>
        </div>
        <div class="form-group">
            <label for="sys_interviewer_contactno"><b class="text-danger">*</b>Contact No. of Reporter/Interviewer</label>
            <input type="text" class="form-control" id="sys_interviewer_contactno" name="sys_interviewer_contactno" value="{{old('sys_interviewer_contactno', $f->edcs_defaultreporter_contactno)}}" pattern="[0-9]{11}" placeholder="09*********" required>
        </div>
    </div>
</div>
<hr>
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
            <label for="suffix">Suffix</label>
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
            <label for="sex"><span class="text-danger font-weight-bold">*</span>Sex</label>
            <select class="form-control" name="sex" id="sex" required>
                <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                <option value="M" {{(old('gender') == 'M') ? 'selected' : ''}}>Male</option>
                <option value="F" {{(old('gender') == 'F') ? 'selected' : ''}}>Female</option>
            </select>
        </div>
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
        <div class="form-group">
            <label for="address_region_code"><b class="text-danger">*</b>Region</label>
            <select class="form-control" name="address_region_code" id="address_region_code" tabindex="-1" required>
            @foreach(App\Models\Regions::orderBy('regionName', 'ASC')->get() as $a)
            <option value="{{$a->id}}" {{($a->id == 1) ? 'selected' : ''}}>{{$a->regionName}}</option>
            @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="address_province_code"><b class="text-danger">*</b>Province</label>
            <select class="form-control" name="address_province_code" id="address_province_code" tabindex="-1" required disabled>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="address_muncity_code"><b class="text-danger">*</b>City/Municipality</label>
            <select class="form-control" name="address_muncity_code" id="address_muncity_code" tabindex="-1" required disabled>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="address_brgy_code"><b class="text-danger">*</b>Barangay</label>
            <select class="form-control" name="brgy_id" id="address_brgy_code" required disabled>
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="Streetpurok" class="form-label"><b class="text-danger">*</b>House/Lot No./Street/Purok/Subdivision</label>
    <input type="text" class="form-control" id="Streetpurok" name="Streetpurok" style="text-transform: uppercase;" value="{{old('Streetpurok')}}" placeholder="ex. S1 B2 L3 PHASE 4 SUBDIVISION HOMES" required>
</div>
<div class="form-group">
    <label for="sys_occupationtype"><span class="text-danger font-weight-bold">*</span>Has Occupation/Student?</label>
    <select class="form-control" name="sys_occupationtype" id="sys_occupationtype" required>
        <option value="" disabled {{(is_null(old('sys_occupationtype'))) ? 'selected' : ''}}>Choose...</option>
        <option value="WORKING" {{(old('sys_occupationtype') == 'WORKING') ? 'selected' : ''}}>Has Occupation/Work</option>
        <option value="STUDENT" {{(old('sys_occupationtype') == 'STUDENT') ? 'selected' : ''}}>Student</option>
        <option value="NONE" {{(old('sys_occupationtype') == 'NONE') ? 'selected' : ''}}>Not Applicable (N/A)</option>
    </select>
</div>
<div class="row d-none" id="hasOccupation">
    <div class="col-md-6">
        <div class="form-group">
            <label for="sys_businessorschool_name" class="form-label"><b class="text-danger">*</b><span id="occupationNameText"></span></label>
            <input type="text" class="form-control" id="sys_businessorschool_name" name="sys_businessorschool_name" style="text-transform: uppercase;" value="{{old('sys_businessorschool_name')}}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="sys_businessorschool_address" class="form-label"><b class="text-danger">*</b><span id="occupationAddressText"></span></label>
            <input type="text" class="form-control" id="sys_businessorschool_address" name="sys_businessorschool_address" style="text-transform: uppercase;" value="{{old('sys_businessorschool_address')}}" pattern="(^[a-zA-Z0-9 ]+$)+">
        </div>
    </div>
</div>