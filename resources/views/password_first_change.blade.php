@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{route('first_changepw_init')}}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header"><b>Change Password</b></div>
                        <div class="card-body">
                            <div class="alert alert-info" role="alert">
                                This is your first time logging in. Please change your password to continue.
                            </div>
                            
                            <div class="form-group">
                                <label for="newpw1"><b class="text-danger">*</b>New Password</label>
                                <input type="password" class="form-control" name="newpw1" id="newpw1" minlength="8" required>
                                <small class="form-text text-muted">Your password must be at least 8 characters long.</small>
                            </div>
                            <div class="form-group">
                                <label for="newpw2"><b class="text-danger">*</b>Repeat New Password</label>
                                <input type="password" class="form-control" name="newpw2" id="newpw2" minlength="8" required>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection