@extends('layouts.app')

@section('content')
    <form action="{{route('options.submit')}}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><i class="fa fa-cog mr-2" aria-hidden="true"></i>Options</div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="form-check">
                      <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="option_enableAutoRedirectToCif" id="option_enableAutoRedirectToCif" value="1" {{(auth()->user()->option_enableAutoRedirectToCif == 1) ? 'checked' : ''}}>
                        Enable Auto Redirect to CIF after Creating Patient Record
                      </label>
                    </div>
                    <hr>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                </div>
            </div>
        </div>
    </form>
@endsection