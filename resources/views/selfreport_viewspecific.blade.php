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
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">3. Occupation Details</div>
                        <div class="card-body">
                            <div id="occupationRow">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                          <label for="occupation">Occupation</label>
                                          <input type="text" class="form-control" value="{{(!is_null($data->occupation)) ? $data->occupation : 'N/A'}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="occupation_name">Name of Workplace</label>
                                            <input type="text" class="form-control" value="{{(!is_null($data->occupation_name)) ? $data->occupation_name : 'N/A'}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="natureOfWork">Nature of Work</label>
                                            <input type="text" class="form-control" value="{{(!is_null($data->natureOfWork)) ? $data->natureOfWork : 'N/A'}}" readonly>
                                        </div>
                                        @if($data->natureOfWork == 'OTHERS')
                                        <div class="form-group">
                                            <label for="natureOfWorkIfOthers">Please specify</label>
                                            <input type="text" class="form-control" value="{{(!is_null($data->natureOfWorkIfOthers)) ? $data->natureOfWorkIfOthers : 'N/A'}}" readonly>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">4. COVID-19 Vaccination Information</div>
                        <div class="card-body">
                            @if(!is_null($data->vaccinationDate1))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Name of Vaccine</label>
                                        <input type="text" class="form-control" name="" id="" value="{{$data->vaccinationName1}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="">1.) First Dose Date</label>
                                      <input type="date" class="form-control" name="" id="" value="{{$data->vaccinationDate1}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Vaccination Center/Facility</label>
                                        <input type="text" class="form-control" name="" id="" value="{{(!is_null($data->vaccinationFacility1)) ? $data->vaccinationFacility1 : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Region of Health Facility</label>
                                        <input type="text" class="form-control" name="" id="" value="{{(!is_null($data->vaccinationRegion1)) ? $data->vaccinationRegion1 : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Adverse Event/s</label>
                                        <input type="text" class="form-control" name="" id="" value="{{($data->haveAdverseEvents1 == 1) ? 'YES' : 'NO'}}" readonly>
                                    </div>
                                </div>
                            </div>
                            @if(!is_null($data->vaccinationDate2))
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="">2.) Second Dose Date</label>
                                      <input type="date" class="form-control" name="" id="" value="{{$data->vaccinationDate2}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Vaccination Center/Facility</label>
                                        <input type="text" class="form-control" name="" id="" value="{{(!is_null($data->vaccinationFacility2)) ? $data->vaccinationFacility2 : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Region of Health Facility</label>
                                        <input type="text" class="form-control" name="" id="" value="{{(!is_null($data->vaccinationRegion2)) ? $data->vaccinationRegion2 : 'N/A'}}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Adverse Event/s</label>
                                        <input type="text" class="form-control" name="" id="" value="{{($data->haveAdverseEvents2 == 1) ? 'YES' : 'NO'}}" readonly>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @else
                            <p class="text-center">Not Yet Vaccinated</p>
                            @endif
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