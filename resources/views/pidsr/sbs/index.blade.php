@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>General Trias City CESU - School Based Disease Surveillance</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <strong>There were some problems with your submission:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <a href="{{route('sbs_new', $s->qr)}}" class="btn btn-lg btn-success btn-block">New Case</a>
                @if(!$s->password)
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#modelId">View List</button>
                @else
                <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#modelId">View List</button>
                @endif
            </div>
        </div>
    </div>
    <div class="text-center mt-3">
        <p>CESU General Trias: School Based Disease Surveillance System - Voluntarily Developed and Mantained by CJH</p>
    </div>

    @if(!$s->password)
    <form action="{{route('sbs_init', $s->qr)}}" method="POST">
        @csrf
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Initialize Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="email"><b class="text-danger">*</b>Set Email Address</label>
                          <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <hr>
                        <div class="form-group">
                          <label for="password"><b class="text-danger">*</b>Password</label>
                          <input type="password" class="form-control" minlength="6" maxlength="20" name="password" id="password" required>
                        </div>
                        <div class="form-group">
                          <label for="password_confirmation"><b class="text-danger">*</b>Re-type Password</label>
                          <input type="password" class="form-control" minlength="6" maxlength="20" name="password_confirmation" id="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @else
    <form action="{{route('sbs_login')}}" method="POST">
        @csrf
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Login</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="email"><b class="text-danger">*</b>Email</label>
                          <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="form-group">
                          <label for="password"><b class="text-danger">*</b>Password</label>
                          <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endif
@endsection