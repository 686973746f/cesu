@extends('layouts.app')

@section('content')
<style>
    @media print {
        #printDiv, #divFoot {
            display: none;
        }

        @page {
            margin: 0;
        }

        body {
            background-color: white;
            margin-top: 0;
        }
    }

    .content {
        text-align: center;
    }
    .inner {
        display:inline-block;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            
            @if(isset($queue_number))
            <div class="alert alert-success" role="alert">
                <div class="d-flex justify-content-between">
                    <div><h2>Queue No.:</h2></div>
                    <div><h2><b>#{{$queue_number}}</b></h2></div>
                </div>
            </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div id="printDiv">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <a href="{{route('abtc_print_new', $f->id)}}" type="button" class="btn btn-primary btn-block" id="printnew">Print (New Card)</a>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#philhealthForms" {{($f->category_level == 2 || $f->category_level == 3) ? '' : 'disabled'}}>Print PhilHealth Forms</button>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-primary btn-block" onclick="window.print()" id="printbtn"><i class="fas fa-print mr-2"></i>Print (Old Form)</button>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div><a href="{{route('abtc_schedule_index')}}" class="btn btn-link"><i class="fas fa-calendar-alt mr-2"></i>Back to Todays Schedule</a></div>
                            <div><a href="{{route('abtc_encode_edit', ['br_id' => $f->id])}}" class="btn btn-link"><i class="fas fa-backward mr-2"></i>Back to Patient Details</a></div>
                            <div><a href="{{route('abtc_patient_create')}}" class="btn btn-link"><i class="fas fa-user-plus mr-2"></i>Add NEW Patient</a></div>
                        </div>
                        <hr>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6><b>{{$f->getBranch()}}</b></h6>
                            <h6><b>ANIMAL BITE TREATMENT CENTER</b></h6>
                        </div>
                        <div class="font-weight-bold">
                            <ul>
                                <i class="fas fa-clock mr-2"></i>Schedule: Mon,Tue,Thu,Fri
                                <li class="text-success">New Patients: 8AM - 11AM</li>
                                <li class="text-primary">Follow-up: 1PM</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table text-center table-borderless" style="margin-top: -20px;">
                        <tbody>
                            <tr >
                                <td style="vertical-align: middle;">
                                    <img src="{{asset('assets/images/gentri_icon_large.png')}}" style="width: 8rem;" class="img-fluid mr-3" alt="">
                                    <img src="{{asset('assets/images/cho_icon_large.png')}}" style="width: 8rem;" class="img-fluid" alt="">
                                </td>
                                <td class="content">
                                    <div class="inner">
                                        <div>{!! QrCode::size(120)->generate(route('abtc_qr_process', $f->patient->qr)) !!}</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-borderless" style="margin-top: -20px;">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">Registration No.:</td>
                                <td><u>{{$f->case_id}}</u></td>
                                <td class="font-weight-bold">Date Registered:</td>
                                <td><u>{{date('m/d/Y', strtotime($f->case_date))}}</u></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-borderless" style="margin-top: -20px;">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">Name:</td>
                                <td><u>{{$f->patient->getName()}}</u></td>
                                <td class="font-weight-bold">Age/Gender:</td>
                                <td><u>{{$f->patient->getAge()}} / {{$f->patient->sg()}}</u></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-borderless" style="margin-top: -20px;">
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">Address:</td>
                                <td><u>{{$f->patient->getAddressMini()}}</u></td>
                            </tr>
                        </tbody>
                    </table>

                    <ul>
                        <b>History of Exposure</b>
                        <li class="ml-5"><b>Date of Exposure:</b> <u>{{date('m/d/Y', strtotime($f->bite_date))}}</u></li>
                        <li class="ml-5"><b>Place of Exposure:</b> <u>{{$f->case_location}}</u></li>
                        <li class="ml-5"><b>Type of Exposure:</b> <u>{{$f->getBiteType()}} {{(!is_null($f->body_site)) ? ' / '.$f->body_site : ''}}</u></li>
                        <li class="ml-5"><b>Source of Exposure:</b> <u>{{$f->getSource()}}</u></li>
                    </ul>

                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td><b>Category of Exposure:</b> <u>{{($f->d3_done == 0) ? '__________' : $f->category_level}}</u></td>
                                <td><b>Post Exposure Prophylaxis:</b> <u>Y {{($f->is_booster == 1) ? ' - BOOSTER' : ''}}</u></td>
                            </tr>
                            <tr>
                                <td><b>A. Washing of Bite Wound:</b> <u>{{($f->washing_of_bite == 1) ? 'Y' : 'N'}}</u></td>
                                <td><b>B. RIG:</b> <u>{{$f->showRig()}}</u></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <table class="table table-bordered text-center table-sm">
                        <tbody>
                            <thead class="thead-light text-center">
                                <th colspan="3">Generic Name: <u>{{$f->getGenericName()}}</u> | Brand Name: <u>{{$f->brand_name}}</u></th>
                            </thead>
                            <thead class="thead-light">
                                <th>Day</th>
                                <th>Date</th>
                                <th>Signature</th>
                            </thead>
                            <tr class="font-weight-bold">
                                <td>Day 0</td>
                                <td>{{date('m/d/Y (D)', strtotime($f->d0_date))}}</td>
                                <td class="{{($f->d0_done == 1) ? 'text-success' : ''}}"><b>{{($f->d0_done == 1) ? 'DONE' : ''}}</b></td>
                            </tr>
                            <tr class="font-weight-bold">
                                <td>Day 3</td>
                                <td>{{date('m/d/Y (D)', strtotime($f->d3_date))}}</td>
                                <td class="{{($f->d3_done == 1) ? 'text-success' : ''}}"><b>{{($f->d3_done == 1) ? 'DONE' : ''}}</b></td>
                            </tr>
                            @if($f->is_booster != 1)
                            <tr class="font-weight-bold">
                                <td>Day 7</td>
                                <td>{{date('m/d/Y (D)', strtotime($f->d7_date))}}</td>
                                <td class="{{($f->d7_done == 1) ? 'text-success' : ''}}"><b>{{($f->d7_done == 1) ? 'DONE' : ''}}</b></td>
                            </tr>
                            @if($f->pep_route == 'IM')
                            <tr class="font-weight-bold">
                                <td>Day 14 (M)</td>
                                <td>{{date('m/d/Y (D)', strtotime($f->d14_date))}}</td>
                                <td class="{{($f->d14_done == 1) ? 'text-success' : ''}}"><b>{{($f->d14_done == 1) ? 'DONE' : ''}}</b></td>
                            </tr>
                            @endif
                            <tr class="font-weight-bold">
                                <td>
                                    <div>Day 28</div>
                                    <div><small>(If Animal Died/Lost)</small></div>
                                </td>
                                <td style="vertical-align: middle;">{{date('m/d/Y (D)', strtotime($f->d28_date))}}</td>
                                <td class="{{($f->d28_done == 1) ? 'text-success' : ''}}"><b>{{($f->d28_done == 1) ? 'DONE' : ''}}</b></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    @if($f->is_booster != 1)
                    <ul>
                        <small>Note:</small>
                        <li><small>Observe the biting animal for 14 days. If the animal died, report to ABTC as soon as possible.</small></li>
                        <li><small>Day 3, Day 7, and Day 28 <i>(If Animal Died/Lost)</i> is your <b>Follow-up</b> schedule.</small></li>
                    </ul>
                    @endif
                    <p class="mt-3"><b>Status of animal 14 days after exposure:</b> <u>{{$f->biting_animal_status}}</u></p>
                    <hr>
                    <div class="text-center">
                        <h4 style="color: blue"><i>Be a Responsible Pet Owner</i></h4>
                        <h4 style="color: green"><i>Let's Join Forces for a Rabies-free Gentri</i></h4>
                    </div>
                </div>
                <div class="card-footer text-center" id="divFoot">
                    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <ul class="mt-5">
                <h3><b>MGA PAALALA / MGA DAPAT GAWIN KAPAG NAKAGAT O NAKALMOT NG ASO/PUSA</b></h3>
                <li>Hugasan agad ang sugat gamit ng malinis na tubig at sabon sa loob ng 10-15 minutos.</li>
                <li>Linisin ang sugat gamit ng 70% Alcohol/Ethanol at Povidone-iodine (Betadine), kung mayroon.</li>
                <li>Magpa-konsulta agad sa doktor o malapit na Animal Bite Treatment Center (ABTC).</li>
            </ul>
            <ul>
                <h3 class="text-danger"><b>IWASAN ANG MGA SUMUSUNOD</b></h3>
                <li>Huwag sipsipin ang sugat gamit ang bibig.</li>
                <li>Huwag lagyan ng bawang, barya, o bato sa sugat.</li>
                <li>Huwag magpagamot sa tandok ng kagat/kalmot ng aso/pusa.</li>
                <li>Huwag balutan ang sugat ng damit o bandahe.</li>
                <li>Huwag balewalain ang kagat ng hayop.</li>
            </ul>
            <p>Bigyan ng pansin at seryosohin ang potensyal na exposure sa rabies.</p>
            <p>Kapag lumabas ang sintomas, kamatayan ay halos di na maiiwasan pa.</p>
        </div>
    </div>
</div>

<div class="modal fade" id="philhealthForms" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Print Philhealth Forms</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
        <div class="modal-body">
            <form action="{{route('abtc_print_philhealth', $f->id)}}" method="POST">
                @csrf
                <div class="alert alert-primary" role="alert">
                    <h5>Paki-handa na po sa pasyente ang kopya ng kanyang <b>Philhealth Member Data Record (MDR)</b>, <b>Valid ID</b>, at <b>Philhealth Benefit Eligibility Form</b></h5>
                </div>
                <h5><b>Patient Name:</b> {{$f->patient->getName()}}</h5>
                <h5><b>Age/Gender: </b> {{$f->patient->getAgeInt()}} / {{$f->patient->gender}}</h5>
                <h5><b>Birthdate:</b> {{date('m/d/Y', strtotime($f->patient->bdate))}}</h5>
                <h5 class="mb-3"><b>Contact No.:</b> {{$f->patient->contact_number}}</h5>
                <h5><b>Date Admitted:</b> {{date('m/d/Y', strtotime($f->d0_date))}}</h5>
                <hr>
                <div class="form-group">
                    <label for="status_type"><b class="text-danger">*</b>Philhealth Membership Type</label>
                    <select class="form-control" name="philhealth_statustype" id="philhealth_statustype" required>
                        @if($f->patient->getAgeInt() <= 19)
                        <option value="DEPENDENT" {{(old('philhealth_statustype', $f->patient->philhealth_statustype) == 'DEPENDENT') ? 'selected' : ''}}>Dependent (Wala pang Philhealth Account)</option>
                        @else
                        <option value="MEMBER" {{(old('philhealth_statustype', $f->patient->philhealth_statustype) == 'MEMBER') ? 'selected' : ''}}>Member (May sarili nang Philhealth Account)</option>
                        <option value="DEPENDENT" {{(old('philhealth_statustype', $f->patient->philhealth_statustype) == 'DEPENDENT') ? 'selected' : ''}}>Dependent (sa Asawa)</option>
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="philhealth" class="form-label">@if(($f->category_level  == 3))<b class="text-danger">*</b>@endif Philhealth Number (PIN) of the <span id="ph_text"></span></label>
                    <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth', $f->patient->philhealth)}}" pattern="[0-9]{12}" {{($f->category_level  == 3) ? 'required' : ''}}>
                </div>
                
                <div id="ifDependentDiv" class="d-none">
                    <hr>
                    <div class="alert alert-info" role="alert">
                        Paki-lagay ang detalye ng Philhealth Member kung saan naka-declare ang Patient (halimbawa: Nanay/Mother, Tatay/Father, o Asawa/Spouse). Makikita ito sa MDR na ip-provide ng Patient.
                    </div>
                    <div class="form-group">
                        <label for="linkphilhealth_phnumber" class="form-label"><b class="text-danger">*</b>Philhealth Number (PIN) of the Member</label>
                        <input type="text" class="form-control" id="linkphilhealth_phnumber" name="linkphilhealth_phnumber" value="{{old('linkphilhealth_phnumber', $f->patient->linkphilhealth_phnumber)}}" pattern="[0-9]{12}">
                    </div>
                    <div class="form-group">
                        <label for="linkphilhealth_lname"><b class="text-danger">*</b>Last Name of Philhealth Member</label>
                        <input type="text" class="form-control" id="linkphilhealth_lname" name="linkphilhealth_lname" value="{{old('linkphilhealth_lname', $f->patient->linkphilhealth_lname)}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                    </div>
                    <div class="form-group">
                        <label for="linkphilhealth_fname"><b class="text-danger">*</b>First Name of Philhealth Member</label>
                        <input type="text" class="form-control" id="linkphilhealth_fname" name="linkphilhealth_fname" value="{{old('linkphilhealth_fname', $f->patient->linkphilhealth_fname)}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="linkphilhealth_mname"><b class="text-danger">*</b>Middle Name of Philhealth Member</label>
                                <input type="text" class="form-control" id="linkphilhealth_mname" name="linkphilhealth_mname" value="{{old('linkphilhealth_mname', $f->patient->linkphilhealth_mname)}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ/]+">
                                <i><small>(Type <span class="text-danger">N/A</span> if Not Applicable)</small></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="linkphilhealth_suffix"><b class="text-danger">*</b>Name Extension of Philhealth Member</label>
                                <select class="form-control" name="linkphilhealth_suffix" id="linkphilhealth_suffix">
                                    <option value="N/A" {{is_null(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix)) ? 'selected' : ''}}>N/A (NOT APPLICABLE)</option>
                                    <option value="I" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'I') ? 'selected' : ''}}>I</option>
                                    <option value="II" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'II') ? 'selected' : ''}}>II</option>
                                    <option value="III" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'III') ? 'selected' : ''}}>III</option>
                                    <option value="IV" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'IV') ? 'selected' : ''}}>IV</option>
                                    <option value="V" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'V') ? 'selected' : ''}}>V</option>
                                    <option value="VI" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'VI') ? 'selected' : ''}}>VI</option>
                                    <option value="VII" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'VII') ? 'selected' : ''}}>VII</option>
                                    <option value="VIII" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'VIII') ? 'selected' : ''}}>VIII</option>
                                    <option value="JR" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'JR') ? 'selected' : ''}}>JR</option>
                                    <option value="JR II" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'JR II') ? 'selected' : ''}}>JR II</option>
                                    <option value="SR" {{(old('linkphilhealth_suffix', $f->patient->linkphilhealth_suffix) == 'SR') ? 'selected' : ''}}>SR</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="linkphilhealth_bdate"><span class="text-danger font-weight-bold">*</span>Birthdate of Philhealth Member</label>
                                <input type="date" class="form-control" id="linkphilhealth_bdate" name="linkphilhealth_bdate" value="{{old('linkphilhealth_bdate', $f->patient->linkphilhealth_bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('-21 Days'))}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="linkphilhealth_sex"><b class="text-danger">*</b>Gender of Philhealth Member</label>
                                <select class="form-control" name="linkphilhealth_sex" id="linkphilhealth_sex">
                                    <option value="" disabled {{is_null(old('linkphilhealth_sex', $f->patient->linkphilhealth_suffix)) ? 'selected' : ''}}>Choose...</option>
                                    <option value="M" {{(old('linkphilhealth_sex', $f->patient->linkphilhealth_sex) == 'M') ? 'selected' : ''}}>Male</option>
                                    <option value="F" {{(old('linkphilhealth_sex', $f->patient->linkphilhealth_sex) == 'F') ? 'selected' : ''}}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="linkphilhealth_relationship"><b class="text-danger">*</b>Type of Patient</label>
                        <select class="form-control" name="linkphilhealth_relationship" id="linkphilhealth_relationship">
                            <option value="" disabled {{is_null(old('linkphilhealth_relationship', $f->patient->linkphilhealth_relationship)) ? 'selected' : ''}}>Choose...</option>
                            <option value="CHILD" {{(old('linkphilhealth_relationship', $f->patient->linkphilhealth_relationship) == 'CHILD') ? 'selected' : ''}}>Child (Anak)</option>
                            <option value="PARENT" {{(old('linkphilhealth_relationship', $f->patient->linkphilhealth_relationship) == 'PARENT') ? 'selected' : ''}}>Parent (Magulang)</option>
                            <option value="SPOUSE" {{(old('linkphilhealth_relationship', $f->patient->linkphilhealth_relationship) == 'SPOUSE') ? 'selected' : ''}}>Spouse (Asawa)</option>
                        </select>
                    </div>
                </div>
                <div id="penDiv" class="{{($f->category_level == 3) ? '' : 'd-none'}}">
                    <hr>
                    <div class="form-group">
                        <label for="linkphilhealth_pen"><span id="penSpan"></span></label>
                        <input type="text" class="form-control" id="linkphilhealth_pen" name="linkphilhealth_pen" value="{{old('linkphilhealth_pen', $f->patient->linkphilhealth_pen)}}" pattern="[0-9]{12}">
                    </div>
                    <div class="form-group">
                        <label for="linkphilhealth_businessname"><span id="employerNameSpan"></span></label>
                        <input type="text" class="form-control" id="linkphilhealth_businessname" name="linkphilhealth_businessname" value="{{old('linkphilhealth_businessname', $f->patient->linkphilhealth_businessname)}}" minlength="5" maxlength="200" style="text-transform: uppercase;">
                    </div>
                </div>
                <hr>
                @if($f->category_level == 3)
                <div class="form-group">
                    <label for="vaccinator_name" class="form-label"><b class="text-danger">*</b>Name of Vaccinator</label>
                    <select class="form-control" name="vaccinator_name" id="vaccinator_name" required>
                        <option value="" disabled {{is_null(old('vaccinator_name')) ? 'selected' : ''}}>Choose...</option>
                        @foreach(App\Models\Employee::where('abtc_vaccinator_branch', auth()->user()->abtc_default_vaccinationsite_id)->get() as $v)
                        <option value="{{$v->getNameWithPr()}}">{{$v->getNameWithPr()}}</option>
                        @endforeach
                  </select>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary btn-block" name="submit" value="card">Print ABTC Card</button>
                <button type="submit" class="btn btn-primary btn-block" name="submit" value="soa">Print SOA</button>
                <button type="submit" class="btn btn-primary btn-block" name="submit" value="cf2">Print CF2</button>
                <button type="submit" class="btn btn-primary btn-block" name="submit" value="csf">Print CSF</button>
                @elseif($f->category_level == 2)
                <button type="submit" class="btn btn-primary btn-block" name="submit" value="ekonsulta">Print eKonsulta</button>
                @endif
            </form>

            <form action="{{route('abtc_print_philhealth', $f->id)}}" method="POST">
                @csrf
                <hr>
                <div class="alert alert-info" role="alert">
                    <b>Note:</b> Kung ang 1st Dose ni Patient ay galing sa ibang facility, i-print at papirmahan ang <b>Tranfer Waiver</b> sa ika-huling araw ng kanyang bakuna.
                </div>
                <div class="form-group">
                    <label for="d0_facility_name"><b class="text-danger">*</b>Facility Name kung saan nag-1st Dose si Patient</label>
                    <input type="text" class="form-control" name="d0_facility_name" id="d0_facility_name" style="text-transform: uppercase;" required>
                    <small><b>Note:</b> Isulat ang buong pangalan ng facility kung saan nagpabakuna ng 1st Dose ang Pasyente, wag isulat ang acronym lamang.</small>
                </div>
                <button type="submit" class="btn btn-primary btn-block" name="submit" value="transfer_waiver">Print Waiver</button>
            </form>
            </div>
        </div>
    </div>
</div>

<script>
    @if(request()->input('t'))
        $(document).ready(function () {
            $('#printnew').click();
        });
    @endif

    $('#philhealth_statustype').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'MEMBER' || $(this).val() == '') {
            $('#ifDependentDiv').addClass('d-none');
            $('#employerNameSpan').text('Name of Workplace/Business');
            $('#penSpan').text('Business/Workplace Philhealth Employer Number (PEN #)');

            $('#linkphilhealth_lname').prop('required', false);
            $('#linkphilhealth_fname').prop('required', false);
            $('#linkphilhealth_mname').prop('required', false);
            $('#linkphilhealth_suffix').prop('required', false);
            $('#linkphilhealth_sex').prop('required', false);
            $('#linkphilhealth_bdate').prop('required', false);
            $('#linkphilhealth_phnumber').prop('required', false);
            $('#linkphilhealth_relationship').prop('required', false);
            $('#ph_text').text('Member');
        }
        else if($(this).val() == 'DEPENDENT') {
            $('#ifDependentDiv').removeClass('d-none');
            $('#employerNameSpan').text('Name of Workplace/Business of Member');
            $('#penSpan').text('Business/Workplace Philhealth Employer Number (PEN #) of Member');

            $('#linkphilhealth_lname').prop('required', true);
            $('#linkphilhealth_fname').prop('required', true);
            $('#linkphilhealth_mname').prop('required', true);
            $('#linkphilhealth_suffix').prop('required', true);
            $('#linkphilhealth_sex').prop('required', true);
            $('#linkphilhealth_bdate').prop('required', true);
            $('#linkphilhealth_phnumber').prop('required', true);
            $('#linkphilhealth_relationship').prop('required', true);

            $('#ph_text').text('Dependent (hanapin ang pangalan ng pasyente sa list of Dependents sa MDR)');
        }
    }).trigger('change');
</script>
@endsection