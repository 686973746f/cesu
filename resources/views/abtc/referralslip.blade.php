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
            <p class="text-right"><b>Date:</b> <u>{{date('m/d/Y')}}</u></p>
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td class="bg-light">Name:</td>
                        <td class="text-center"><b>{{$b->patient->getName()}}</b></td>
                        <td class="bg-light">Age/Gender:</td>
                        <td class="text-center">{{$b->patient->getAge()}}/{{$b->patient->sg()}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Contact Number:</td>
                        <td class="text-center">{{(!is_null($b->patient->contact_number)) ? $b->patient->contact_number : 'N/A'}}</td>
                        <td class="bg-light">Philhealth No:</td>
                        <td class="text-center">{{(!is_null($b->patient->philhealth)) ? $b->patient->philhealth : 'N/A'}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Address:</td>
                        <td class="text-center" colspan="3">{{$b->patient->getAddress()}}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Reason for Referral</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="margin-top: 100px;margin-bottom: 100px;"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>Prepared by:</p>
            <p style="margin-top: 40px;"><b><u>LUIS P. BROAS, RN, RPh, MAN</u></b></p>
            <p style="margin-top: -10px;">Nurse II/ABTC Coordinator</p>
            <hr>
            <div class="text-center">
                <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;width: 50rem;">
                <h4 class="mt-2"><b>ANIMAL BITE TREATMENT CENTER</b></h4>
                <h4 class="mt-2"><b><u>REFERRAL SLIP</u></b></h4>
            </div>
            <p class="text-right"><b>Date:</b> <u>{{date('m/d/Y')}}</u></p>
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <td class="bg-light">Name:</td>
                        <td class="text-center"><b>{{$b->patient->getName()}}</b></td>
                        <td class="bg-light">Age/Gender:</td>
                        <td class="text-center">{{$b->patient->getAge()}}/{{$b->patient->sg()}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Contact Number:</td>
                        <td class="text-center">{{(!is_null($b->patient->contact_number)) ? $b->patient->contact_number : 'N/A'}}</td>
                        <td class="bg-light">Philhealth No:</td>
                        <td class="text-center">{{(!is_null($b->patient->philhealth)) ? $b->patient->philhealth : 'N/A'}}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Address:</td>
                        <td class="text-center" colspan="3">{{$b->patient->getAddress()}}</td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Reason for Referral</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="margin-top: 100px;margin-bottom: 100px;"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p>Prepared by:</p>
            <p style="margin-top: 40px;"><b><u>LUIS P. BROAS, RN, RPh, MAN</u></b></p>
            <p style="margin-top: -10px;">Nurse II/ABTC Coordinator</p>
        </div>
    </div>
</div>   
@endsection