@extends('layouts.app')

@section('content')
    <form action="{{route('edcs_barangay_login')}}" method="POST">
        @csrf
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="text-center">
                        <div class="row mb-3">
                            <div class="col-4">
                                <img src="{{asset('assets/images/gentri_icon_large.png')}}" class="img-fluid">
                            </div>
                            <div class="col-4">
                                <img src="{{asset('assets/images/cho_icon_large.png')}}" class="img-fluid">
                            </div>
                            <div class="col-4">
                                <img src="{{asset('assets/images/cesu_icon.png')}}" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header"><b>Welcome to CESU General Trias - EDCS Cases Barangay Portal</b></div>
                        <div class="card-body">
                            @if(session('msg'))
                            <div class="alert alert-{{session('msgtype')}}" role="alert">
                                {{session('msg')}}
                            </div>
                            @endif
                            <div class="form-group">
                              <label for="brgy"><b class="text-danger">*</b>Select Barangay</label>
                              <select class="form-control" name="brgy" id="brgy" required>
                                <option value="" disabled selected></option>
                                @foreach($brgy_list as $b)
                                <option value="{{$b->brgyName}}">{{$b->brgyName}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="form-group">
                              <label for="password"><b class="text-danger">*</b>Password</label>
                              <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                            <div class="alert alert-info text-center" role="alert">
                                By logging in, you agree to comply with the provisions of the <b>Data Privacy Act of 2012 (R.A. No. 10173)</b> and <b>R.A. No. 11332</b>.
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
                    <p class="mt-3 text-center">©2024 Developed and Mainted by <u>Christian James Historillo</u> for General Trias CHO - CESU</p>
                </div>
            </div>
            
        </div>
    </form>

    <script>
        $('#brgy').select2({
            theme: 'bootstrap',
        });
    </script>
@endsection