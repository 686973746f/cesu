@extends('layouts.app')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="card">
            <div class="card-header text-center">
                <img src="{{asset('assets/images/cesu_icon.png')}}" class="mb-3" style="width: 10rem;">
                <img src="{{asset('assets/images/cho_icon_large.png')}}" style="width: 10rem;" class="mb-3">
                <img src="{{asset('assets/images/gentri_icon_large.png')}}" style="width: 10rem;" class="mb-3">
                <hr>
                <h5>Province of Cavite</h5>
                <h5>City of General Trias</h5>
                <h5>City Health Office</h5>
                <h5>City Epidemiology and Surveillance Unit</h5>
                <hr>
                <h5 class="font-weight-bold">COVID-19 Swab Scheduling System (Pa-swab)</h5>
            </div>
            <div class="card-body text-center">
                <p>Select Language</p>
                <p>Pumili ng Wika</p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-primary btn-lg btn-block my-3" href="{{route('paswab.index', ['locale' => 'en'])}}?rlink={{request()->input('rlink')}}&s={{request()->input('s')}}" role="button"><span class="flag-icon flag-icon-gb mr-2"></span>English</a>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-primary btn-lg btn-block my-3" href="{{route('paswab.index', ['locale' => 'fil'])}}?rlink={{request()->input('rlink')}}&s={{request()->input('s')}}" role="button"><span class="flag-icon flag-icon-ph mr-2"></span>Filipino / Tag-lish</a>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-center">Note: If errors/issues has been found or if site not working properly, please contact CESU Staff Immediately.</p>
            </div>
        </div>
        <p class="text-center mt-3">For inquiries: 0919 066 43 24/25/27 | (046) 509 - 5289 | <a href = "mailto: cesu.gentrias@gmail.com">cesu.gentrias@gmail.com</a> | <a href="https://www.facebook.com/cesugentrias">Facebook Page</a></p>
        <hr>
        <p class="mt-3 text-center">Developed and Maintained by <u>CJH</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
    </div>
@endsection