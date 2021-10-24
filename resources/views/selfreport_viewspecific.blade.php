@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('selfreport.finishAssessment', ['id' => $data->id])}}" method="POST">
            @csrf
            <div class="card border-info">
                <div class="card-header bg-info text-white font-weight-bold">Assess Positive Patient ({{$data->getName()}} <small>#{{$data->id}}</small>)</div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <p><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>Assess the patient by using the Contact Information Provided by Patient (Mobile Number or Email) at the bottom.</p>
                        <p>After completing the assessment, the patient record will be counted in the official list of Active Cases and will be added in the official patient records.</p>
                    </div>
                    <div class="card">
                        <div class="card-header font-weight-bold">1. Patient Information</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" class="form-control font-weight-bold" value="{{$data->lname}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fname">First Name (and Suffix)</label>
                                        <input type="text" class="form-control font-weight-bold" value="{{$data->fname}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mname">Middle Name</label>
                                        <input type="text" class="form-control font-weight-bold" value="{{$data->mname}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bdate">Birthdate</label>
                                        <input type="date" class="form-control" value="{{$data->bdate}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender">Age / Gender</label>
                                        <input type="text" class="form-control" value="{{$data->getAge().' / '.$data->gender}}" readonly>
                                    </div>
                                    @if($data->gender == 'FEMALE')
                                    <div class="form-group">
                                        <label for="isPregnant">Is the Patient Pregnant?</label>
                                        <input type="text" class="form-control" value="{{($data->isPregnant == 1) ? 'YES' : 'NO'}}" readonly>
                                    </div>
                                    @if($data->isPregnant == 1)
                                    <div class="form-group">
                                        <label for="lmp">Last Menstrual Period (LMP)</label>
                                        <input type="text" class="form-control" value="{{($data->isPregnant == 1) ? date('m/d/Y', strtotime($data->ifPregnantLMP)).' - '.$data->diff4Humans($data->ifPregnantLMP) : 'N/A'}}" readonly>
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cs">Civil Status</label>
                                        <input type="text" class="form-control" value="{{$data->cs}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nationality">Nationality</label>
                                        <input type="text" class="form-control" value="{{$data->nationality}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="text" class="form-control" value="{{$data->mobile}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="philhealth">Philhealth Number</label>
                                        <input type="text" class="form-control" value="{{!is_null($data->philhealth) ? $data->philhealth : 'N/A'}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phoneno">Telephone Number (& Area Code)</label>
                                        <input type="text" class="form-control" value="{{!is_null($data->phoneno) ? $data->phoneno : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="text" class="form-control" value="{{!is_null($data->email) ? $data->email : 'N/A'}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address_province">Province</label>
                                        <input type="text" class="form-control" value="{{$data->address_province}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address_city">City</label>
                                        <input type="text" class="form-control" value="{{$data->address_city}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address_brgy">Barangay</label>
                                        <input type="text" class="form-control" value="{{$data->address_brgy}}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_houseno">House No./Lot/Building</label>
                                        <input type="text" class="form-control" value="{{$data->address_houseno}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_street">Street/Purok/Sitio</label>
                                        <input type="text" class="form-control" value="{{$data->address_street}}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success">Complete Assessment</button>
                </div>
            </div>
        </form>
    </div>
@endsection