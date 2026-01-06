@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between">
        <div></div>
        <div>{!! QrCode::size(100)->generate($d->hash) !!}</div>
    </div>
    <div>
        
    </div>
    <a href="{{route('disaster_viewfamilies')}}" class="btn btn-secondary mb-3">Go Back</a>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <div><b>View Family Head and Members</b></div>
                    <div>{{$d->getName()}} (ID: {{$d->id}})</div>
                </div>
                <div>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modelId">
                        Add Family Member
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <form action="{{route('disaster_updatefamilyhead', $d->id)}}" method="POST">
                @csrf
                <div id="accordianId" role="tablist" aria-multiselectable="true">
                    <div class="card mb-3">
                        <div class="card-header" role="tab" id="section1HeaderId">
                            <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId"><b>Family Head Details</b></a>
                        </div>
                        <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                            <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname', $d->lname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                            <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname', $d->fname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="mname">Middle Name</label>
                                            <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname', $d->mname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="suffix">Name Extension <small>(ex. JR, SR, II, III, etc.)</small></label>
                                            <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix', $d->suffix)}}" minlength="2" maxlength="6" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                            <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $d->bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label for="birthplace">Birthplace</label>
                                        <input type="text" class="form-control" name="birthplace" id="birthplace" value="{{old('birthplace', $d->birthplace)}}" style="text-transform: uppercase">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                            <select class="form-control" name="sex" id="sex" required>
                                            <option value="M" {{(old('sex', $d->sex) == 'M') ? 'selected' : ''}}>Male</option>
                                            <option value="F" {{(old('sex', $d->sex) == 'F') ? 'selected' : ''}}>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="gender"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                            <select class="form-control" name="cs" id="cs" required>
                                                <option value="SINGLE" {{(old('cs', $d->cs) == 'SINGLE') ? 'selected' : ''}}>Single</option>
                                                <option value="MARRIED" {{(old('cs', $d->cs) == 'MARRIED') ? 'selected' : ''}}>Married</option>
                                                <option value="WIDOWED" {{(old('cs', $d->cs) == 'WIDOWED') ? 'selected' : ''}}>Widowed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_number"><span class="text-danger font-weight-bold">*</span>Primary Contact Number</label>
                                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $d->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="contact_number2">Alternate Contact Number</label>
                                            <input type="text" class="form-control" id="contact_number2" name="contact_number2" value="{{old('contact_number2', $d->contact_number2)}}" pattern="[0-9]{11}" placeholder="09*********">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="religion">Religion</label>
                                            <input type="text" class="form-control" id="religion" name="religion" value="{{old('religion', $d->religion)}}" style="text-transform: uppercase;">
                                        </div>
                                        <div class="form-group">
                                            <label for="monthlyfamily_income">Monthly Family Net Income</label>
                                            <input type="number" class="form-control" id="monthlyfamily_income" name="monthlyfamily_income" value="{{old('monthlyfamily_income', $d->monthlyfamily_income)}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="id_presented"><b class="text-danger">*</b>ID Card Presented</label>
                                            <input type="text" class="form-control" id="id_presented" name="id_presented" value="{{old('id_presented', $d->id_presented)}}" style="text-transform: uppercase;" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_number"><b class="text-danger">*</b>ID Card Number</label>
                                            <input type="text" class="form-control" id="id_number" name="id_number" value="{{old('id_number', $d->id_number)}}" style="text-transform: uppercase;" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="occupation">Occupation</label>
                                            <input type="text" class="form-control" id="occupation" name="occupation" value="{{old('occupation', $d->occupation)}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="house_ownership"><span class="text-danger font-weight-bold">*</span>House Ownership</label>
                                            <select class="form-control" name="house_ownership" id="house_ownership" required>
                                                <option value="OWNER" {{(old('house_ownership', $d->house_ownership) == 'OWNER') ? 'selected' : ''}}>Owner</option>
                                                <option value="RENTER" {{(old('house_ownership', $d->house_ownership) == 'RENTER') ? 'selected' : ''}}>Renter</option>
                                                <option value="SHARER" {{(old('house_ownership', $d->house_ownership) == 'SHARER') ? 'selected' : ''}}>Sharer</option>
                                                <option value="INFORMAL SETTLER" {{(old('house_ownership', $d->house_ownership) == 'INFORMAL SETTLER') ? 'selected' : ''}}>Informal Settler</option>
                                                <option value="N/A" {{(old('house_ownership', $d->house_ownership) == 'N/A') ? 'selected' : ''}}>Not Applicable (N/A)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="mothermaiden_name">Mother's Maiden Name</label>
                                            <input type="text" class="form-control" id="mothermaiden_name" name="mothermaiden_name" value="{{old('mothermaiden_name', $d->mothermaiden_name)}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_soloparent"><span class="text-danger font-weight-bold">*</span>Is Solo Parent?</label>
                                            <select class="form-control" name="is_soloparent" id="is_soloparent" required>
                                                <option value="Y" {{(old('is_soloparent', $d->is_soloparent) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                <option value="N" {{(old('is_soloparent', $d->is_soloparent) == 'N') ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_4ps"><span class="text-danger font-weight-bold">*</span>Is 4Ps Beneficiary?</label>
                                            <select class="form-control" name="is_4ps" id="is_4ps" required>
                                                <option value="Y" {{(old('is_4ps', $d->is_4ps) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                <option value="N" {{(old('is_4ps', $d->is_4ps) == 'N') ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="is_indg"><span class="text-danger font-weight-bold">*</span>Is Indigenous People?</label>
                                            <select class="form-control" name="is_indg" id="is_indg" required>
                                                <option value="Y" {{(old('is_indg', $d->is_indg) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                <option value="N" {{(old('is_indg', $d->is_indg) == 'N') ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header"><b>Permanent Address</b></div>
                                    <div class="card-body">
                                        <div class="row" id="address_div">
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
                                                    <select class="form-control" name="address_brgy_code" id="address_brgy_code" required disabled>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="street_purok"><b class="text-danger">*</b>House No., Street/Purok/Subdivision</label>
                                                    <input type="text" class="form-control" id="street_purok" name="street_purok" value="{{old('street_purok', $d->street_purok)}}" style="text-transform: uppercase;" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dswd_serialno">DSWD Serial No.</label>
                                            <input type="text" class="form-control" name="dswd_serialno" id="dswd_serialno" value="{{old('dswd_serialno', $d->dswd_serialno)}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="cswd_serialno">CSWD Serial No.</label>
                                            <input type="text" class="form-control" name="cswd_serialno" id="cswd_serialno" value="{{old('cswd_serialno', $d->cswd_serialno)}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                  <label for="">Remarks</label>
                                  <textarea class="form-control" name="remarks" id="remarks" rows="3">{{$d->remarks}}</textarea>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div><b>List of Family Members</b></div>
                        <div></div>
                    </div>
                </div>
                <div class="card-body">
                    

                    @if($member_list->count() != 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="text-center thead-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Birthdate</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Relationship to Family Head</th>
                                    <th>Highest Education Attainment</th>
                                    <th>Occupation</th>
                                    <th>Created at/by</th>
                                    <th>Updated at/by</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($member_list as $ind => $m)
                                <tr>
                                    <td class="text-center">{{$ind+1}}</td>
                                    <td>
                                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#memberModal{{$m->id}}">{{$m->getName()}}</button>
                                    </td>
                                    <td class="text-center">{{date('m/d/Y', strtotime($m->bdate))}}</td>
                                    <td class="text-center">{{$m->getAge()}}</td>
                                    <td class="text-center">{{$m->sex}}</td>
                                    <td class="text-center">{{$m->relationship_tohead}}</td>
                                    <td class="text-center">{{$m->highest_education}}</td>
                                    <td class="text-center">{{$m->occupation}}</td>
                                    <td class="text-center">{{date('m/d/Y h:i A', strtotime($m->created_at))}}</td>
                                    <td class="text-center">{{date('m/d/Y h:i A', strtotime($m->updated_at))}}</td>
                                </tr>

                                <form action="{{route('disaster_updatemember', $m->id)}}" method="POST">
                                    @csrf
                                    <div class="modal fade" id="memberModal{{$m->id}}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Family Member Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                                                <input type="text" class="form-control" id="lname{{$m->id}}" name="lname" value="{{old('lname', $m->lname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                                                <input type="text" class="form-control" id="fname{{$m->id}}" name="fname" value="{{old('fname', $m->fname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="mname">Middle Name</label>
                                                                <input type="text" class="form-control" id="mname{{$m->id}}" name="mname" value="{{old('mname', $m->mname)}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="suffix">Name Extension <small>(ex. JR, SR, II, III, etc.)</small></label>
                                                                <input type="text" class="form-control" id="suffix{{$m->id}}" name="suffix" value="{{old('suffix', $m->suffix)}}" minlength="2" maxlength="6" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                                                <input type="date" class="form-control" id="bdate{{$m->id}}" name="bdate" value="{{old('bdate', $m->bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="sex{{$m->id}}"><b class="text-danger">*</b>Sex</label>
                                                                <select class="form-control sex-field" name="sex" id="sex{{$m->id}}" required>
                                                                    <option value="M" {{old('sex', $m->sex) == 'M' ? 'selected' : ''}}>Male</option>
                                                                    <option value="F" {{old('sex', $m->sex) == 'F' ? 'selected' : ''}}>Female</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="relationship_tohead"><span class="text-danger font-weight-bold">*</span>Relationship to the Family Head</label>
                                                                <select class="form-control" name="relationship_tohead" id="relationship_tohead{{$m->id}}" required>
                                                                    <option value="SPOUSE" {{(old('relationship_tohead', $m->relationship_tohead) == 'SPOUSE') ? 'selected' : ''}}>Spouse/Asawa</option>
                                                                    <option value="CHILD" {{(old('relationship_tohead', $m->relationship_tohead) == 'CHILD') ? 'selected' : ''}}>Child</option>
                                                                    <option value="GRANDCHILDREN" {{(old('relationship_tohead', $m->relationship_tohead) == 'GRANDCHILDREN') ? 'selected' : ''}}>Grandchildren</option>
                                                                    <option value="OTHERS" {{(old('relationship_tohead', $m->relationship_tohead) == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="highest_education"><span class="text-danger font-weight-bold">*</span>Highest Educational Attainment</label>
                                                                <select class="form-control" name="highest_education" id="highest_education{{$m->id}}" required>
                                                                    <option value="NO FORMAL EDUCATION" {{(old('highest_education', $m->highest_education) == 'NO FORMAL EDUCATION') ? 'selected' : ''}}>No Formal Education/Hindi nakapag-aral</option>
                                                                    <option value="ELEMENTARY UNDERGRADUATE" {{(old('highest_education', $m->highest_education) == 'ELEMENTARY UNDERGRADUATE') ? 'selected' : ''}}>Elementary Undergraduate</option>
                                                                    <option value="ELEMENTARY GRADUATE" {{(old('highest_education', $m->highest_education) == 'ELEMENTARY GRADUATE') ? 'selected' : ''}}>Elementary Graduate</option>
                                                                    <option value="JUNIOR HIGH SCHOOL UNDERGRADUATE" {{(old('highest_education', $m->highest_education) == 'JUNIOR HIGH SCHOOL UNDERGRADUATE') ? 'selected' : ''}}>Junior High School Undergraduate</option>
                                                                    <option value="JUNIOR HIGH SCHOOL GRADUATE" {{(old('highest_education', $m->highest_education) == 'JUNIOR HIGH SCHOOL GRADUATE') ? 'selected' : ''}}>Junior High School Graduate</option>
                                                                    <option value="SENIOR HIGH SCHOOL UNDERGRADUATE" {{(old('highest_education', $m->highest_education) == 'SENIOR HIGH SCHOOL UNDERGRADUATE') ? 'selected' : ''}}>Senior High School Undergraduate</option>
                                                                    <option value="SENIOR HIGH SCHOOL GRADUATE" {{(old('highest_education', $m->highest_education) == 'SENIOR HIGH SCHOOL GRADUATE') ? 'selected' : ''}}>Senior High School Graduate</option>
                                                                    <option value="COLLEGE UNDERGRADUATE" {{(old('highest_education', $m->highest_education) == 'COLLEGE UNDERGRADUATE') ? 'selected' : ''}}>College Undergraduate</option>
                                                                    <option value="COLLEGE GRADUATE" {{(old('highest_education', $m->highest_education) == 'COLLEGE GRADUATE') ? 'selected' : ''}}>College Graduate</option>
                                                                    <option value="MASTERS DEGREE" {{(old('highest_education', $m->highest_education) == 'MASTERS DEGREE') ? 'selected' : ''}}>Masters Degree</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="occupation">Occupation</label>
                                                                <input type="text" class="form-control" id="occupation{{$m->id}}" name="occupation" value="{{old('occupation', $m->occupation)}}" style="text-transform: uppercase;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="is_pwd"><span class="text-danger font-weight-bold">*</span>Is PWD</label>
                                                                <select class="form-control" name="is_pwd" id="is_pwd{{$m->id}}" required>
                                                                    <option value="Y" {{(old('is_pwd', $m->is_pwd) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                                    <option value="N" {{(old('is_pwd', $m->is_pwd) == 'N') ? 'selected' : ''}}>No</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="is_4ps"><span class="text-danger font-weight-bold">*</span>Is 4Ps</label>
                                                                <select class="form-control" name="is_4ps" id="is_4ps{{$m->id}}" required>
                                                                    <option value="Y" {{(old('is_4ps', $m->is_4ps) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                                    <option value="N" {{(old('is_4ps', $m->is_4ps) == 'N') ? 'selected' : ''}}>No</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="is_indg"><span class="text-danger font-weight-bold">*</span>Is Indigent</label>
                                                                <select class="form-control" name="is_indg" id="is_indg{{$m->id}}" required>
                                                                    <option value="Y" {{(old('is_indg', $m->is_indg) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                                    <option value="N" {{(old('is_indg', $m->is_indg) == 'N') ? 'selected' : ''}}>No</option>
                                                                </select>
                                                            </div>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <h6 class="text-center">List is currently empty.</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{route('disaster_storemember', $d->id)}}" method="POST">
    @csrf
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Add Family Member</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                                <input type="text" class="form-control" id="lname" name="lname" value="{{old('lname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{old('fname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" minlength="2" maxlength="50" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="suffix">Name Extension <small>(ex. JR, SR, II, III, etc.)</small></label>
                                <input type="text" class="form-control" id="suffix" name="suffix" value="{{old('suffix')}}" minlength="2" maxlength="6" pattern="[A-Za-z\- 'Ññ]+" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender"><b class="text-danger">*</b>Sex</label>
                                <select class="form-control" name="sex" id="sex" required>
                                  <option value="" disabled {{(is_null(old('sex'))) ? 'selected' : ''}}>Choose...</option>
                                  <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                  <option value="F" {{(old('sex') == 'F') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="relationship_tohead"><span class="text-danger font-weight-bold">*</span>Relationship to the Family Head</label>
                                <select class="form-control" name="relationship_tohead" id="relationship_tohead" required>
                                    <option value="" disabled {{(is_null(old('relationship_tohead'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="SPOUSE" {{(old('relationship_tohead') == 'SPOUSE') ? 'selected' : ''}}>Spouse/Asawa</option>
                                    <option value="CHILD" {{(old('relationship_tohead') == 'CHILD') ? 'selected' : ''}}>Child</option>
                                    <option value="GRANDCHILDREN" {{(old('relationship_tohead') == 'GRANDCHILDREN') ? 'selected' : ''}}>Grandchildren</option>
                                    <option value="OTHERS" {{(old('relationship_tohead') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="highest_education"><span class="text-danger font-weight-bold">*</span>Highest Educational Attainment</label>
                                <select class="form-control" name="highest_education" id="highest_education" required>
                                    <option value="" disabled {{(is_null(old('highest_education'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="NO FORMAL EDUCATION" {{(old('highest_education') == 'NO FORMAL EDUCATION') ? 'selected' : ''}}>No Formal Education/Hindi nakapag-aral</option>
                                    <option value="ELEMENTARY UNDERGRADUATE" {{(old('highest_education') == 'ELEMENTARY UNDERGRADUATE') ? 'selected' : ''}}>Elementary Undergraduate</option>
                                    <option value="ELEMENTARY GRADUATE" {{(old('highest_education') == 'ELEMENTARY GRADUATE') ? 'selected' : ''}}>Elementary Graduate</option>
                                    <option value="JUNIOR HIGH SCHOOL UNDERGRADUATE" {{(old('highest_education') == 'JUNIOR HIGH SCHOOL UNDERGRADUATE') ? 'selected' : ''}}>Junior High School Undergraduate</option>
                                    <option value="JUNIOR HIGH SCHOOL GRADUATE" {{(old('highest_education') == 'JUNIOR HIGH SCHOOL GRADUATE') ? 'selected' : ''}}>Junior High School Graduate</option>
                                    <option value="SENIOR HIGH SCHOOL UNDERGRADUATE" {{(old('highest_education') == 'SENIOR HIGH SCHOOL UNDERGRADUATE') ? 'selected' : ''}}>Senior High School Undergraduate</option>
                                    <option value="SENIOR HIGH SCHOOL GRADUATE" {{(old('highest_education') == 'SENIOR HIGH SCHOOL GRADUATE') ? 'selected' : ''}}>Senior High School Graduate</option>
                                    <option value="COLLEGE UNDERGRADUATE" {{(old('highest_education') == 'COLLEGE UNDERGRADUATE') ? 'selected' : ''}}>College Undergraduate</option>
                                    <option value="COLLEGE GRADUATE" {{(old('highest_education') == 'COLLEGE GRADUATE') ? 'selected' : ''}}>College Graduate</option>
                                    <option value="MASTERS DEGREE" {{(old('highest_education') == 'MASTERS DEGREE') ? 'selected' : ''}}>Masters Degree</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="occupation">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_4ps"><span class="text-danger font-weight-bold">*</span>Is 4Ps</label>
                                <select class="form-control" name="is_4ps" id="is_4ps" required>
                                    <option value="" {{(is_null(old('is_4ps'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_4ps') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_4ps') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_indg"><span class="text-danger font-weight-bold">*</span>Is Indigent</label>
                                <select class="form-control" name="is_indg" id="is_indg" required>
                                    <option value="" {{(is_null(old('is_indg'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_indg') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_indg') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
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
    //Default Values for Gentri
    var regionDefault = {{$d->brgy->city->province->region->id}};
    var provinceDefault = {{$d->brgy->city->province->id}};
    var cityDefault = {{$d->brgy->city->id}};
    var brgyDefault = {{$d->brgy->id}}

    $('#address_region_code').change(function (e) { 
        e.preventDefault();

        var regionId = $(this).val();

        if (regionId) {
            $('#address_province_code').prop('disabled', false);
            $('#address_muncity_code').prop('disabled', true);
            $('#address_brgy_code').prop('disabled', true);

            $('#address_province_code').empty();
            $('#address_muncity_code').empty();
            $('#address_brgy_code').empty();

            $.ajax({
                url: '/ga/province/' + regionId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#address_province_code').empty();
                    $('#address_province_code').append('<option value="" disabled selected>Select Province</option>');

                    let sortedData = Object.entries(data).sort((a, b) => {
                        return a[1].localeCompare(b[1]); // Compare province names (values)
                    });

                    $.each(sortedData, function(key, value) {
                        $('#address_province_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                    });
                }
            });
        } else {
            $('#address_province_code').empty();
        }
    });

    $('#address_province_code').change(function (e) { 
        e.preventDefault();

        var provinceId = $(this).val();

        if (provinceId) {
            $('#address_province_code').prop('disabled', false);
            $('#address_muncity_code').prop('disabled', false);
            $('#address_brgy_code').prop('disabled', true);

            $('#address_muncity_code').empty();
            $('#address_brgy_code').empty();

            $.ajax({
                url: '/ga/city/' + provinceId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#address_muncity_code').empty();
                    $('#address_muncity_code').append('<option value="" disabled selected>Select City/Municipality</option>');
                    
                    let sortedData = Object.entries(data).sort((a, b) => {
                        return a[1].localeCompare(b[1]); // Compare province names (values)
                    });

                    $.each(sortedData, function(key, value) {
                        $('#address_muncity_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                    });
                }
            });
        } else {
            $('#address_muncity_code').empty();
        }
    });

    $('#address_muncity_code').change(function (e) { 
        e.preventDefault();

        var cityId = $(this).val();

        if (cityId) {
            $('#address_province_code').prop('disabled', false);
            $('#address_muncity_code').prop('disabled', false);
            $('#address_brgy_code').prop('disabled', false);

            $('#address_brgy_code').empty();

            $.ajax({
                url: '/ga/brgy/' + cityId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#address_brgy_code').empty();
                    $('#address_brgy_code').append('<option value="" disabled selected>Select Barangay</option>');

                    let sortedData = Object.entries(data).sort((a, b) => {
                        return a[1].localeCompare(b[1]); // Compare province names (values)
                    });

                    $.each(sortedData, function(key, value) {
                        $('#address_brgy_code').append('<option value="' + value[0] + '">' + value[1] + '</option>');
                    });
                }
            });
        } else {
            $('#address_brgy_code').empty();
        }
    });

    if ($('#address_region_code').val()) {
        $('#address_region_code').trigger('change'); // Automatically load provinces on page load
    }

    if (provinceDefault) {
        setTimeout(function() {
            $('#address_province_code').val(provinceDefault).trigger('change');
        }, 1500); // Slight delay to ensure province is loaded
    }
    if (cityDefault) {
        setTimeout(function() {
            $('#address_muncity_code').val(cityDefault).trigger('change');
        }, 2500); // Slight delay to ensure city is loaded
    }
    if (brgyDefault) {
        setTimeout(function() {
            $('#address_brgy_code').val(brgyDefault).trigger('change');
        }, 3500); // Slight delay to ensure city is loaded
    }
</script>
@endsection