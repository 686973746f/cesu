@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Referral Slip</div>
        <div class="card-body">
            <div class="text-center">
                <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                <h2 class="mt-2"><b>ANIMAL BITE TREATMENT CENTER</b></h2>
                <h2 class="mt-2"><b>REFERRAL SLIP</b></h2>
            </div>
            <p class="text-right">Date: {{date('m/d/Y')}}</p>
            <table class="table table-bordered">
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
                        <td class="text-center">{{(!is_null($b->patient->contact_number)) ? $b->patient->contact_number : 'N/A'}}</td>
                        <td class="bg-light">Philhealth No:</td>
                        <td class="text-center">{{(!is_null($b->patient->philhealth)) ? $b->patient->philhealth : 'N/A'}}</td>
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
                            <div class="mt-5"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <p><b>LUIS P. BROAS, RN, RPh, MAN</b></p>
            <p>Nurse II/ABTC Coordinator</p>
            <hr>
            
        </div>
    </div>
</div>   
@endsection