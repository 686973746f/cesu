@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            @if($c->facility_id == 10886 || $c->facility_id == 39708 || $c->facility_id == 11730)
            <div class="text-center">
                <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
            </div>
            @elseif($c->facility_id == 10525)
            <div class="d-flex justify-content-between text-center">
                <div>
                    <img src="{{asset('assets/images/medicare_logo.png')}}" alt="" style="width: 8rem;">
                </div>
                <div>
                    <h4><b>CITY OF GENERAL TRIAS MEDICARE HOSPITAL</b></h4>
                    <h5>Gen. Trias City, Cavite</h5>
                    <h5>Tel. No. (046) 509-0064</h5>
                </div>
                <div>
                    <img src="{{asset('assets/images/gentri_icon_large.png')}}" alt="" style="width: 8rem;">
                </div>
            </div>
            @else
            <div class="text-center">
                <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
            </div>
            @endif
            <div class="text-center">
                <h4 class="mb-5"><b>Medical Certificate Verification</b></h4>
                <h6>Beware of fake verification sites. The legitimate site should have this domain name <span class="text-success"><b>https://cesugentri.com/medcert/verify</b></span></h6>
                <h6>The hardcopy should have <b>1.)</b> Written signature of the Physician <b>2.)</b> Dry Seal</h6>
            </div>
            <hr>
            @if($c)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td colspan="2" class="text-center"><b class="text-success">QR CODE VALID</b></td>
                        </tr>
                        <tr class="">
                            <td>Control No.</td>
                            <td>{{$c->opdno}}</td>
                        </tr>
                        <tr class="">
                            <td>Name</td>
                            <td>{{substr($c->syndromic_patient->lname, 0, 1) . preg_replace('/[^@]/', '*', substr($c->syndromic_patient->lname, 1))}}, {{substr($c->syndromic_patient->fname, 0, 1) . preg_replace('/[^@]/', '*', substr($c->syndromic_patient->fname, 1))}} {{(!is_null($c->syndromic_patient->mname)) ? substr($c->syndromic_patient->mname, 0, 1) . preg_replace('/[^@]/', '*', substr($c->syndromic_patient->mname, 1)) : ''}} </td>
                        </tr>
                        <tr class="">
                            <td>Birth Year</td>
                            <td>{{date('Y', strtotime($c->syndromic_patient->bdate))}}</td>
                        </tr>
                        <tr class="">
                            <td>Consultation Date</td>
                            <td>{{date('m/d/Y (l)', strtotime($c->consultation_date))}}</td>
                        </tr>
                        <tr class="">
                            <td>Name of Physician</td>
                            <td>{{$c->name_of_physician}} ({{$c->getPhysicianDetails()->position}})</td>
                        </tr>
                        <tr>
                            <td>MedCert Date Generated</td>
                            <td>{{date('m/d/Y (l)', strtotime($c->medcert_generated_date))}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center">
                <h1 class="text-danger"><i class="fas fa-times-circle fa-2x"></i></h1>
                <h3 class="text-danger"><b>INVALID QR CODE</b></h3>
            </div>
            @endif
            <div class="mt-3 text-center">
                <code class=" text-muted">CHO General Trias MedCert Verification System. Developed and Maintained by CJH.</code>
            </div>
        </div>
    </div>
</div>
@endsection