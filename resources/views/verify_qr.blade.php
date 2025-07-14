@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <img src="{{asset('assets/images/cesu_icon.png')}}" class="mb-3" style="width: 8rem;">
                <h4><b>CESU Gen. Trias Swab Test Result Verification</b></h4>
                <hr>
                <span>Beware of fake verification sites. The legitimate site should have this domain name https://cesugentri.com/verify/</span>
                <hr>
                @if($c)
                @php
                
                @endphp
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr class="">
                                <td>Test ID</td>
                                <td>#{{$c->id}}</td>
                            </tr>
                            <tr class="">
                                <td>Name</td>
                                <td>{{substr($c->records->lname, 0, 1) . preg_replace('/[^@]/', '*', substr($c->records->lname, 1))}}, {{substr($c->records->fname, 0, 1) . preg_replace('/[^@]/', '*', substr($c->records->fname, 1))}} {{(!is_null($c->records->mname)) ? substr($c->records->mname, 0, 1) . preg_replace('/[^@]/', '*', substr($c->records->mname, 1)) : ''}} </td>
                            </tr>
                            <tr class="">
                                <td>Birth Year</td>
                                <td>{{date('Y', strtotime($c->records->bdate))}}</td>
                            </tr>
                            <tr class="">
                                <td>Specimen Type</td>
                                <td>{{$sType}}</td>
                            </tr>
                            <tr class="">
                                <td>Specimen Collection</td>
                                <td>{{$sDate}}</td>
                            </tr>
                            <tr class="">
                                <td>Result Released</td>
                                <td>{{$sDateReleased}}</td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td>Result</td>
                                <td class="{{$txtc}}">{{$sResult}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <h3 class="text-danger">INVALID QR CODE</h3>
                <p>Sorry, your QR Code is invalid.</p>
                @endif
                <div class="mt-3">
                    <code class="text-center text-muted">CESU General Trias Result Verification System. Developed by CJH.</code>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection