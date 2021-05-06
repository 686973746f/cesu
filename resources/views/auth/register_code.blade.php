@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{route('rcode.check')}}" method="GET" autocomplete="off">
                    <div class="card">
                        <div class="card-header">{{ __('Register') }}</div>
                        <div class="card-body">
                            @if(session('msg'))
                            <div class="alert alert-danger" role="alert">
                                <p class="mb-0">{{session('msg')}}</p>
                            </div>
                            @endif
                            <div class="form-group">
                            <label for="refCode">Referral Code</label>
                            <input type="text" class="form-control" name="refCode" id="refCode" autofocus required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection