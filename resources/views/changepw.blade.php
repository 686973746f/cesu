@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{route('changepw.init')}}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header"><i class="fa fa-key mr-2" aria-hidden="true"></i>Change Password</div>
                        <div class="card-body">
                            @if(session('msg'))
                                <div class="text-center alert alert-{{session('msgtype')}}" role="alert">
                                    {{session('msg')}}
                                </div>
                            @endif
                            <div class="form-group">
                            <label for="oldpw">Input Old Password</label>
                            <input type="password" class="form-control" name="oldpw" id="oldpw" minlength="8" required>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="newpw1">New Password</label>
                                <input type="password" class="form-control" name="newpw1" id="newpw1" minlength="8" required>
                            </div>
                            <div class="form-group">
                                <label for="newpw2">Repeat New Password</label>
                                <input type="password" class="form-control" name="newpw2" id="newpw2" minlength="8" required>
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
@endsection