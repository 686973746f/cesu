<form action="{{route('pidsr_laboratory_group_patient_store')}}" method="POST">
    @csrf
    <div class="modal fade" id="addPatient" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Patient</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname', $lname)}}" minlength="2" maxlength="50" placeholder="ex: DELA CRUZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required {{(!$manual_mode) ? 'readonly' : ''}}>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname', $fname)}}" minlength="2" maxlength="50" placeholder="ex: JUAN" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required {{(!$manual_mode) ? 'readonly' : ''}}>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="mname">Middle Name <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname', $mname)}}" minlength="2" maxlength="50" placeholder="ex: SANCHEZ" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" {{(!$manual_mode) ? 'readonly' : ''}}>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="suffix">Suffix <i>(If Applicable)</i></label>
                                <input type="text" class="form-control" name="suffix" id="suffix" value="{{old('suffix', $suffix)}}" minlength="2" maxlength="3" placeholder="ex: JR, SR, II, III, IV" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" {{(!$manual_mode) ? 'readonly' : ''}}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                            <label for="age"><b class="text-danger">*</b>Age (In Years)</label>
                            <input type="number" min="0" max="300" class="form-control" name="age" id="age" value="{{old('age', $age)}}" required {{(!$manual_mode) ? 'readonly' : ''}}>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="gender"><b class="text-danger">*</b>Sex</label>
                                <select class="form-control" name="gender" id="gender" required>
                                    <option value="" disabled selected>Choose...</option>
                                    <option value="M" {{($gender == 'M') ? 'selected' : ''}}>Male</option>
                                    <option value="F" {{($gender == 'F') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save (CTRL + S)</button>
                </div>
            </div>
        </div>
    </div>
</form>