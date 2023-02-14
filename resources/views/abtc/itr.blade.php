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
        <div class="card-header" id="">
            <div class="d-flex justify-content-between">
                <div>ABTC Online ITR</div>
                <div><button type="button" class="btn btn-primary" id="PrintBtn" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print</button></div>
            </div>
        </div>
        <div class="card-body" id="divToPrint">
            <div class="text-center">
                <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                <h3 class="mt-2"><b>ANIMAL BITE TREATMENT CENTER</b></h3>
                <h4 class="mt-2"><b><u>INDIVIDUAL TREATMENT RECORD</u></b></h4>
            </div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="bg-light">Name:</td>
                        <td>{{$b->patient->getName()}}</td>
                        <td class="bg-light">Civil Status</td>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <td class="bg-light">Age/Gender</td>
                        <td>{{$b->patient->getAge()}}/{{$b->patient->sg()}}</td>
                        <td class="bg-light">Father</td>
                        <td></td>
                        <td class="bg-light">Mother</td>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <td class="bg-light">Date of Birth</td>
                        <td></td>
                        <td class="bg-light">Spouse</td>
                        <td colspan="3"></td>
                    </tr>
                    <tr>
                        <td class="bg-light">Address</td>
                        <td>{{$b->patient->getAddress()}}</td>
                        <td class="bg-light">P.Health/Pantawid #</td>
                        <td colspan="3"></td>
                    </tr>
                </tbody>
            </table>
            <p>Date: {{date('m/d/Y')}}</p>
            <p>S/O</p>
            <p class="ml-5">Chief Complaint: _____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________</p>
            <p>BP: ____________________</p>
            <p>WT: ____________________</p>
            <p>HT: ____________________</p>
            <p>Temperature: ____________________</p>
            <p>A:</p>
            <p style="margin-top: 100px;">P:</p>
            <div class="text-right">
                <p>_____________________</p>
                <p>(Physician)</p>
            </div>
        </div>
    </div>
</div>   
@endsection