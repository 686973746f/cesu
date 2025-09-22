@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Evacuation Centers List of Families</b></div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newFamilyHead">
                          Launch
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
            </div>
        </div>
    </div>

    <form action="{{route('disaster_storefamilyhead')}}" method="POST">
        @csrf
        <div class="modal fade" id="newFamilyHead" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Add Family Head</b></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info" role="alert">
                            <b class="text-danger">Note:</b> All fields marked with an asterisk (<b class="text-danger">*</b>) are required to be filled-out properly.
                        </div>

                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="cswd_serialno">Serial No.</label>
                                <input type="text" class="form-control" name="cswd_serialno" id="cswd_serialno" value="{{old('cswd_serialno')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                        <hr>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                    <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="birthplace"><b class="text-danger">*</b>Birthplace</label>
                                <input type="text" class="form-control" name="birthplace" id="birthplace" value="{{old('birthplace')}}" style="text-transform: uppercase" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender"><span class="text-danger font-weight-bold">*</span>Sex</label>
                                    <select class="form-control" name="sex" id="sex" required>
                                    <option value="" disabled {{(is_null(old('sex'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="M" {{(old('sex') == 'M') ? 'selected' : ''}}>Male</option>
                                    <option value="F" {{(old('sex') == 'F') ? 'selected' : ''}}>Female</option>
                                    </select>
                                </div>
                                <div id="femaleDiv" class="d-none">
                                    <div class="form-group">
                                        <label for="is_pregnant"><b class="text-danger">*</b>Is Pregnant?</label>
                                        <select class="form-control" name="is_pregnant" id="is_pregnant">
                                        <option value="" disabled {{(is_null(old('is_pregnant'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('is_pregnant') == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('is_pregnant') == 'N') ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_lactating"><b class="text-danger">*</b>Is Lactating?</label>
                                        <select class="form-control" name="is_lactating" id="is_lactating">
                                        <option value="" disabled {{(is_null(old('is_lactating'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="Y" {{(old('is_lactating', $p->is_lactating) == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('is_lactating', $p->is_lactating) == 'N') ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                    <select class="form-control" name="cs" id="cs" required>
                                    <option value="" disabled {{(is_null(old('cs', $p->cs))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="SINGLE" {{(old('cs', $p->cs) == 'SINGLE') ? 'selected' : ''}}>Single</option>
                                    <option value="MARRIED" {{(old('cs', $p->cs) == 'MARRIED') ? 'selected' : ''}}>Married</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="contact_number"><span class="text-danger font-weight-bold">*</span>Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{old('contact_number', $p->contact_number)}}" pattern="[0-9]{11}" placeholder="09*********" required>
                                </div>
                                <div class="form-group">
                                    <label for="contact_number2">Alternate Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number2" name="contact_number2" value="{{old('contact_number2', $p->contact_number2)}}" pattern="[0-9]{11}" placeholder="09*********">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="religion">Religion</label>
                                    <input type="text" class="form-control" id="religion" name="religion" value="{{old('religion', $p->religion)}}" style="text-transform: uppercase;">
                                </div>
                                <div class="form-group">
                                    <label for="occupation">Occupation</label>
                                    <input type="text" class="form-control" id="occupation" name="occupation" value="{{old('occupation', $p->occupation)}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_presented"><b class="text-danger">*</b>ID Card Presented</label>
                                    <input type="text" class="form-control" id="id_presented" name="id_presented" value="{{old('id_presented', $p->id_presented)}}" style="text-transform: uppercase;" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_number"><b class="text-danger">*</b>ID Card Number</label>
                                    <input type="text" class="form-control" id="id_number" name="id_number" value="{{old('id_number', $p->id_number)}}" style="text-transform: uppercase;" required>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header"><b>Permanent Address</b></div>
                            <div class="card-body">
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
                                            <select class="form-control" name="address_brgy_code" id="address_brgy_code" required disabled>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="street_purok"><b class="text-danger">*</b>House No., Street/Purok/Subdivision</label>
                                            <input type="text" class="form-control" id="street_purok" name="street_purok" value="{{old('street_purok', $p->street_purok)}}" style="text-transform: uppercase;" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="house_ownership"><span class="text-danger font-weight-bold">*</span>House Ownership</label>
                                    <select class="form-control" name="house_ownership" id="house_ownership" required>
                                        <option value="" disabled {{(is_null(old('house_ownership', $p->house_ownership))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="OWNER" {{(old('house_ownership', $p->house_ownership) == 'OWNER') ? 'selected' : ''}}>Owner</option>
                                        <option value="RENTER" {{(old('house_ownership', $p->house_ownership) == 'RENTER') ? 'selected' : ''}}>Renter</option>
                                        <option value="SHARER" {{(old('house_ownership', $p->house_ownership) == 'SHARER') ? 'selected' : ''}}>Sharer</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="shelterdamage_classification"><span class="text-danger font-weight-bold">*</span>Shelter Damage Classification</label>
                                    <select class="form-control" name="shelterdamage_classification" id="shelterdamage_classification" required>
                                        <option value="" disabled {{(is_null(old('shelterdamage_classification', $p->shelterdamage_classification))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="PARTIALLY DAMAGED" {{(old('shelterdamage_classification', $p->shelterdamage_classification) == 'PARTIALLY DAMAGED') ? 'selected' : ''}}>Partially Damaged</option>
                                        <option value="TOTALLY DAMAGED" {{(old('shelterdamage_classification', $p->shelterdamage_classification) == 'TOTALLY DAMAGED') ? 'selected' : ''}}>Totally Damaged</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="is_injured"><span class="text-danger font-weight-bold">*</span>Is Injured</label>
                                            <select class="form-control" name="is_injured" id="is_injured" required>
                                                <option value="" {{(is_null(old('is_injured', $p->is_injured))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="Y" {{(old('is_injured', $p->is_injured) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                <option value="N" {{(old('is_injured', $p->is_injured) == 'N') ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="is_pwd"><span class="text-danger font-weight-bold">*</span>Is PWD</label>
                                            <select class="form-control" name="is_pwd" id="is_pwd" required>
                                                <option value="" {{(is_null(old('is_pwd', $p->is_pwd))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="Y" {{(old('is_pwd', $p->is_pwd) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                <option value="N" {{(old('is_pwd', $p->is_pwd) == 'N') ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="is_4ps"><span class="text-danger font-weight-bold">*</span>Is 4Ps</label>
                                            <select class="form-control" name="is_4ps" id="is_4ps" required>
                                                <option value="" {{(is_null(old('is_4ps', $p->is_4ps))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="Y" {{(old('is_4ps', $p->is_4ps) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                <option value="N" {{(old('is_4ps', $p->is_4ps) == 'N') ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="is_indg"><span class="text-danger font-weight-bold">*</span>Is Indigent</label>
                                            <select class="form-control" name="is_indg" id="is_indg" required>
                                                <option value="" {{(is_null(old('is_indg', $p->is_indg))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="Y" {{(old('is_indg', $p->is_indg) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                <option value="N" {{(old('is_indg', $p->is_indg) == 'N') ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="outcome"><span class="text-danger font-weight-bold">*</span>Outcome</label>
                                    <select class="form-control" name="outcome" id="outcome" required>
                                    <option value="" disabled {{(is_null(old('outcome', $p->outcome))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="ALIVE" {{(old('outcome', $p->outcome) == 'ALIVE') ? 'selected' : ''}}>Alive</option>
                                    <option value="DIED" {{(old('outcome', $p->outcome) == 'DIED') ? 'selected' : ''}}>Died</option>
                                    <option value="MISSING" {{(old('outcome', $p->outcome) == 'MISSING') ? 'selected' : ''}}>Missing</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="family_status"><span class="text-danger font-weight-bold">*</span>Family Status</label>
                                    <select class="form-control" name="family_status" id="family_status" required>
                                    <option value="" disabled {{(is_null(old('family_status', $p->family_status))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="ACTIVE" {{(old('family_status', $p->family_status) == 'ACTIVE') ? 'selected' : ''}}>Active (Still on Evacuation Center)</option>
                                    <option value="WENT HOME" {{(old('family_status', $p->family_status) == 'WENT HOME') ? 'selected' : ''}}>Went Home</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="focal_name"><span class="text-danger font-weight-bold">*</span>Name of DSWD Focal</label>
                                    <select class="form-control" name="focal_name" id="focal_name" required>
                                    <option value="" disabled {{(is_null(old('focal_name', $p->focal_name))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="JUAN DELA CRUZ" {{(old('focal_name', $p->focal_name) == 'JUAN DELA CRUZ') ? 'selected' : ''}}>JUAN DELA CRUZ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">

                            </div>
                        </div>
                        <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks', $p->remarks)}}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection