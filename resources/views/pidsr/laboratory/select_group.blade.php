@extends('layouts.app')

@section('content')
    <form action="{{route('pidsr_laboratory_linkedcs_process')}}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header">Link EDCS to Specimen</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="edcs_cid"><b class="text-danger">*</b>Selected EDCS Case ID:</label>
                                <input type="text" class="form-control" name="edcs_cid" id="edcs_cid" value="{{request()->input('case_id')}}" required readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="disease"><b class="text-danger">*</b>Case:</label>
                                <input type="text" class="form-control" name="disease" id="disease" value="{{request()->input('disease')}}" required readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                      <label for="group_id"><b class="text-danger">*</b>Link to Open Case:</label>
                      <select class="form-control" name="group_id" id="group_id">
                        <option value="" disabled selected>Choose...</option>
                        @foreach($list as $l)
                        <option value="{{$l->id}}">{{$l->title}} - {{$l->disease_tag}}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </div>
        </div>
    </form>
@endsection