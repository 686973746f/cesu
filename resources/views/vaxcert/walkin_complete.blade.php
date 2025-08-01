@extends('layouts.app')

@section('content')
<style>
    .rectangle {
        border: 2px solid black;
        padding: 10px;
        width: 200px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header text-center">Submission Complete!</div>
                <div class="card-body text-center">
                    <h5>Please save your reference code:</h5>
                    <div class="d-flex justify-content-center">
                        <div class="rectangle">
                            <h3>{{$code}}</h3>
                        </div>
                    </div>
                    <h6 class="text-center">Paki-screenshot o i-print ang iyong reference code upang magamit sa pag-track ng iyong concern.</h6>
                    <h6 class="text-center">Babalitaan namin kayo makalipas ng isa (1) hanggang dalawang (2) araw. Antayin ang aming update via <b>TEXT</b> o <b>TAWAG</b> pati na rin sa iyong <b>EMAIL ADDRESS</b>.</h6>
                    <hr>
                    <p>If you have any concerns, you may contact us at:</p>
                    <table class="table text-center table-bordered">
                        <tbody>
                            <tr>
                                <td scope="row" rowspan="2" style="vertical-align: middle;"><i class="fas fa-phone-alt mr-2"></i>Text/Call/Viber</td>
                                <td>0962 545 6998</td>
                            </tr>
                            <tr>
                                <td>0954 154 8355</td>
                            </tr>
                            <tr>
                                <td scope="row">Telephone Number</td>
                                <td>(046) 509 - 5289 local 300</td>
                            </tr>
                            <tr>
                                <td scope="row"><i class="fas fa-at mr-2"></i>Email Address</td>
                                <td><a href = "mailto: cesugentrias.vaxcert@gmail.com">cesugentrias.vaxcert@gmail.com</a></td>
                            </tr>
                            <tr>
                                <td colspan="2"><a href="https://www.facebook.com/cesugentrias">CESU GenTri Facebook Page</a></td>
                            </tr>
                            <tr>
                                <td scope="row">Address</td>
                                <td>City Health Office (3rd Floor CESU Office), Pria Rd., Hospital Area - Main, Brgy. Pinagtipunan, General Trias, Cavite, 4107</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="font-weight-bold"><i class="far fa-clock mr-2"></i>Office Hours: 08:00 AM - 05:00 PM (from Monday - Friday ONLY, except Holidays)</p>
                    <p>Thank you very much.</p>
                </div>
            </div>
            <p class="text-center">GenTrias LGU VaxCert Concern Ticketing System - Developed and Maintained by <u>CJH</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
        </div>
    </div>
</div>
@endsection