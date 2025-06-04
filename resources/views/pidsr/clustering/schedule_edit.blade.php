@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('dengue_clustering_update', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>Edit Schedule</b> - ID: {{$d->id}}</div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="form-group">
                  <label for="enabled"><b class="text-danger">*</b>Enabled</label>
                  <input type="number" class="form-control" name="enabled" id="enabled" min="0" max="1" value="{{$d->enabled}}" required>
                </div>
                <div id="enabled_div" class="d-none">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purok_subdivision"><b class="text-danger">*</b>Override Area Name</label>
                                <input type="text" class="form-control" name="purok_subdivision" id="purok_subdivision" value="{{old('purok_subdivision', $d->purok_subdivision)}}" style="text-transform: uppercase" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brgy_id"><b class="text-danger">*</b>Override Barangay</label>
                                <select class="form-control" name="brgy_id" id="brgy_id" required>
                                    @foreach($brgy_list as $b)
                                    <option value="{{$b->id}}" {{($b->id == old('brgy_id', $d->brgy_id)) ? 'selected' : ''}}>{{$b->alt_name ?: $b->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_team"><b class="text-danger">*</b>Responsible Team</label>
                                <select class="form-control" name="assigned_team" id="assigned_team" required>
                                    <option value="" disabled {{(is_null(old('assigned_team', $d->assigned_team))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="CHO" {{((old('assigned_team', $d->assigned_team)) == 'CHO') ? 'selected' : ''}}>CHO</option>
                                    <option value="CENRO" {{((old('assigned_team', $d->assigned_team)) == 'CENRO') ? 'selected' : ''}}>CENRO</option>
                                    <option value="GSO" {{((old('assigned_team', $d->assigned_team)) == 'GSO') ? 'selected' : ''}}>GSO</option>
                                    <option value="CHO, CENRO, and GSO" {{((old('assigned_team', $d->assigned_team)) == 'CHO, CENRO, and GSO') ? 'selected' : ''}}>CHO, CENRO, and GSO</option>
                                    <option value="DOH REGIONAL" {{((old('assigned_team', $d->assigned_team)) == 'DOH REGIONAL') ? 'selected' : ''}}>DOH REGIONAL</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status"><b class="text-danger">*</b>Status</label>
                                <select class="form-control" name="status" id="status" required>
                                    @if($d->status == 'PENDING')
                                    <option value="PENDING" {{(old('status', $d->status) == 'PENDING') ? 'selected' : ''}}>PENDING</option>
                                    <option value="CYCLE1" {{(old('status', $d->status) == 'CYCLE1') ? 'selected' : ''}}>1ST CYCLE DONE</option>
                                    @else
                                    <option value="PENDING" {{(old('status', $d->status) == 'PENDING') ? 'selected' : ''}}>PENDING</option>
                                    <option value="CYCLE1" {{(old('status', $d->status) == 'CYCLE1') ? 'selected' : ''}}>1ST CYCLE DONE</option>
                                    <option value="CYCLE2" {{(old('status', $d->status) == 'CYCLE2') ? 'selected' : ''}}>2ND CYCLE DONE</option>
                                    <option value="CYCLE3" {{(old('status', $d->status) == 'CYCLE3') ? 'selected' : ''}}>3RD CYCLE DONE</option>
                                    <option value="CYCLE4" {{(old('status', $d->status) == 'CYCLE4') ? 'selected' : ''}}>4TH CYCLE DONE (COMPLETED)</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    @if($d->status == 'PENDING')
                    <div class="form-group">
                        <label for="cycle1_date">1st Cycle Date</label>
                        <input type="datetime-local" class="form-control" name="cycle1_date" id="cycle1_date" value="{{old('cycle1_date', $d->cycle1_date)}}" aria-describedby="cycle1_date">
                    </div>
                    @else
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cycle1_date">1st Cycle Date</label>
                                <input type="datetime-local" class="form-control" name="cycle1_date" id="cycle1_date" value="{{old('cycle1_date', $d->cycle1_date)}}" aria-describedby="cycle1_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cycle2_date">2nd Cycle Date</label>
                                <input type="datetime-local" class="form-control" name="cycle2_date" id="cycle2_date" value="{{old('cycle2_date', $d->cycle2_date)}}" aria-describedby="cycle2_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cycle3_date">3rd Cycle Date</label>
                                <input type="datetime-local" class="form-control" name="cycle3_date" id="cycle3_date" value="{{old('cycle3_date', $d->cycle3_date)}}" aria-describedby="cycle3_date">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="cycle4_date">4th Cycle Date</label>
                                <input type="datetime-local" class="form-control" name="cycle4_date" id="cycle4_date" value="{{old('cycle4_date', $d->cycle4_date)}}" aria-describedby="cycle4_date">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block" id="submitBtn">Save (CTRL + S)</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).bind('keydown', function(e) {
        if(e.ctrlKey && (e.which == 83)) {
            e.preventDefault();
            $('#submitBtn').trigger('click');
            $('#submitBtn').prop('disabled', true);
            setTimeout(function() {
                $('#submitBtn').prop('disabled', false);
            }, 2000);
            return false;
        }
    });

    $('#enabled').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 1) {
            $('#assigned_team').prop('required', true);
            $('#enabled_div').removeClass('d-none');
        }
        else {
            $('#assigned_team').prop('required', false);
            $('#enabled_div').addClass('d-none');
        }
    }).trigger('change');

    $('#status').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'PENDING') {
            $('#cycle1_date').prop('required', false);
        }
        else if($(this).val() == 'CYCLE1') {
            $('#cycle1_date').prop('required', true);

            @if($d->status != 'PENDING')
            $('#cycle2_date').prop('required', false);
            $('#cycle3_date').prop('required', false);
            $('#cycle4_date').prop('required', false);
            @endif
        }
        else if($(this).val() == 'CYCLE2') {
            $('#cycle1_date').prop('required', true);

            $('#cycle2_date').prop('required', true);
            $('#cycle3_date').prop('required', false);
            $('#cycle4_date').prop('required', false);
        }
        else if($(this).val() == 'CYCLE3') {
            $('#cycle1_date').prop('required', true);

            $('#cycle2_date').prop('required', true);
            $('#cycle3_date').prop('required', true);
            $('#cycle4_date').prop('required', false);
        }
        else if($(this).val() == 'CYCLE4') {
            $('#cycle1_date').prop('required', true);

            $('#cycle2_date').prop('required', true);
            $('#cycle3_date').prop('required', true);
            $('#cycle4_date').prop('required', true);
        }
    }).trigger('change');
</script>
@endsection