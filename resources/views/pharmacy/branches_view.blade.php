@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('pharmacy_update_branch', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>View Branch/Entity</b></div>
                    <div>Created At / By: {{date('m/d/Y h:i A', strtotime($d->created_at))}} / {{$d->user->name}} {{($d->getUpdatedBy()) ? '| Updated At / By: '.date('m/d/Y h:i A', strtotime($d->updated_at)).' / '.$d->getUpdatedBy->name : ''}}</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                  <label for="name"><b class="text-danger">*</b>Branch/Entity Name</label>
                  <input type="text" class="form-control" name="name" id="name" value="{{old('name', $d->name)}}" required>
                </div>
                <div class="form-group">
                    <label for="focal_person">Focal Person</label>
                    <input type="text" class="form-control" name="focal_person" id="focal_person" value="{{old('focal_person', $d->focal_person)}}">
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" name="contact_number" id="contact_number" value="{{old('contact_number', $d->contact_number)}}">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" id="description" value="{{old('description', $d->description)}}">
                </div>
                <div class="form-group">
                  <label for="level"><b class="text-danger">*</b>Level</label>
                  <select class="form-control" name="level" id="level">
                    <option value="1" {{(old('level', $d->level) == 1) ? 'selected' : ''}}>Level 1 - Main Entity</option>
                    <option value="2" {{(old('level', $d->level) == 2) ? 'selected' : ''}}>Level 2 - Sub Entity</option>
                    <!--<option value="3" {{(old('level', $d->level) == 3) ? 'selected' : ''}}>Level 3 - Outide GenTri</option>-->
                  </select>
                </div>
                <hr>
                <div class="form-group">
                    <label for="if_bhs_id">IF BHS, Link to BHS ID</label>
                    <select class="form-control" name="if_bhs_id" id="if_bhs_id">
                        <option value="" {{(is_null(old('if_bhs_id', $d->if_bhs_id))) ? 'selected' : ''}}>Not a BHS</option>
                        @foreach($bhs_list as $bhs)
                        <option value="{{$bhs->id}}" {{(old('if_bhs_id', $d->if_bhs_id) == $bhs->id) ? 'selected' : ''}}>{{$bhs->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
    $("#if_bhs_id").select2({
        theme: 'bootstrap',
    });
</script>
@endsection