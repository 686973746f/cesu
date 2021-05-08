@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('interviewers.store')}}" method="POST" autocomplete="off">
            @csrf
            <div class="card">
                <div class="card-header">Add Interviewer</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
                              <input type="text" class="form-control" name="lname" value="{{old('lname')}}" required autofocus>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name</label>
                                <input type="text" class="form-control" name="fname" value="{{old('fname')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" value="{{old('mname')}}" name="mname">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="desc">Description</label>
                                <input type="text" class="form-control" value="{{old('desc')}}" name="desc">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brgy_id"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                <select class="form-control" name="brgy_id" id="brgy_id">
                                    <option value="" {{(empty(old('brgy_id'))) ? 'selected' : ''}}>N/A</option>
                                    @foreach($list as $item)
                                      <option value="{{$item->id}}" {{(old('brgy_id') == $item->id)}}>{{$item->brgyName}}</option>
                                    @endforeach
                                </select>
                              </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection