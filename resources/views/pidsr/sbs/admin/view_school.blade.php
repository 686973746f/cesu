@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>{{$s->name}}</b></div>
                <div>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modelId">
                      Launch
                    </button>
                    
                    <!-- Modal -->
                    
                </div>
            </div>
        </div>
        <div class="card-body">

        </div>
    </div>
</div>

<form action="{{route('sbs_createlevel', $s->id)}}" method="POST">
    @csrf
    <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Level</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="type"><span class="text-danger font-weight-bold">*</span>Add to Type</label>
                        <select class="form-control" name="type" id="type" required>
                            <option value="" disabled {{(is_null(old('type'))) ? 'selected' : ''}}>Choose...</option>
                            @if(in_array("ES", explode(", ", $s->school_type)))
                            <option value="ES" {{(old('type') == 'ES') ? 'selected' : ''}}>Elementary School</option>
                            @endif
                            @if(in_array("JHS", explode(", ", $s->school_type)))
                            <option value="JHS" {{(old('type') == 'JHS') ? 'selected' : ''}}>Junior High School</option>
                            @endif
                            @if(in_array("SHS", explode(", ", $s->school_type)))
                            <option value="SHS" {{(old('type') == 'SHS') ? 'selected' : ''}}>Senior High School</option>
                            @endif
                            @if(in_array("COLLEGE", explode(", ", $s->school_type)))
                            <option value="COLLEGE" {{(old('type') == 'COLLEGE') ? 'selected' : ''}}>College</option>
                            @endif
                            @if(in_array("VOCATIONAL", explode(", ", $s->school_type)))
                            <option value="VOCATIONAL" {{(old('type') == 'VOCATIONAL') ? 'selected' : ''}}>Vocational</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="level_name"><b class="text-danger">*</b>Grade Level Name</label>
                        <input type="text" class="form-control" name="level_name" id="level_name" value="{{old('level_name')}}" style="text-transform: uppercase;" placeholder="ex. Grade X | Grade Y | Course" required>
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