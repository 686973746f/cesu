@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{route('adminpanel.brgy.update.user', ['brgy_id' => $brgy->id, 'user_id' => $user->id])}}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header">Edit User</div>
                        <div class="card-body">
                            @if(session('msg'))
                            <div class="alert alert-{{session('msgtype')}}" role="alert">
                                {{session('msg')}}
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="">User ID</label>
                                <input type="text" class="form-control" name="" id="" value="{{$user->id}}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="name"><span class="text-danger font-weight-bold">*</span>Name</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{old('name', $user->name)}}" required>
                            </div>
                            <div class="form-group">
                                <label for="email"><span class="text-danger font-weight-bold">*</span>Email</label>
                                <input type="email" class="form-control" name="email" id="email" value="{{old('email', $user->email)}}" required>
                            </div>
                            <div class="form-group">
                                <label for="enabled"><span class="text-danger font-weight-bold">*</span>Status</label>
                                <select class="form-control" name="enabled" id="enabled" required>
                                    <option value="1" {{(old('enabled', $user->enabled) == 1) ? 'selected' : ''}}>Enabled</option>
                                    <option value="0" {{(old('enabled', $user->enabled) == 0) ? 'selected' : ''}}>Disabled</option>
                                </select>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="interviewer_id">Default Interviewer ID <small>(Optional)</small></label>
                                <select class="form-control" name="interviewer_id" id="interviewer_id">
                                    <option value="" {{(is_null(old('interviewer_id', $user->interviewer_id))) ? 'selected' : ''}}>N/A</option>
                                    @foreach($interviewers->sortBy('lname') as $interviewer)
                                    <option value="{{$interviewer->id}}" {{(old('interviewer_id', $user->interviewer_id) == $interviewer->id) ? 'selected' : ''}}>{{$interviewer->getName()}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="canAccessLinelist"><span class="text-danger font-weight-bold">*</span>Can use Linelist</label>
                                <select class="form-control" name="canAccessLinelist" id="canAccessLinelist" required>
                                    <option value="1" {{(old('canAccessLinelist', $user->canAccessLinelist) == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('canAccessLinelist', $user->canAccessLinelist) == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="canByPassValidation"><span class="text-danger font-weight-bold">*</span>Bypass Validation of Encoding</label>
                                <select class="form-control" name="canByPassValidation" id="canByPassValidation" required>
                                    <option value="1" {{(old('canByPassValidation', $user->canByPassValidation) == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('canByPassValidation', $user->canByPassValidation) == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="isValidator"><span class="text-danger font-weight-bold">*</span>Is Validator?</label>
                                <select class="form-control" name="isValidator" id="isValidator" required>
                                    <option value="1" {{(old('isValidator', $user->isValidator) == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('isValidator', $user->isValidator) == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="isPositiveEncoder"><span class="text-danger font-weight-bold">*</span>Is Positive Cases Encoder?</label>
                                <select class="form-control" name="isPositiveEncoder" id="isPositiveEncoder" required>
                                    <option value="1" {{(old('isPositiveEncoder', $user->isPositiveEncoder) == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('isPositiveEncoder', $user->isPositiveEncoder) == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#interviewer_id').select2({
                theme: 'bootstrap',
            });
        });
    </script>
@endsection