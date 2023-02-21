@extends('layouts.app')

@section('content')
<style>
    @media print {
        #PrintBtn, #titleBody {
            display: none;
        }

        body {
            background-color: white;
        }

        body * {
            visibility: hidden;
        }

        #divToPrint, #divToPrint * {
            visibility: visible;
        }

        #divToPrint {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>Referral Slip</div>
                <div><button type="button" class="btn btn-primary" onclick="window.print()" id="PrintBtn">Print</button></div>
            </div>
        </div>
        <div class="card-body" id="divToPrint">
            <div class="text-center">
                <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;width: 50rem;">
                <h4 class="mt-2"><b>ANIMAL BITE TREATMENT CENTER</b></h4>
                <h4 class="mt-2"><b><u>REFERRAL SLIP</u></b></h4>
            </div>
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td class="bg-light">To:</td>
                        <td>Animal Bite Clinic (ABC) / Animal Bite Treatment Center (ABTC)</td>
                    </tr>
                    <tr>
                        <td class="bg-light">From:</td>
                        <td>City Health Office (CHO) General Trias, Cavite (Animal Bite Treatment Center)</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td style="background-color: #f8f8fb">Name of Patient:</td>
                        <td>{{$b->patient->getName()}}</td>
                        <td class="bg-light">Age/Gender</td>
                        <td>{{$b->patient->getAge()}}/{{$b->patient->sg()}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Address:</td>
                        <td colspan="3">{{$b->patient->getAddress()}}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td class="bg-light">Complaint/Findings</td>
                        <td>{{$b->getBiteType()}} ({{$b->getSource()}})</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Impression/Diagnosis</td>
                        <td>Animal Bite</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Prescription/Rx</td>
                        <td>N/A</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Reason for Referral</td>
                        <td>{{$reason}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Recommendation</td>
                        <td>{{$rec}}</td>
                    </tr>
                </tbody>
            </table>
            <div class="row text-center">
                <div class="col-md-6">
                    <p><u>{{date('m/d/Y')}}</u></p>
                    <p style="margin-top: -20px;">Date</p>
                </div>
                <div class="col-md-6">
                    <p>____________________</p>
                    <p style="margin-top: -20px;">Physician</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;width: 50rem;">
                <h4 class="mt-2"><b>ANIMAL BITE TREATMENT CENTER</b></h4>
                <h4 class="mt-2"><b><u>REFERRAL SLIP</u></b></h4>
            </div>
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td class="bg-light">To:</td>
                        <td>Animal Bite Clinic (ABC) / Animal Bite Treatment Center (ABTC)</td>
                    </tr>
                    <tr>
                        <td class="bg-light">From:</td>
                        <td>City Health Office (CHO) General Trias, Cavite (Animal Bite Treatment Center)</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td style="background-color: #f8f8fb">Name of Patient:</td>
                        <td>{{$b->patient->getName()}}</td>
                        <td class="bg-light">Age/Gender</td>
                        <td>{{$b->patient->getAge()}}/{{$b->patient->sg()}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Address:</td>
                        <td colspan="3">{{$b->patient->getAddress()}}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td class="bg-light">Complaint/Findings</td>
                        <td>{{$b->getBiteType()}} ({{$b->getSource()}})</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Impression/Diagnosis</td>
                        <td>Animal Bite</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Prescription/Rx</td>
                        <td>N/A</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Reason for Referral</td>
                        <td>{{$reason}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Recommendation</td>
                        <td>{{$rec}}</td>
                    </tr>
                </tbody>
            </table>
            <div class="row text-center">
                <div class="col-md-6">
                    <p><u>{{date('m/d/Y')}}</u></p>
                    <p style="margin-top: -20px;">Date</p>
                </div>
                <div class="col-md-6">
                    <p>____________________</p>
                    <p style="margin-top: -20px;">Physician</p>
                </div>
            </div>
        </div>
    </div>
</div>   
@endsection