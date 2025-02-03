<div class="modal fade" id="ncModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Non-comm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div id="ncAccordian" role="tablist" aria-multiselectable="true">
                    <div class="card">
                        <div class="card-header" role="tab" id="ncSection1Header">
                            <a data-toggle="collapse" data-parent="#ncAccordian" href="#ncSection1" aria-expanded="true" aria-controls="ncSection1">New Risk Assessment Form</a>
                        </div>
                        <div id="ncSection1" class="collapse in" role="tabpanel" aria-labelledby="ncSection1Header">
                            <form action="{{route('raf_create')}}" method="GET">
                                <div class="card-body">
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
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success btn-block">Next</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="ncSection2Header">
                            <a data-toggle="collapse" data-parent="#ncAccordian" href="#ncSection2" aria-expanded="true" aria-controls="ncSection2">View Masterlist</a>
                        </div>
                        <div id="ncSection2" class="collapse in" role="tabpanel" aria-labelledby="ncSection2Header">
                            <div class="card-body">
                                Coming soon.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>