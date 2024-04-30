@extends('layouts.app')

@section('content')
    <form action="{{route('edcs_barangay_login')}}" method="POST">
        @csrf
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="text-center">
                        <img src="{{asset('assets/images/gentri_icon_large.png')}}" class="mb-3" style="width: 12rem;">
                        <img src="{{asset('assets/images/cho_icon_large.png')}}" class="mb-3" style="width: 12rem;">
                        <img src="{{asset('assets/images/cesu_icon.png')}}" class="mb-3" style="width: 12rem;">
                    </div>
                    <div class="card">
                        <div class="card-header"><b>Welcome to CESU General Trias - EDCS Cases Barangay Portal</b></div>
                        <div class="card-body">
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
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
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