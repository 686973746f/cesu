@extends('layouts.app')

@section('content')
<style>
    #divToPrint {
        background-image: url("{{asset('assets/images/gentri_icon_large_watermark.png')}}");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 70%;
    }

    @media print {
        #PrintBtn, #titleBody {
            display: none;
        }

        @page {
            margin: 0;
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
                    <div><b>Vaccine Certificate - LGU</b></div>
                    <div><button type="button" class="btn btn-success" onclick="window.print()"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print <i>(CTRL + P)</i></button></div>
                </div>
            </div>
            <div class="card-body" id="divToPrint">
                <div class="text-center">
                    <img src="{{asset('assets/images/CHO_LETTERHEAD.png')}}" class="img-fluid" style="margin-top: 0px;">
                </div>
                <h3 class="text-center mt-3"><b>CERTIFICATION</b></h3>
                <h4 class="mt-5">To whom it may concern,</h4>
                <h4 class="mt-5" style="text-align:justify">This is to certify that <u><b>{{$d->getSalutation()}} {{$d->getName()}}</b></u>, <b><u>{{Carbon\Carbon::parse($d->bdate)->age;}}</u></b> years of age, born on <b><u>{{date('F d, Y', strtotime($d->bdate))}}</u></b>, and currently residing at <u><b>{{mb_strtoupper($d->getFullAddress())}}</b></u> has been vaccinated against COVID-19 at the City Health Office, Brgy. Pinagtipunan, City of General Trias, Cavite.</h4>
                <h4 class="mt-5">The following details pertain to the vaccination:</h4>
                @if($d->dose1_vaccine_manufacturer_name == 'J&J')
                <div class="row justify-content-center mt-3">
                    <div class="col-sm-5">
                        <div class="card">
                            <div class="card-header"><b>1st and 2nd Dose</b></div>
                            <div class="card-body">
                                @if($d->dose1_city == 'Y')
                                <div class="d-flex justify-content-between">
                                    <div><b>Date of Vaccination:</b></div>
                                    <div>{{date('F d, Y', strtotime($d->dose1_vaccination_date))}}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><b>Manufacturer:</b></div>
                                    <div>{{$d->dose1_vaccine_manufacturer_name}}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><b>Batch/Lot No.:</b></div>
                                    <div>{{$d->dose1_batch_number}}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><b>Vaccinator:</b></div>
                                    <div>
                                        <div>{{$d->dose1_vaccinator_name}}</div>
                                        <div>License No. {{$d->dose1_vaccinator_licenseno}}</div>
                                    </div>
                                </div>
                                @else
                                <p class="text-center">Vaccinated outside the City of General Trias</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="row justify-content-center mt-3">
                    <div class="col-sm-5">
                        <div class="card">
                            <div class="card-header"><b>1st Dose</b></div>
                            <div class="card-body">
                                @if($d->dose1_city == 'Y')
                                <div class="d-flex justify-content-between">
                                    <div><b>Date of Vaccination:</b></div>
                                    <div>{{date('F d, Y', strtotime($d->dose1_vaccination_date))}}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><b>Manufacturer:</b></div>
                                    <div>{{$d->dose1_vaccine_manufacturer_name}}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><b>Batch/Lot No.:</b></div>
                                    <div>{{$d->dose1_batch_number}}</div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><b>Vaccinator:</b></div>
                                    <div>
                                        <div>{{$d->dose1_vaccinator_name}}</div>
                                        <div>License No. {{$d->dose1_vaccinator_licenseno}}</div>
                                    </div>
                                </div>
                                @else
                                <p class="text-center">Vaccinated outside the City of General Trias</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="card">
                            <div class="card-header"><b>2nd Dose</b></div>
                            <div class="card-body">
                                @if($d->process_dose2 == 'Y')
                                    @if($d->dose2_city == 'Y')
                                    <div class="d-flex justify-content-between">
                                        <div><b>Date of Vaccination:</b></div>
                                        <div>{{date('F d, Y', strtotime($d->dose1_vaccination_date))}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div><b>Manufacturer:</b></div>
                                        <div>{{$d->dose1_vaccine_manufacturer_name}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div><b>Batch/Lot No.:</b></div>
                                        <div>{{$d->dose1_batch_number}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div><b>Vaccinator:</b></div>
                                        <div>
                                            <div>{{$d->dose1_vaccinator_name}}</div>
                                            <div>License No. {{$d->dose1_vaccinator_licenseno}}</div>
                                        </div>
                                    </div>
                                    @else
                                    <p class="text-center">Vaccinated outside the City of General Trias</p>
                                    @endif
                                @else
                                <p class="text-center">N/A</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row justify-content-center my-3">
                    <div class="col-sm-5">
                        <div class="card">
                            <div class="card-header"><b>3rd Dose (1st Booster)</b></div>
                            <div class="card-body">
                                @if($d->process_dose3 == 'Y')
                                    @if($d->dose3_city == 'Y')
                                    <div class="d-flex justify-content-between">
                                        <div><b>Date of Vaccination:</b></div>
                                        <div>{{date('F d, Y', strtotime($d->dose3_vaccination_date))}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div><b>Manufacturer:</b></div>
                                        <div>{{$d->dose3_vaccine_manufacturer_name}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div><b>Batch/Lot No.:</b></div>
                                        <div>{{$d->dose3_batch_number}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div><b>Vaccinator:</b></div>
                                        <div>
                                            <div>{{$d->dose3_vaccinator_name}}</div>
                                            <div>License No. {{$d->dose3_vaccinator_licenseno}}</div>
                                        </div>
                                    </div>
                                    @else
                                    <p class="text-center">Vaccinated outside the City of General Trias</p>
                                    @endif
                                @else
                                <p class="text-center">N/A</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="card">
                            <div class="card-header"><b>4th Dose (2nd Booster)</b></div>
                            <div class="card-body">
                                @if($d->process_dose4 == 'Y')
                                    @if($d->dose4_city == 'Y')
                                    <div class="d-flex justify-content-between">
                                        <div><b>Date of Vaccination:</b></div>
                                        <div>{{date('F d, Y', strtotime($d->dose4_vaccination_date))}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div><b>Manufacturer:</b></div>
                                        <div>{{$d->dose4_vaccine_manufacturer_name}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div><b>Batch/Lot No.:</b></div>
                                        <div>{{$d->dose4_batch_number}}</div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div><b>Vaccinator:</b></div>
                                        <div>
                                            <div>{{$d->dose4_vaccinator_name}}</div>
                                            <div>License No. {{$d->dose4_vaccinator_licenseno}}</div>
                                        </div>
                                    </div>
                                    @else
                                    <p class="text-center">Vaccinated outside the City of General Trias</p>
                                    @endif
                                @else
                                <p class="text-center">N/A</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <h4 style="text-align:justify">This certification is issued upon the request of the above-mentioned name on <b><u>{{date('F d, Y', strtotime($d->created_at))}}</u></b> for whatever legal purposes it may serve.</h4>

                <div class="d-flex justify-content-between mt-5">
                    <div>
                        <h5 class="mb-3 bg-warning text-danger">Control No.: {{$d->control_no}}</h5>
                        <ul>
                            <h5>COVID Hotlines:</h5>
                            <li><h5>0962 545 6998</h5></li>
                            <li><h5>0962 545 6556 to 8</h5></li>
                            <li><h5>(046) 509 5289</h5></li>
                        </ul>
                    </div>
                    <div class="text-center">
                        <img src="{{asset('assets/images/signatureonly_docathan.png')}}" class="img-fluid" style="margin-bottom:-30px;width:10rem;" id="signature3">
                        <h4><b>JONATHAN P. LUSECO, MD</b></h4>
                        <h4>City Health Officer II</h4>
                    </div>
                    <div class="text-center">
                        <div>{!! QrCode::size(150)->generate(route('vaxcertlgu_onlineverify', $d->hash)) !!}</div>
                        <small>(Scan QR to Verify)</small>
                    </div>
                </div>
                
                <h4 class="text-center mt-3"><b><span class="text-primary">Let's Join Forces</span> <span class="text-success">For a COVID-19 Free</span> <span class="text-success">Gen</span><span class="text-primary">Tri</span></b></h4>
                <hr>
                <div class="text-center">
                    <h6><b>Office of the City Health Officer</b></h6>
                    <h6>Hospital Rd., Brgy. Pinagtipunan, General Trias, Cavite</h6>
                    <h6>Website: <a href="">https://generaltrias.gov.ph/cho</a></h6>
                    <h6>Contact No.: (046) 509 5289</h6>
                </div>
            </div>
        </div>
    </div>
@endsection