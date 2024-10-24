@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
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
                <code class=" text-muted">CHO General Trias MedCert Verification System. Developed and Maintained by Christian James Historillo.</code>
            </div>
        </div>
    </div>
</div>
@endsection