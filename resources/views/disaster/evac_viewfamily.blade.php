@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Family Head</b></div>
        <div class="card-body">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div><b>Family Members inside Evacuation</b></div>
                        <div>
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#linkMember">
                              Add Family Member
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <div class="form-group">
                        <label for="member_id"><b class="text-danger">*</b>Select Family Member to Add</label>
                        <select class="form-control" name="member_id" id="member_id" required>
                        <option value="" disabled {{(is_null(old('member_id'))) ? 'selected' : ''}}>Choose...</option>
                        @foreach($available_list as $l)
                        <option value="{{$l->id}}">{{$l->getName()}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_registered"><b class="text-danger">*</b>Date Registered</label>
                        <input type="datetime-local" class="form-control" name="date_registered" id="date_registered" value="{{old('date_registered', date('Y-m-d H:i'))}}" required>
                    </div>

                    <div class="row">
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
                            <option value="" disabled {{(is_null(old('outcome'))) ? 'selected' : ''}}>Choose...</option>
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

<script>
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
</script>
@endsection