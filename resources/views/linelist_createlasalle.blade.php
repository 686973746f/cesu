@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <form action="" method="POST">
            <div class="card">
                <div class="card-header">Create LaSalle Linelist</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="dru">Disease Reporting Unit (Hospital/Agency)</label>
                              <input type="text" name="dru" id="dru" class="form-control" value="CITY HEALTH OFFICE - GENERAL TRIAS" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="laSallePhysician">Referring Physician</label>
                                <input type="text" name="laSallePhysician" id="laSallePhysician" class="form-control" value="Dr. JONATHAN P. LUSECO" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="contactPerson">Contact Person</label>
                              <input type="text" name="contactPerson" id="contactPerson" class="form-control" value="LUIS P. BROAS" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Official E-mail Address</label>
                                <input type="email" name="email" id="email" class="form-control" value="cesu.gentrias@gmail.com" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="contactTelephone">Contact Person</label>
                              <input type="text" name="contactTelephone" id="contactTelephone" class="form-control" value="(046) 509 5289" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contactMobile">Mobile Number</label>
                                <input type="text" name="contactMobile" id="contactMobile" class="form-control" value="0917 561 1254" required>
                            </div>
                        </div>
                    </div>
                    <hr>
                    
                </div>
            </div>
        </form>
    </div>
@endsection