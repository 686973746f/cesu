@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <div><b>{{$d->evacuationcenter->disaster->name}}</b></div>
                    <div><b>Evacuation Center ({{$d->evacuationCenter->name}}): View Family Head</b></div>
                    <div>{{$d->familyHead->getName()}}</div>
                </div>
                <div></div>
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif

            <form action="{{route('disaster_updateevacfamily', [$d->evacuationCenter->id, $d->id])}}" method="POST">
                @csrf
                <div class="card mb-3">
                    <div class="card-header"><b>Family Head Status</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_registered"><b class="text-danger">*</b>Date Registered</label>
                                    <input type="datetime-local" class="form-control" name="date_registered" id="date_registered" value="{{old('date_registered', $d->date_registered)}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="family_status"><b class="text-danger">*</b>Status</label>
                                    <select class="form-control" name="family_status" id="family_status" required>
                                        <option value="ACTIVE" {{(old('family_status', $d->family_status) == 'ACTIVE') ? 'selected' : ''}}>Active</option>
                                        <option value="RETURNED" {{(old('family_status', $d->family_status) == 'RETURNED') ? 'selected' : ''}}>Returned Home</option>
                                    </select>
                                </div>
                                <div class="form-group" id="returnedhome_div">
                                  <label for="date_returnedhome"><b class="text-danger">*</b>Date Returned Home</label>
                                  <input type="datetime-local" class="form-control" name="date_returnedhome" id="date_returnedhome" value="{{old('date_returnedhome', $d->date_returnedhome ?: date('Y-m-d\TH:i'))}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_injured"><b class="text-danger">*</b>Is Injured?</label>
                                    <select class="form-control" name="is_injured" id="is_injured1" required>
                                        <option value="Y" {{(old('is_injured', $d->is_injured) == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('is_injured', $d->is_injured) == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_admitted"><b class="text-danger">*</b>Is Admitted?</label>
                                    <select class="form-control" name="is_admitted" id="is_admitted1" required>
                                        <option value="Y" {{(old('is_admitted', $d->is_admitted) == 'Y') ? 'selected' : ''}}>Yes</option>
                                        <option value="N" {{(old('is_admitted', $d->is_admitted) == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="admitted_div1" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_admitted"><b class="text-danger">*</b>Date Admitted</label>
                                        <input type="date" class="form-control" name="date_admitted" id="date_admitted1" max="{{date('Y-m-d')}}" value="{{old('date_admitted', $d->date_admitted)}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_discharged">Date Discharged</label>
                                        <input type="date" class="form-control" name="date_discharged" id="date_discharged1" max="{{date('Y-m-d', strtotime('+1 Day'))}}" value="{{old('date_discharged', $d->date_discharged)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shelterdamage_classification"><b class="text-danger">*</b>Shelter Damage Classification</label>
                                    <select class="form-control" name="shelterdamage_classification" id="shelterdamage_classification" required>
                                        <option value="PARTIALLY DAMAGED" {{(old('shelterdamage_classification', $d->shelterdamage_classification) == 'PARTIALLY DAMAGED') ? 'selected' : ''}}>Partially Damaged</option>
                                        <option value="TOTALLY DAMAGED" {{(old('shelterdamage_classification', $d->shelterdamage_classification) == 'TOTALLY DAMAGED') ? 'selected' : ''}}>Totally Damaged</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="evac_type"><b class="text-danger">*</b>Evacuee Type</label>
                                    <select class="form-control" name="evac_type" id="evac_type" required>
                                        <option value="PREEMPTIVE" {{(old('evac_type', $d->evac_type) == 'PREEMPTIVE') ? 'selected' : ''}}>Pre-Emptive Evacuation</option>
                                        <option value="FORCED" {{(old('evac_type', $d->evac_type) == 'FORCED') ? 'selected' : ''}}>Forced Evacuation</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="outcome"><b class="text-danger">*</b>Outcome</label>
                                    <select class="form-control" name="outcome" id="outcome1" required>
                                        <option value="ALIVE" {{(old('outcome', $d->outcome) == 'ALIVE') ? 'selected' : ''}}>Alive</option>
                                        <option value="MISSING" {{(old('outcome', $d->outcome) == 'MISSING') ? 'selected' : ''}}>Missing</option>
                                        <option value="MISSING THEN RETURNED" {{(old('outcome', $d->outcome) == 'MISSING THEN RETURNED') ? 'selected' : ''}}>Missing, then Returned</option>
                                        <option value="DIED" {{(old('outcome', $d->outcome) == 'DIED') ? 'selected' : ''}}>Died</option>
                                    </select>
                                </div>
                                <div id="missing_div1" class="d-none">
                                    <div class="form-group">
                                        <label for="date_missing"><b class="text-danger">*</b>Date Missing</label>
                                        <input type="datetime-local" class="form-control" name="date_missing" id="date_missing1" value="{{old('date_missing', $d->date_missing)}}">
                                    </div>
                                    <div class="form-group d-none" id="return_div1">
                                        <label for="date_returned"><b class="text-danger">*</b>Date Returned</label>
                                        <input type="datetime-local" class="form-control" name="date_returned" id="date_returned1" value="{{old('date_returned', $d->date_returned)}}">
                                    </div>
                                </div>
                                <div id="died_div1" class="d-none">
                                    <div class="form-group">
                                        <label for="date_died"><b class="text-danger">*</b>Date Died</label>
                                        <input type="datetime-local" class="form-control" name="date_died" id="date_died1" value="{{old('date_died', $d->date_died)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="focal_name">Name of C/DSWD Focal</label>
                                    <input type="text" class="form-control" name="focal_name" id="focal_name" style="text-transform: uppercase;" value="{{old('focal_name', $d->focal_name)}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supervisor_name">Name of C/DSWD Immediate Supervisor</label>
                                    <input type="text" class="form-control" name="supervisor_name" id="supervisor_name" style="text-transform: uppercase;" value="{{old('focal_name', $d->supervisor_name)}}">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="remarks">Remarks</label>
                          <textarea class="form-control" name="remarks" id="remarks" rows="3">{{old('remarks', $d->remarks)}}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div><b>Family Member(s) who are with the Family Head inside the Evacuation</b></div>
                        <div>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#linkMember">
                              Link Family Member
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($list->count() != 0)
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Is Pregnant</th>
                                <th>Is Lactating</th>
                                <th>Relationship to Family Head</th>
                                <th>Highest Education</th>
                                <th>Outcome</th>
                                <th>Added at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $ind => $m)
                            <tr>
                                <td class="text-center">{{$ind + 1}}</td>
                                <td><a href="">{{$m->member->getName()}}</a></td>
                                <td class="text-center">{{$m->member->getAge()}}</td>
                                <td class="text-center">{{$m->member->sex}}</td>
                                <td class="text-center">{{$m->is_pregnant}}</td>
                                <td class="text-center">{{$m->is_lactating}}</td>
                                <td class="text-center">{{$m->member->relationship_tohead}}</td>
                                <td class="text-center">{{$m->member->highest_education}}</td>
                                <td class="text-center">{{$m->outcome}}</td>
                                <td class="text-center">{{date('m/d/Y h:i A', strtotime($m->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h6 class="text-center">List is currently empty.</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($available_list->count() != 0)
<form action="{{route('disaster_linkmembertoevac', [$d->evacuation_center_id, $d->id])}}" method="POST">
    @csrf
    <div class="modal fade" id="linkMember" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Link Member to Evacuation Center</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    @if($available_list->count() > 0)
                    <div class="form-group" id="member_id_div">
                        <label for="member_id"><b class="text-danger">*</b>Select Family Member to Add</label>
                        <select class="form-control" name="member_id" id="member_id" required>
                        <option value="" disabled {{(is_null(old('member_id'))) ? 'selected' : ''}}>Choose...</option>
                        @foreach($available_list as $l)
                        <option value="{{$l->id}}" data-gender="{{ $l->sex }}">{{$l->getName()}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_registered"><b class="text-danger">*</b>Date Registered</label>
                                <input type="datetime-local" class="form-control" name="date_registered" id="date_registered" value="{{old('date_registered', $d->date_registered)}}" max="{{ date('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_injured"><b class="text-danger">*</b>Is Injured?</label>
                                <select class="form-control" name="is_injured" id="is_injured" required>
                                    <option value="" disabled {{(is_null(old('is_injured'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="female_extra_div" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_pregnant"><b class="text-danger">*</b>Is Pregnant?</label>
                                    <select class="form-control" name="is_pregnant" id="is_pregnant">
                                    <option value="" disabled {{(is_null(old('is_pregnant'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_pregnant') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_pregnant') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_lactating"><b class="text-danger">*</b>Is Lactating?</label>
                                    <select class="form-control" name="is_lactating" id="is_lactating">
                                    <option value="" disabled {{(is_null(old('is_lactating'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_lactating') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_lactating') == 'N') ? 'selected' : ''}}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>      
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_pwd"><b class="text-danger">*</b>Is PWD?</label>
                                <select class="form-control" name="is_pwd" id="is_pwd" required>
                                    <option value="" disabled {{(is_null(old('is_pwd'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y" {{(old('is_pwd') == 'Y') ? 'selected' : ''}}>Yes</option>
                                    <option value="N" {{(old('is_pwd') == 'N') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_admitted"><b class="text-danger">*</b>Is Admitted?</label>
                                <select class="form-control" name="is_admitted" id="is_admitted" required>
                                    <option value="" disabled {{(is_null(old('is_admitted'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="admitted_div" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_admitted"><b class="text-danger">*</b>Date Admitted</label>
                                    <input type="date" class="form-control" name="date_admitted" id="date_admitted" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_discharged">Date Discharged</label>
                                    <input type="date" class="form-control" name="date_discharged" id="date_discharged" max="{{date('Y-m-d', strtotime('+1 Day'))}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="outcome"><b class="text-danger">*</b>Outcome</label>
                        <select class="form-control" name="outcome" id="outcome" required>
                            <option value="ALIVE">Alive</option>
                            <option value="MISSING">Missing</option>
                            <option value="MISSING THEN RETURNED">Missing, then Returned</option>
                            <option value="DIED">Died</option>
                        </select>
                    </div>
                    <div id="missing_div" class="d-none">
                        <div class="form-group">
                            <label for="date_missing"><b class="text-danger">*</b>Date Missing</label>
                            <input type="datetime-local" class="form-control" name="date_missing" id="date_missing">
                        </div>
                        <div class="form-group d-none" id="return_div">
                            <label for="date_returned"><b class="text-danger">*</b>Date Returned</label>
                            <input type="datetime-local" class="form-control" name="date_returned" id="date_returned">
                        </div>
                    </div>
                    <div id="died_div" class="d-none">
                        <div class="form-group">
                            <label for="date_died"><b class="text-danger">*</b>Date Died</label>
                            <input type="datetime-local" class="form-control" name="date_died" id="date_died">
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning" role="alert">
                        <h5><b>There are no Family Members available to link.</b></h5>
                        <hr>
                        <h6>To create and link a new member data to this family, you may proceed to <b><a href="{{route('disaster_viewfamilyhead', $d->familyhead_id)}}">View {{ucwords(strtolower($d->familyhead->getName()))}} Family Data</a></b></h6>
                    </div>
                    @endif
                </div>
                @if($available_list->count() > 0)
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Save</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</form>
@else
<div class="modal fade" id="linkMember" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Link Member to Evacuation Center</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info" role="alert">
                    Family Head doesn't have family members data linked yet. To add, click <a href="{{route('disaster_viewfamilyhead', $d->familyhead_id)}}">here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    $('#family_status').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'RETURNED') {
            $('#returnedhome_div').removeClass('d-none');
            $('#date_returnedhome').prop('required', true);
        }
        else {
            $('#returnedhome_div').addClass('d-none');
            $('#date_returnedhome').prop('required', false);
        }
    }).trigger('change');

    $('#member_id').select2({
        theme: 'bootstrap',
        dropdownParent: $('#member_id_div'),
    });

    $('#is_admitted').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#admitted_div').removeClass('d-none');
            $('#date_admitted').prop('required', true);
        }
        else {
            $('#admitted_div').addClass('d-none');
            $('#date_admitted').prop('required', false);
        }
    }).trigger('change');

    $('#outcome').change(function (e) { 
        e.preventDefault();
        
        $('#missing_div').addClass('d-none');
        $('#died_div').addClass('d-none');
        $('#return_div').addClass('d-none');

        $('#date_died').prop('required', false);
        $('#date_missing').prop('required', false);
        $('#date_returned').prop('required', false);
        if($(this).val() == 'DIED') {
            $('#died_div').removeClass('d-none');
            $('#date_died').prop('required', true);
        }
        else if($(this).val() == 'MISSING') {
            $('#missing_div').removeClass('d-none');
            $('#date_missing').prop('required', true);
        }
        else if($(this).val() == 'MISSING THEN RETURNED') {
            $('#missing_div').removeClass('d-none');
            $('#return_div').removeClass('d-none');
            $('#date_missing').prop('required', true);
            $('#date_returned').prop('required', true);
        }
    }).trigger('change');

    $('#is_admitted1').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'Y') {
            $('#admitted_div1').removeClass('d-none');
            $('#date_admitted1').prop('required', true);
        }
        else {
            $('#admitted_div1').addClass('d-none');
            $('#date_admitted1').prop('required', false);
        }
    }).trigger('change');

    $('#outcome1').change(function (e) { 
        e.preventDefault();
        
        $('#missing_div1').addClass('d-none');
        $('#died_div1').addClass('d-none');
        $('#return_div1').addClass('d-none');

        $('#date_died1').prop('required', false);
        $('#date_missing1').prop('required', false);
        $('#date_returned1').prop('required', false);
        if($(this).val() == 'DIED') {
            $('#died_div1').removeClass('d-none');
            $('#date_died1').prop('required', true);
        }
        else if($(this).val() == 'MISSING') {
            $('#missing_div1').removeClass('d-none');
            $('#date_missing1').prop('required', true);
        }
        else if($(this).val() == 'MISSING THEN RETURNED') {
            $('#missing_div1').removeClass('d-none');
            $('#return_div1').removeClass('d-none');
            $('#date_missing1').prop('required', true);
            $('#date_returned1').prop('required', true);
        }
    }).trigger('change');

    $('#member_id').on('change', function() {
        var gender = $(this).find(':selected').data('gender');
        
        if (gender && gender.toLowerCase() === 'f') {
            $('#female_extra_div').slideDown();
            $('#is_pregnant').prop('required', true);
            $('#is_lactating').prop('required', true);
        } else {
            $('#female_extra_div').slideUp();
            $('#is_pregnant').prop('required', false);
            $('#is_lactating').prop('required', false);
        }
    });
</script>
@endsection