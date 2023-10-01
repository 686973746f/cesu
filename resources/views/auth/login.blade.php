@extends('layouts.app')

@section('content')
<div class="container" style="font-family: Arial, Helvetica, sans-serif;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center">
                <img src="{{asset('assets/images/gentri_icon_large.png')}}" class="mb-3" style="width: 12rem;">
                <img src="{{asset('assets/images/cho_icon_large.png')}}" class="mb-3" style="width: 12rem;">
                <img src="{{asset('assets/images/cesu_icon.png')}}" class="mb-3" style="width: 12rem;">
            </div>
            <div class="accordion" id="accordionExample">
                <div class="card">
                  <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                      <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Staff Login
                      </button>
                    </h2>
                  </div>
              
                  <div id="collapseOne" class="collapse {{(session('openform') == 'patient') ? '' : 'show'}}" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
    
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
    
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
    
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
    
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
    
                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button class="g-recaptcha btn btn-primary" 
                                    data-sitekey="{{ env('RECAPTCHA_SITEKEY') }}" 
                                    data-callback='onSubmit' 
                                    data-action='submit'>Login</button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <p>{{ __('Don\'t have an account yet?') }}</p>
                                <a href="{{ route('rcode.index') }}" class="btn btn-link">Register</a>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
                <!--
                <div class="card">
                  <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                      <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        I am a COVID-19 Swab Test Patient
                      </button>
                    </h2>
                  </div>
                  <div id="collapseTwo" class="collapse {{(session('openform') == 'patient') ? 'show' : ''}}">
                    <form action="{{route('paswab.check', ['locale' => 'en'])}}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if(session('msg'))
                            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                                <p class="mb-0">{{session('msg')}}</p>
                            </div>
                            @endif
                            <div class="form-group">
                              <label for="scode">Input your Schedule Code</label>
                              <input type="text" class="form-control" name="scode" id="scode" value="{{old('scode')}}" required>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Check</button>
                        </div>
                    </form>
                  </div>
                </div>
                -->
            </div>
            <p class="text-center mt-3">For inquiries: 0919 066 43 24/25/27 | (046) 509 - 5289 | <a>cesu.gentrias@gmail.com</a> | <a href="https://www.facebook.com/cesugentrias">Facebook Page</a></p>
            <hr>
            <p class="mt-3 text-center">Developed and Maintained by <u>Christian James Historillo</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
        </div>
    </div>
</div>

<script>
    function onSubmit(token) {
        
      $("#loginForm").submit();
    }
  </script>
@endsection
