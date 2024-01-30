@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">View Online CIF</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{asset('assets/images/pidsrlogo.png')}}" style="width: 5rem;" class="img-responsive">
                </div>
                <div class="col-md-4 text-center">
                    <h5>Case Investigation Form</h5>
                    <h4><b>Test</b></h4>
                </div>
                <div class="col-md-4 text-right">
                    <img src="{{asset('assets/images/doh_logo.png')}}" style="width: 5rem;" class="img-responsive">
                </div>
            </div>
            <table class="table table-bordered mt-3">
                <tbody>
                    <tr>
                        <td colspan="3">
                            <h6><b>Name of DRU:</b></h6>
                            <h6>{{$p->NameOfDru}}</h6>
                        </td>
                        <td colspan="2">
                            <h6><b>Date Encoded:</b></h6>
                            <h6>{{date('M d, Y', strtotime($p->DateOfEntry))}}</h6>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="bg-light"><h6><b>I. PATIENT PROFILE</b></h6></td>
                    </tr>
                    <tr>
                        <td>
                            <h6>Patient Number:</h6>
                            <h6></h6>
                        </td>
                        <td>
                            <h6><b>Last Name:</b></h6>
                            <h6>{{$p->FamilyName}}</h6>
                        </td>
                        <td>
                            <h6><b>First Name:</b></h6>
                            <h6></h6>
                        </td>
                        <td>
                            <h6><b>Middle Name:</b></h6>
                            <h6></h6>
                        </td>
                        <td>
                            <h6><b>Suffix:</b></h6>
                            <h6></h6>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection