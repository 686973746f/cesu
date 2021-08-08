@extends('layouts.app')

@section('content')
    @php
    $proceed = 1;
    @endphp
    @if($proceed == 1)
    <form action="{{route('selfreport.store')}}" method="POST" id="myForm" name="wholeForm" enctype="multipart/form-data">
        @csrf
        <div class="container" style="font-family: Arial, Helvetica, sans-serif">

            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{$error}}</p>
                    <hr>
                @endforeach
            </div>
            <hr>
            @endif
            
            <div class="card mb-3">
                <div class="card-header text-center font-weight-bold"><h4>Self-Report</h4></div>
                <div class="card-body">
                    <div id="carouselId" class="carousel slide mb-3" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselId" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselId" data-slide-to="1"></li>
                            <li data-target="#carouselId" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <img data-src="holder.js/1280x720/auto/#777:#555/text:First slide" alt="First slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h3>Title</h3>
                                    <p>Description</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img data-src="holder.js/1280x720/auto/#666:#444/text:Second slide" alt="Second slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h3>Title</h3>
                                    <p>Description</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img data-src="holder.js/1280x720/auto/#666:#444/text:Third slide" alt="Third slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h3>Title</h3>
                                    <p>Description</p>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselId" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselId" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header font-weight-bold">1. Patient Information</div>
                <div class="card-body">
                    <div class="card mb-3">
                        <div class="card-header">Personal Information</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lname"><span class="text-danger font-weight-bold">*</span>{{ __('selfreport.lastname') }}</label>
                                        <input type="text" class="form-control @error('lname') border-danger @enderror" id="lname" name="lname" value="{{old('lname')}}" max="50" style="text-transform: uppercase;" required>
                                        @error('lname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fname"><span class="text-danger font-weight-bold">*</span>First Name (and Suffix)</label>
                                        <input type="text" class="form-control @error('fname') border-danger @enderror" id="fname" name="fname" value="{{old('fname')}}" max="50" style="text-transform: uppercase;" required>
                                        @error('fname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="mname">Middle Name <small><i>(Leave blank if N/A)</i></small></label>
                                        <input type="text" class="form-control" id="mname" name="mname" value="{{old('mname')}}" style="text-transform: uppercase;" max="50">
                                        @error('mname')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bdate"><span class="text-danger font-weight-bold">*</span>Birthdate</label>
                                        <input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate')}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" required>
                                        @error('bdate')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="gender"><span class="text-danger font-weight-bold">*</span>Gender</label>
                                        <select class="form-control" id="gender" name="gender" required>
                                            <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="MALE" {{(old('gender') == 'MALE') ? 'selected' : ''}}>Male</option>
                                            <option value="FEMALE" {{(old('gender') == 'FEMALE') ? 'selected' : ''}}>Female</option>
                                        </select>
                                        @error('gender')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                    <div id="ifGenderFemale">
                                        <div class="form-group">
                                            <label for="isPregnant"><span class="text-danger font-weight-bold">*</span>Are you Pregnant?</label>
                                            <select class="form-control" name="isPregnant" id="isPregnant">
                                                <option value="" disabled {{(is_null(old('isPregnant'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="0" {{(old('isPregnant') == '0') ? 'selected' : ''}}>No</option>
                                                <option value="1" {{(old('isPregnant') == '1') ? 'selected' : ''}}>Yes</option>
                                            </select>
                                        </div>
                                        <div id="ifPregnant">
                                            <div class="form-group">
                                                <label for="lmp"><span class="text-danger font-weight-bold">*</span>Last Menstrual Period (LMP)</label>
                                                <input type="date" class="form-control" name="lmp" id="lmp" value="{{old('lmp')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cs"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
                                        <select class="form-control" id="cs" name="cs" required>
                                            <option value="" disabled {{(is_null(old('cs'))) ? 'selected' : ''}}>Pumili / Choose</option>
                                            <option value="SINGLE" @if(old('cs') == 'SINGLE') {{'selected'}} @endif>Single</option>
                                            <option value="MARRIED" @if(old('cs') == 'MARRIED') {{'selected'}} @endif>Married</option>
                                            <option value="WIDOWED" @if(old('cs') == 'WIDOWED') {{'selected'}} @endif>Widowed</option>
                                            <option value="N/A" @if(old('cs') == 'N/A') {{'selected'}} @endif>N/A</option>
                                        </select>
                                        @error('cs')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="nationality"><span class="text-danger font-weight-bold">*</span>Nationality</label>
                                        <select class="form-control" id="nationality" name="nationality" required>
                                            <option value="Filipino" @if(old('nationality') == 'Filipino' || empty(old('nationality'))) {{'selected'}} @endif>Filipino</option>
                                            <option value="Foreign" @if(old('nationality') == 'Foreign') {{'selected'}} @endif>Foreign</option>
                                        </select>
                                        @error('nationality')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile"><span class="text-danger font-weight-bold">*</span>Mobile Number <small>(Format: 09*********)</small></label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile')}}" pattern="[0-9]{11}" placeholder="0917xxxxxxx" required>
                                        @error('mobile')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="philhealth">Philhealth Number <small><i>(Leave blank if N/A)</i></small></label>
                                        <input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" minlength="12" maxlength="14">
                                        @error('philhealth')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phoneno">Telephone Number (& Area Code) <small><i>(Leave blank if N/A)</i></small></label>
                                        <input type="text" class="form-control" id="phoneno" name="phoneno" value="{{old('phoneno')}}">
                                        @error('phoneno')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email Address <small><i>(Leave blank if N/A)</i></small></label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}">
                                        @error('email')
                                                <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div id="addresstext">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address_province" id="address_province" value="{{old('address_province')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address_city" id="address_city" value="{{old('address_city')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address_provincejson" id="address_provincejson" value="{{old('address_provincejson')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address_cityjson" id="address_cityjson" value="{{old('address_cityjson')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="saddress_province"><span class="text-danger font-weight-bold">*</span>Province</label>
                                        <select class="form-control" name="saddress_province" id="saddress_province" required>
                                            <option value="" selected disabled>Choose...</option>
                                        </select>
                                            @error('saddress_province')
                                                <small class="text-danger">{{$message}}</small>
                                            @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="saddress_city"><span class="text-danger font-weight-bold">*</span>City or Municipality</label>
                                        <select class="form-control" name="saddress_city" id="saddress_city" required>
                                        <option value="" selected disabled>Choose...</option>
                                        </select>
                                        @error('saddress_city')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address_brgy"><span class="text-danger font-weight-bold">*</span>Barangay</label>
                                        <select class="form-control" name="address_brgy" id="address_brgy" required>
                                        <option value="" selected disabled>Choose...</option>
                                        </select>
                                            @error('address_brgy')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_houseno"><span class="text-danger font-weight-bold">*</span>House No./Lot/Building</label>
                                        <input type="text" class="form-control" id="address_houseno" name="address_houseno" style="text-transform: uppercase;" value="{{old('address_houseno')}}" required>
                                        @error('address_houseno')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address_street"><span class="text-danger font-weight-bold">*</span>Street/Purok/Sitio/Subdivision</label>
                                        <input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" required>
                                        <small class="text-muted">Kung N/A, lagyan ng pinakamalapit na establisyemento kung saan ka nakatira (e.g Near Brgy. Hall, Near Alfamart, Near Tulay, Near Ilog, etc.)</small>
                                        @error('address_street')
                                            <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                              <label for="req_file">Upload Requirements <small>(Valid IDs, Birth Certifate, etc.)</small></label>
                              <input type="file" class="form-control-file" name="req_file" id="req_file">
                              <small class="form-text text-muted">Accepted file formats: JPG, JPEG, PNG, PDF. Max file size: 5MB</small>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">Work Information</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="haveOccupation"><span class="text-danger font-weight-bold">*</span>Are you currently employed?</label>
                                <select class="form-control" name="haveOccupation" id="haveOccupation" required>
                                    <option value="" disabled {{(is_null(old('haveOccupation'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="1" {{(old('haveOccupation') == '1') ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('haveOccupation') == '0') ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="occupationRow">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="occupation"><span class="text-danger font-weight-bold">*</span>Occupation</label>
                                            <input type="text" class="form-control" name="occupation" id="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="occupation_name">Name of Workplace</label>
                                            <input type="text" class="form-control" name="occupation_name" id="occupation_name" value="{{old('occupation_name')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-4"> 
                                        <div class="form-group">
                                            <label for="natureOfWork"><span class="text-danger font-weight-bold">*</span>Nature of Work</label>
                                            <select class="form-control" name="natureOfWork" id="natureOfWork">
                                                <option value="" disabled {{(is_null(old('natureOfWork'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="AGRICULTURE" {{(old('natureOfWork') == 'AGRICULTURE') ? 'selected' : ''}}>Agriculture</option>
                                                <option value="BPO" {{(old('natureOfWork') == 'BPO') ? 'selected' : ''}}>BPO (Outsourcing E.G. eTelecare Global Sol. Inc)</option>
                                                <option value="COMMUNICATIONS" {{(old('natureOfWork') == 'COMMUNICATIONS') ? 'selected' : ''}}>Communications (E.G. PLDT)</option>
                                                <option value="CONSTRUCTION" {{(old('natureOfWork') == 'CONSTRUCTION') ? 'selected' : ''}}>Construction (E.G. Makati Dev Corp)</option>
                                                <option value="EDUCATION" {{(old('natureOfWork') == 'EDUCATION') ? 'selected' : ''}}>Education (E.G. DLSU)</option>
                                                <option value="ELECTRICITY" {{(old('natureOfWork') == 'ELECTRICITY') ? 'selected' : ''}}>Electricity</option>
                                                <option value="FINANCIAL" {{(old('natureOfWork') == 'FINANCIAL') ? 'selected' : ''}}>Financial (E.G. Banks)</option>
                                                <option value="GOVERNMENT UNITS/ORGANIZATIONS" {{(old('natureOfWork') == 'GOVERNMENT UNITS/ORGANIZATIONS') ? 'selected' : ''}}>Government Units/Organizations (E.G. GSIS)</option>
                                                <option value="HOTEL AND RESTAURANT" {{(old('natureOfWork') == 'HOTEL AND RESTAURANT') ? 'selected' : ''}}>Hotel and Restaurant (E.G. Jollibee Foods Corp)</option>
                                                <option value="MANNING/SHIPPING AGENCY" {{(old('natureOfWork') == 'MANNING/SHIPPING AGENCY') ? 'selected' : ''}}>Manning/Shipping Agency (E.G. Fil Star Maritime)</option>
                                                <option value="MANUFACTURING" {{(old('natureOfWork') == 'MANUFACTURING') ? 'selected' : ''}}>Manufacturing (E.G. Nestle Phils Inc)</option>
                                                <option value="MEDICAL AND HEALTH SERVICES" {{(old('natureOfWork') == 'MEDICAL AND HEALTH SERVICES') ? 'selected' : ''}}>Medical and Health Services</option>
                                                <option value="MICROFINANCE" {{(old('natureOfWork') == 'MICROFINANCE') ? 'selected' : ''}}>Microfinance (E.G. Ahon sa Hirap Inc)</option>
                                                <option value="MINING AND QUARRYING" {{(old('natureOfWork') == 'MINING AND QUARRYING') ? 'selected' : ''}}>Mining and Quarrying (E.G. Philex Mining Corp)</option>
                                                <option value="NON PROFIT ORGANIZATIONS" {{(old('natureOfWork') == 'NON PROFIT ORGANIZATIONS') ? 'selected' : ''}}>Non Profit Organizations (E.G. Iglesia Ni Cristo)</option>
                                                <option value="REAL ESTATE" {{(old('natureOfWork') == 'REAL ESTATE') ? 'selected' : ''}}>Real Estate (E.G. Megaworld Corp)</option>
                                                <option value="STORAGE" {{(old('natureOfWork') == 'STORAGE') ? 'selected' : ''}}>Storage (Include Freight Forwarding E.G. Dhl)</option>
                                                <option value="TRANSPORTATION" {{(old('natureOfWork') == 'TRANSPORTATION') ? 'selected' : ''}}>Transportation (E.G. Philippine Airlines)</option>
                                                <option value="WHOLESALE AND RETAIL TRADE" {{(old('natureOfWork') == 'WHOLESALE AND RETAIL TRADE') ? 'selected' : ''}}>Wholesale and Retail Trade (E.G. Mercury Drug)</option>
                                                <option value="OTHERS" {{(old('natureOfWork') == 'OTHERS') ? 'selected' : ''}}>Others (Specify)</option>
                                            </select>
                                                @error('natureOfWork')
                                                <small class="text-danger">{{$message}}</small>
                                                @enderror
                                        </div>
                                        <div id="specifyWorkNatureDiv">
                                            <div class="form-group">
                                                <label for="natureOfWorkIfOthers"><span class="text-danger font-weight-bold">*</span>Please specify</label>
                                                <input type="text" class="form-control" name="natureOfWorkIfOthers" id="natureOfWorkIfOthers" value="{{old('natureOfWorkIfOthers')}}" style="text-transform: uppercase;">
                                                @error('natureOfWorkIfOthers')
                                                <small class="text-danger">{{$message}}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Special Population</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="isHealthCareWorker"><span class="text-danger font-weight-bold">*</span>Health Care Worker</label>
                                <select class="form-control" name="isHealthCareWorker" id="isHealthCareWorker" required>
                                    <option value="" disabled {{(is_null(old('isHealthCareWorker'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="1" {{(old('isHealthCareWorker') == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('isHealthCareWorker') == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="divisHealthCareWorker">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="healthCareCompanyName"><span class="text-danger font-weight-bold">*</span>Name of Health Facility</label>
                                            <input type="text" class="form-control" name="healthCareCompanyName" id="healthCareCompanyName" value="{{old('healthCareCompanyName')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="healthCareCompanyLocation"><span class="text-danger font-weight-bold">*</span>Location</label>
                                            <input type="text" class="form-control" name="healthCareCompanyLocation" id="healthCareCompanyLocation" value="{{old('healthCareCompanyLocation')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="isOFW"><span class="text-danger font-weight-bold">*</span>Returning Overseas Filipino</label>
                                <select class="form-control" name="isOFW" id="isOFW" required>
                                    <option value="" disabled {{(is_null(old('isOFW'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="1" {{(old('isOFW') == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('isOFW') == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="divisOFW">
                                <div class="form-group">
                                    <label for="OFWCountyOfOrigin"><span class="text-danger font-weight-bold">*</span>Country of Origin</label>
                                    <select class="form-control" name="OFWCountyOfOrigin" id="OFWCountyOfOrigin">
                                        <option value="" disabled {{(is_null(old('OFWCountyOfOrigin'))) ? 'selected' : ''}}>Choose...</option>
                                        @foreach ($countries as $country)
                                            @if($country != 'Philippines')
                                                <option value="{{$country}}" {{(old('OFWCountyOfOrigin') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="OFWPassportNo"><span class="text-danger font-weight-bold">*</span>Passport No.</label>
                                    <input type="text" class="form-control" name="OFWPassportNo" id="OFWPassportNo" value="{{old('OFWPassportNo')}}" style="text-transform: uppercase;">
                                </div>
                                <div class="form-group">
                                    <label for="ofwType"><span class="text-danger font-weight-bold">*</span>OFW?</label>
                                    <select class="form-control" name="ofwType" id="ofwType">
                                        <option value="" disabled {{(is_null(old('ofwType'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="1" {{(old('ofwType') == "YES") ? 'selected' : ''}}>Yes</option>
                                        <option value="2" {{(old('ofwType') == "NO") ? 'selected' : ''}}>No (Non-OFW)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="isFNT"><span class="text-danger font-weight-bold">*</span>Foreign National Traveler</label>
                                <select class="form-control" name="isFNT" id="isFNT" required>
                                    <option value="" disabled {{(is_null(old('isFNT'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="1" {{(old('isFNT') == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('isFNT') == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="divisFNT">
                                <div class="form-group">
                                    <label for="FNTCountryOfOrigin"><span class="text-danger font-weight-bold">*</span>Country of Origin</label>
                                    <select class="form-control" name="FNTCountryOfOrigin" id="FNTCountryOfOrigin">
                                        <option value="" selected disabled>Choose...</option>
                                        @foreach ($countries as $country)
                                            @if($country != 'Philippines')
                                                <option value="{{$country}}" {{(old('FNTCountryOfOrigin') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="FNTPassportNo"><span class="text-danger font-weight-bold">*</span>Passport No.</label>
                                    <input type="text" class="form-control" name="FNTPassportNo" id="FNTPassportNo" value="{{old('FNTPassportNo')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="isLSI"><span class="text-danger font-weight-bold">*</span>Locally Stranded Individual/APOR/Traveler</label>
                                <select class="form-control" name="isLSI" id="isLSI" required>
                                    <option value="" disabled {{(is_null(old('isLSI'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="1" {{(old('isLSI') == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('isLSI') == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="divisLSI">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="LSIProvince"><span class="text-danger font-weight-bold">*</span>Province of Origin</label>
                                            <select class="form-control" name="LSIProvince" id="LSIProvince">
                                                <option value="" selected disabled>Choose...</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="LSICity"><span class="text-danger font-weight-bold">*</span>City of Origin</label>
                                            <select class="form-control" name="LSICity" id="LSICity">
                                                    <option value="" selected disabled>Choose...</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lsiType"><span class="text-danger font-weight-bold">*</span>Type</label>
                                    <select class="form-control" name="lsiType" id="lsiType">
                                        <option value="" disabled {{(is_null(old('lsiType'))) ? 'selected' : ''}}>Choose...</option>
                                        <option value="1" {{(old('lsiType') == 1) ? 'selected' : ''}}>Locally Stranted Individual</option>
                                        <option value="0" {{(old('lsiType') == 2) ? 'selected' : ''}}>Authorized Person Outside Residence/Local Traveler</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="isLivesOnClosedSettings"><span class="text-danger font-weight-bold">*</span>Lives in Closed Settings</label>
                                <select class="form-control" name="isLivesOnClosedSettings" id="isLivesOnClosedSettings" required>
                                    <option value="" disabled {{(is_null(old('isLivesOnClosedSettings'))) ? 'selected' : ''}}>Choose...</option>
                                    <option value="1" {{(old('isLivesOnClosedSettings') == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('isLivesOnClosedSettings') == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="divisLivesOnClosedSettings">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="institutionType"><span class="text-danger font-weight-bold">*</span>Specify Institution Type</label>
                                            <input type="text" class="form-control" name="institutionType" id="institutionType" value="{{old('institutionType')}}" style="text-transform: uppercase;">
                                            <small><i>(e.g. prisons, residential facilities, retirement communities, care homes, camps etc.)</i></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="institutionName"><span class="text-danger font-weight-bold">*</span>Name of Institution</label>
                                            <input type="text" class="form-control" name="institutionName" id="institutionName" value="{{old('institutionName')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header font-weight-bold">2. Case Investigation Details</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="pType"><span class="text-danger font-weight-bold">*</span>Type of Client</label>
                        <select class="form-control" name="pType" id="pType" required>
                            <option value="" disabled selected>Choose...</option>
                            <option value="PROBABLE" @if(old('pType') == "PROBABLE"){{'selected'}}@endif>Suspected</option>
                            <option value="CLOSE CONTACT" @if(old('pType') == "CLOSE CONTACT"){{'selected'}}@endif>Close Contact</option>
                            <option value="TESTING" @if(old('pType') == "TESTING"){{'selected'}}@endif>Not A Case of COVID</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="patientmsg">Personal Message to CESU Staff/Encoders <small>(Optional)</small></label>
                        <textarea class="form-control" name="patientmsg" id="patientmsg" rows="3">{{old('patientmsg')}}</textarea>
                      </div>
                    <div class="card mb-3">
                        <div class="card-header">Consultation Information</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="havePreviousCovidConsultation"><span class="text-danger font-weight-bold">*</span>Have previous COVID-19 related consultation?</label>
                                <select class="form-control" name="havePreviousCovidConsultation" id="havePreviousCovidConsultation" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="1" {{(old('havePreviousCovidConsultation') == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(old('havePreviousCovidConsultation') == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                            <div id="divYes1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="facilityNameOfFirstConsult"><span class="text-danger font-weight-bold">*</span>Name of facility where first consult was done</label>
                                            <input type="text" class="form-control" name="facilityNameOfFirstConsult" id="facilityNameOfFirstConsult" value="{{old('facilityNameOfFirstConsult')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dateOfFirstConsult"><span class="text-danger font-weight-bold">*</span>Date of First Consult</label>
                                            <input type="date" class="form-control" name="dateOfFirstConsult" id="dateOfFirstConsult" value="{{old('dateOfFirstConsult')}}" max="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Disposition at Time of Report</div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="dispositionType"><span class="text-danger font-weight-bold">*</span>Status</label>
                                <select class="form-control" name="dispositionType" id="dispositionType" required>
                                    <option value="" {{(is_null(old('dispositionType'))) ? 'selected' : ''}}>N/A</option>
                                    <option value="1" {{(old('dispositionType') == 1) ? 'selected' : ''}}>Admitted in hospital</option>
                                    <option value="2" {{(old('dispositionType') == 2) ? 'selected' : ''}}>Admitted in isolation/quarantine facility</option>
                                    <option value="3" {{(old('dispositionType') == 3) ? 'selected' : ''}}>In home isolation/quarantine</option>
                                    <option value="4" {{(old('dispositionType') == 4) ? 'selected' : ''}}>Discharged to home</option>
                                    <option value="5" {{(old('dispositionType') == 5) ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                            <div id="divYes5">
                                <div class="form-group">
                                    <label for="dispositionName" id="dispositionlabel"></label>
                                    <input type="text" class="form-control" name="dispositionName" id="dispositionName" value="{{old('dispositionName')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div id="divYes6">
                                <div class="form-group">
                                    <label for="dispositionDate" id="dispositiondatelabel"></label>
                                    <input type="datetime-local" class="form-control" name="dispositionDate" id="dispositionDate" value="{{old('dispositionDate')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header font-weight-bold">3. COVID-19 Vaccination Information</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="vaccineq1"><span class="text-danger font-weight-bold">*</span>Ikaw ba ay bakunado na kontra COVID-19? / Are you currently vaccinated againts COVID-19?</label>
                        <select class="form-control" name="vaccineq1" id="vaccineq1">
                        <option value="" disabled {{is_null(old('vaccineq1')) ? 'selected' : ''}}>Choose...</option>
                        <option value="1" {{(old('vaccineq1') == '1') ? 'selected' : ''}}>Oo / Yes</option>
                        <option value="0" {{(old('vaccineq1') == '0') ? 'selected' : ''}}>Hindi / No</option>
                        </select>
                    </div>
                    <div id="ifVaccinated">
                        <div class="form-group">
                            <label for="howManyDose"><span class="text-danger font-weight-bold">*</span>Ilang Dose na ang nakumpleto? / How many Dose have you completed?</label>
                            <select class="form-control" name="howManyDose" id="howManyDose">
                            <option value="" disabled {{is_null(old('howManyDose')) ? 'selected' : ''}}>Choose...</option>
                            <option value="1" {{(old('howManyDose') == '1') ? 'selected' : ''}}>1st Dose</option>
                            <option value="2" {{(old('howManyDose') == '2') ? 'selected' : ''}}>2nd Dose</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nameOfVaccine"><span class="text-danger font-weight-bold">*</span>Pangalan ng Bakuna / Name of Vaccine</label>
                            <select class="form-control" name="nameOfVaccine" id="nameOfVaccine">
                            <option value="" disabled {{is_null(old('nameOfVaccine')) ? 'selected' : ''}}>Choose...</option>
                            <option value="ASTRAZENECA" {{(old('nameOfVaccine') == 'ASTRAZENECA') ? 'selected' : ''}}>Astrazeneca</option>
                            <option value="JOHNSON & JOHNSON'S" {{(old('nameOfVaccine') == "JOHNSON & JOHNSON'S") ? 'selected' : ''}}>Johnson & Johnson's</option>
                            <option value="MODERNA" {{(old('nameOfVaccine') == 'MODERNA') ? 'selected' : ''}}>Moderna</option>
                            <option value="PFIZER" {{(old('nameOfVaccine') == 'PFIZER') ? 'selected' : ''}}>Pfizer</option>
                            <option value="SINOFARM" {{(old('nameOfVaccine') == 'SINOFARM') ? 'selected' : ''}}>Sinofarm</option>
                            <option value="SINOVAC" {{(old('nameOfVaccine') == 'SINOVAC') ? 'selected' : ''}}>Sinovac</option>
                            <option value="SPUTNIK V" {{(old('nameOfVaccine') == 'SPUTNIK V') ? 'selected' : ''}}>Sputnik V</option>
                            </select>
                        </div>
                        <div id="VaccineDose1">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vaccinationDate1"><span class="text-danger font-weight-bold">*</span>1.) First (1st) Dose - Date of Vaccination</label>
                                        <input type="date" class="form-control" name="vaccinationDate1" id="vaccinationDate1" value="{{old('vaccinationDate1')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vaccinationFacility1">Vaccination Center/Facility <small>(Optional)</small></label>
                                        <input type="text" class="form-control" name="vaccinationFacility1" id="vaccinationFacility1" value="{{old('vaccinationFacility1')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vaccinationRegion1">Region of Health Facility <small>(Optional)</small></label>
                                        <input type="text" class="form-control" name="vaccinationRegion1" id="vaccinationRegion1" value="{{old('vaccinationRegion1')}}" style="text-transform: uppercase;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="haveAdverseEvents1"><span class="text-danger font-weight-bold">*</span>Adverse Event/s</label>
                                        <select class="form-control" name="haveAdverseEvents1" id="haveAdverseEvents1">
                                            <option value="" disabled {{(is_null(old('haveAdverseEvents1'))) ? 'selected' : ''}}>Choose...</option>
                                            <option value="1" {{(old('haveAdverseEvents1') == '1') ? 'selected' : ''}}>Yes</option>
                                            <option value="0" {{(old('haveAdverseEvents1') == '0') ? 'selected' : ''}}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div id="VaccineDose2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vaccinationDate2"><span class="text-danger font-weight-bold">*</span>2.) Second (2nd) Dose - Date of Vaccination</label>
                                            <input type="date" class="form-control" name="vaccinationDate2" id="vaccinationDate2" value="{{old('vaccinationDate2')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vaccinationFacility2">Vaccination Center/Facility <small>(Optional)</small></label>
                                            <input type="text" class="form-control" name="vaccinationFacility2" id="vaccinationFacility2" value="{{old('vaccinationFacility2')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="vaccinationRegion2">Region of Health Facility <small>(Optional)</small></label>
                                            <input type="text" class="form-control" name="vaccinationRegion2" id="vaccinationRegion2" value="{{old('vaccinationRegion2')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="haveAdverseEvents2"><span class="text-danger font-weight-bold">*</span>Adverse Event/s</label>
                                            <select class="form-control" name="haveAdverseEvents2" id="haveAdverseEvents2">
                                                <option value="" disabled {{(is_null(old('haveAdverseEvents2'))) ? 'selected' : ''}}>Choose...</option>
                                                <option value="1" {{(old('haveAdverseEvents2') == '1') ? 'selected' : ''}}>Yes</option>
                                                <option value="0" {{(old('haveAdverseEvents2') == '0') ? 'selected' : ''}}>No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header font-weight-bold">4. Clinical Information</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="haveSymptoms"><span class="text-danger font-weight-bold">*</span>Kasalukuyan ka bang nakakaranas ng senyales o sintomas ng COVID-19? / Are you currently experiencing any COVID-19 signs or symptoms?</label>
                        <select class="form-control" name="haveSymptoms" id="haveSymptoms">
                        <option value="" disabled {{is_null(old('haveSymptoms')) ? 'selected' : ''}}>Choose...</option>
                        <option value="1" {{(old('haveSymptoms') == '1') ? 'selected' : ''}}>Oo / Yes</option>
                        <option value="0" {{(old('haveSymptoms') == '0') ? 'selected' : ''}}>Hindi / No</option>
                        </select>
                    </div>
                    <div id="ifHaveSymptoms">
                        <div class="form-group">
                            <label for="dateOnsetOfIllness"><span class="text-danger font-weight-bold">*</span>Kailan nagsimula ang Sintomas / Date of Onset of Illness</label>
                            <input type="date" class="form-control" name="dateOnsetOfIllness" id="dateOnsetOfIllness" min="1999-01-01" max="{{date('Y-m-d')}}">
                        </div>
                        <div class="card">
                            <div class="card-header">Senyales at Sintomas (Lagyan ng Check ang mayroon) / Signs and Symptoms (Check all that apply)</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Asymptomatic"
                                                name="sasCheck[]"
                                                id="signsCheck1"
                                                {{(is_array(old('sasCheck')) && in_array("Asymptomatic", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck1">Asymptomatic</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Fever"
                                                name="sasCheck[]"
                                                id="signsCheck2"
                                                {{(is_array(old('sasCheck')) && in_array("Fever", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck2">Lagnat / Fever</label>
                                        </div>
                                        <div id="divFeverChecked">
                                            <div class="form-group mt-2">
                                                <label for="SASFeverDeg"><span class="text-danger font-weight-bold">*</span>Degrees (in Celcius)</label>
                                                <input type="number" class="form-control" name="SASFeverDeg" id="SASFeverDeg" min="1" value="{{old('SASFeverDeg')}}">
                                            </div>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Cough"
                                                name="sasCheck[]"
                                                id="signsCheck3"
                                                {{(is_array(old('sasCheck')) && in_array("Cough", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck3">Ubo / Cough</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="General Weakness"
                                                name="sasCheck[]"
                                                id="signsCheck4"
                                                {{(is_array(old('sasCheck')) && in_array("General Weakness", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck4">Panghihina / General Weakness</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Fatigue"
                                                name="sasCheck[]"
                                                id="signsCheck5"
                                                {{(is_array(old('sasCheck')) && in_array("Fatigue", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck5">Pagkapagod / Fatigue</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Headache"
                                                name="sasCheck[]"
                                                id="signsCheck6"
                                                {{(is_array(old('sasCheck')) && in_array("Headache", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck6">Sakit ng Ulo / Headache</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Myalgia"
                                                name="sasCheck[]"
                                                id="signsCheck7"
                                                {{(is_array(old('sasCheck')) && in_array("Myalgia", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck7">Myalgia</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Sore throat"
                                                name="sasCheck[]"
                                                id="signsCheck8"
                                                {{(is_array(old('sasCheck')) && in_array("Sore throat", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck8">Sakit ng Lalamunan / Sore Throat</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Coryza"
                                                name="sasCheck[]"
                                                id="signsCheck9"
                                                {{(is_array(old('sasCheck')) && in_array("Coryza", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck9">Coryza</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Dyspnea"
                                                name="sasCheck[]"
                                                id="signsCheck10"
                                                {{(is_array(old('sasCheck')) && in_array("Dyspnea", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck10">Dyspnea</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Anorexia"
                                                name="sasCheck[]"
                                                id="signsCheck11"
                                                {{(is_array(old('sasCheck')) && in_array("Anorexia", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck11">Anorexia</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Nausea"
                                                name="sasCheck[]"
                                                id="signsCheck12"
                                                {{(is_array(old('sasCheck')) && in_array("Nausea", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck12">Pagduduwal / Nausea</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Vomiting"
                                                name="sasCheck[]"
                                                id="signsCheck13"
                                                {{(is_array(old('sasCheck')) && in_array("Vomiting", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck13">Nagsusuka / Vomiting</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Diarrhea"
                                                name="sasCheck[]"
                                                id="signsCheck14"
                                                {{(is_array(old('sasCheck')) && in_array("Diarrhea", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck14">Pagdudumi / Diarrhea</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Altered Mental Status"
                                                name="sasCheck[]"
                                                id="signsCheck15"
                                                {{(is_array(old('sasCheck')) && in_array("Altered Mental Status", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck15">Nabago ang Katayuan sa Kaisipan / Altered Mental Status</label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Anosmia (Loss of Smell)"
                                                name="sasCheck[]"
                                                id="signsCheck16"
                                                {{(is_array(old('sasCheck')) && in_array("Anosmia (Loss of Smell)", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck16">Kawalan ng Pang-Amoy / Anosmia <small>(loss of smell, w/o any identified cause)</small></label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Ageusia (Loss of Taste)"
                                                name="sasCheck[]"
                                                id="signsCheck17"
                                                {{(is_array(old('sasCheck')) && in_array("Ageusia (Loss of Taste)", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck17">Kawalan ng Panglasa / Ageusia <small>(loss of taste, w/o any identified cause)</small></label>
                                        </div>
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                value="Others"
                                                name="sasCheck[]"
                                                id="signsCheck18"
                                                {{(is_array(old('sasCheck')) && in_array("Others", old('sasCheck'))) ? 'checked' : ''}}
                                            />
                                            <label class="form-check-label" for="signsCheck18">Iba pa / Others</label>
                                        </div>
                                        <div id="divSASOtherChecked">
                                            <div class="form-group mt-2">
                                                <label for="SASOtherRemarks"><span class="text-danger font-weight-bold">*</span>Tukuyin / Specify Findings</label>
                                                <input type="text" class="form-control" name="SASOtherRemarks" id="SASOtherRemarks" value="{{old('SASOtherRemarks')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card mb-3">
                        <div class="card-header">Comorbidities (Check all that apply if present)</div>
                        <div class="card-body">
                            <div class="row comoOpt">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="None"
                                            name="comCheck[]"
                                            id="comCheck1"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("None", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck1">Wala / None</label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="Hypertension"
                                            name="comCheck[]"
                                            id="comCheck2"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("Hypertension", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck2">Alta-presyon / Hypertension</label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="Diabetes"
                                            name="comCheck[]"
                                            id="comCheck3"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("Diabetes", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck3">Diabetes</label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="Heart Disease"
                                            name="comCheck[]"
                                            id="comCheck4"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("Heart Disease", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck4">Sakit sa Puso / Heart Disease</label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="Lung Disease"
                                            name="comCheck[]"
                                            id="comCheck5"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("Lung Disease", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck5">Sakit sa Baga / Lung Disease</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="Gastrointestinal"
                                            name="comCheck[]"
                                            id="comCheck6"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("Gastrointestinal", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck6">Gastrointestinal</label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="Genito-urinary"
                                            name="comCheck[]"
                                            id="comCheck7"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("Genito-urinary", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck7">Genito-urinary</label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="Neurological Disease"
                                            name="comCheck[]"
                                            id="comCheck8"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("Neurological Disease", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck8">Neurological Disease</label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="Cancer"
                                            name="comCheck[]"
                                            id="comCheck9"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("Cancer", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck9">Cancer</label>
                                    </div>
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            value="Others"
                                            name="comCheck[]"
                                            id="comCheck10"
                                            required
                                            {{(is_array(old('comCheck')) && in_array("Others", old('comCheck'))) ? 'checked' : ''}}
                                        />
                                        <label class="form-check-label" for="comCheck10">Iba pa / Others</label>
                                    </div>
                                    <div id="divComOthersChecked">
                                        <div class="form-group mt-2">
                                            <label for="COMOOtherRemarks"><span class="text-danger font-weight-bold">*</span>Tukuyin / Specify Findings</label>
                                            <input type="text" class="form-control" name="COMOOtherRemarks" id="COMOOtherRemarks" value="{{old('COMOOtherRemarks')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header font-weight-bold">5. Laboratory Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="testedPositiveUsingRTPCRBefore"><span class="text-danger font-weight-bold">*</span>Have you ever tested positive using RT-PCR before?</label>
                                <select class="form-control" name="testedPositiveUsingRTPCRBefore" id="testedPositiveUsingRTPCRBefore" required>
                                    <option value="1" {{(old('testedPositiveUsingRTPCRBefore') == 1) ? 'selected' : ''}}>Yes</option>
                                    <option value="0" {{(is_null(old('testedPositiveUsingRTPCRBefore')) || old('testedPositiveUsingRTPCRBefore') == 0) ? 'selected' : ''}}>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="testedPositiveNumOfSwab"><span class="text-danger font-weight-bold">*</span>Number of previous RT-PCR swabs done</label>
                                <input type="number" class="form-control" name="testedPositiveNumOfSwab" id="testedPositiveNumOfSwab" min="0" value="{{(is_null(old('testedPositiveNumOfSwab'))) ? '0' : old('testedPositiveNumOfSwab')}}" required>
                            </div>
                        </div>
                    </div>
                    <div id="divIfTestedPositiveUsingRTPCR">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="testedPositiveSpecCollectedDate"><span class="text-danger font-weight-bold">*</span>Date of Specimen Collection</label>
                                    <input type="date" class="form-control" name="testedPositiveSpecCollectedDate" id="testedPositiveSpecCollectedDate" max="{{date('Y-m-d')}}" value="{{old('testedPositiveSpecCollectedDate')}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="testedPositiveLab">Laboratory</label>
                                    <input type="text" class="form-control" name="testedPositiveLab" id="testedPositiveLab" value="{{old('testedPositiveLab')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="testDateCollected1"><span class="text-danger font-weight-bold">*</span>Date Collected</label>
                                <input type="date" class="form-control" name="testDateCollected1" id="testDateCollected1" min="{{date('Y-01-01')}}" max="{{date('Y-12-31')}}" value="{{old('testDateCollected1')}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="testDateReleased1"><span class="text-danger font-weight-bold">*</span>Date released</label>
                                <input type="date" class="form-control" name="testDateReleased1" id="testDateReleased1" min="{{date('Y-01-01')}}" value="{{old('testDateReleased1')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="testLaboratory1"><span class="text-danger font-weight-bold">*</span>Laboratory</label>
                                <input type="text" class="form-control" name="testLaboratory1" id="testLaboratory1" value="{{old('testLaboratory1')}}" style="text-transform: uppercase;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="testType1"><span class="text-danger font-weight-bold">*</span>Type of test</label>
                                <select class="form-control" name="testType1" id="testType1" required>
                                    <option value="" disabled selected>Choose...</option>
                                    <option value="OPS" {{(old('testType1') == 'OPS') ? 'selected' : ''}}>RT-PCR (OPS)</option>
                                    <option value="NPS" {{(old('testType1') == 'NPS') ? 'selected' : ''}}>RT-PCR (NPS)</option>
                                    <option value="OPS AND NPS" {{(old('testType1') == 'OPS AND NPS') ? 'selected' : ''}}>RT-PCR (OPS and NPS)</option>
                                    <option value="ANTIGEN" {{(old('testType1') == 'ANTIGEN') ? 'selected' : ''}}>Antigen Test</option>
                                    <option value="ANTIBODY" {{(old('testType1') == 'ANTIBODY') ? 'selected' : ''}}>Antibody Test</option>
                                    <option value="OTHERS" {{(old('testType1') == 'OTHERS') ? 'selected' : ''}}>Others</option>
                                </select>
                            </div>
                            <div id="divTypeOthers1">
                                <div class="form-group">
                                    <label for="testTypeOtherRemarks1"><span class="text-danger font-weight-bold">*</span>Specify Reason</label>
                                    <input type="text" class="form-control" name="testTypeOtherRemarks1" id="testTypeOtherRemarks1" value="{{old('testTypeOtherRemarks1')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div id="ifAntigen1">
                                <div class="form-group">
                                    <label for="antigenKit1"><span class="text-danger font-weight-bold">*</span>Antigen Kit</label>
                                    <input type="text" class="form-control" name="antigenKit1" id="antigenKit1" value="{{old('antigenKit1')}}" style="text-transform: uppercase;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label for="result_file"><span class="text-danger font-weight-bold">*</span>Upload Positive Result</label>
                      <input type="file" class="form-control-file" name="result_file" id="result_file" required>
                      <small class="form-text text-muted">Accepted file formats: JPG, JPEG, PNG, PDF. Max file size: 5MB</small>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header font-weight-bold">6. Chest X-ray Details</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="diagWithSARI"><span class="text-danger font-weight-bold">*</span>Was diagnosed to have Severe Acute Respiratory Illness?</label>
                        <select class="form-control" name="diagWithSARI" id="diagWithSARI" required>
                          <option value="1" {{(old('diagWithSARI') == 1) ? 'selected' : ''}}>Yes</option>
                          <option value="0" {{(is_null(old('diagWithSARI')) || old('diagWithSARI') == 0) ? 'selected' : ''}}>No</option>
                        </select>
                      </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for=""><span class="text-danger font-weight-bold">*</span>Kailan natapos / Date done</label>
                                <input type="date" class="form-control" name="imagingDoneDate" id="imagingDoneDate" value="{{old('imagingDoneDate')}}">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="imagingDone"><span class="text-danger font-weight-bold">*</span>Uri ng Chest X-Ray / Chest X-Ray Type</label>
                                <select class="form-control" name="imagingDone" id="imagingDone" required>
                                <option value="None" {{(old('imagingDone') == "None") ? 'selected' : ''}}>None</option>
                                <option value="Chest Radiography" {{(old('imagingDone') == "Chest Radiography") ? 'selected' : ''}}>Chest Radiography</option>
                                <option value="Chest CT" {{(old('imagingDone') == "Chest CT") ? 'selected' : ''}}>Chest CT</option>
                                <option value="Lung Ultrasound" {{(old('imagingDone') == "Lung Ultrasound") ? 'selected' : ''}}>Lung Ultrasound</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="imagingResult"><span class="text-danger font-weight-bold">*</span>Resulta / Results</label>
                                <select class="form-control" name="imagingResult" id="imagingResult">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4">
                            <div id="divImagingOthers">
                                <div class="form-group">
                                    <label for="imagingOtherFindings"><span class="text-danger font-weight-bold">*</span>Tukuyin / Specify findings</label>
                                    <input type="text" class="form-control" name="imagingOtherFindings" id="imagingOtherFindings" value="{{old('imagingOtherFindings')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3 mb-3">
                <div class="card-header font-weight-bold">7. Contact Tracing: Exposure and Travel History</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="expoitem1"><span class="text-danger font-weight-bold">*</span>History of exposure to known probable and/or confirmed COVID-19 case 14 days before the onset of signs and symptoms?  OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                        <select class="form-control" name="expoitem1" id="expoitem1" required>
                            <option value="2" {{(old('expoitem1') == 2) ? 'selected' : ''}}>No</option>
                            <option value="1" {{(old('expoitem1') == 1) ? 'selected' : ''}}>Yes</option>
                            <option value="3" {{(old('expoitem1') == 3) ? 'selected' : ''}}>Unknown</option>
                        </select>
                    </div>
                    <div id="divExpoitem1">
                        <div class="form-group">
                            <label for=""><span class="text-danger font-weight-bold">*</span>Date of Last Contact</label>
                            <input type="date" class="form-control" name="expoDateLastCont" id="expoDateLastCont" max="{{date('Y-m-d')}}" value="{{old('expoDateLastCont')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="expoitem2"><span class="text-danger font-weight-bold">*</span>Has the patient been in a place with a known COVID-19 transmission 14 days before the onset of signs and symptoms? OR If Asymptomatic, 14 days before swabbing or specimen collection?</label>
                        <select class="form-control" name="expoitem2" id="expoitem2" required>
                            <option value="0" {{(old('expoitem2') == 2) ? 'selected' : ''}}>No</option>
                            <option value="1" {{(old('expoitem2') == 1) ? 'selected' : ''}}>Yes, Local</option>
                            <option value="2" {{(old('expoitem2') == 2) ? 'selected' : ''}}>Yes, International</option>
                            <option value="3" {{(old('expoitem2') == 3) ? 'selected' : ''}}>Unknown exposure</option>
                        </select>
                    </div>
                    <div id="divTravelInt">
                        <div class="form-group">
                            <label for="intCountry"><span class="text-danger font-weight-bold">*</span>If International Travel, country of origin</label>
                            <select class="form-control" name="intCountry" id="intCountry">
                                <option value="" {{(is_null(old('intCountry'))) ? 'selected disabled' : ''}}>Choose...</option>
                                    @foreach ($countries as $country)
                                        @if($country != 'Philippines')
                                            <option value="{{$country}}" {{(old('intCountry') == $country) ? 'selected' : ''}}>{{$country}}</option>
                                        @endif
                                    @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header">Inclusive travel dates</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="intDateFrom">From</label>
                                                    <input type="date" class="form-control" name="intDateFrom" id="intDateFrom" value="{{old('intDateFrom')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="intDateTo">From</label>
                                                    <input type="date" class="form-control" name="intDateTo" id="intDateTo" value="{{old('intDateTo')}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="intWithOngoingCovid">With ongoing COVID-19 community transmission?</label>
                                    <select class="form-control" name="intWithOngoingCovid" id="intWithOngoingCovid">
                                        <option value="NO" {{(old('intWithOngoingCovid') == "NO") ? 'selected' : ''}}>No</option>
                                        <option value="YES" {{(old('intWithOngoingCovid') == "YES") ? 'selected' : ''}}>Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="intVessel">Airline/Sea vessel</label>
                                            <input type="text" class="form-control" name="intVessel" id="intVessel" value="{{old('intVessel')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="intVesselNo">Flight/Vessel Number</label>
                                            <input type="text" class="form-control" name="intVesselNo" id="intVesselNo" value="{{old('intVesselNo')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="intDateDepart">Date of departure</label>
                                            <input type="date" class="form-control" name="intDateDepart" id="intDateDepart" value="{{old('intDateDepart')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="intDateArrive">Date of arrival in PH</label>
                                            <input type="date" class="form-control" name="intDateArrive" id="intDateArrive" value="{{old('intDateArrive')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="divTravelLoc">
                        <div class="card">
                            <div class="card-header">
                                If Local Travel, specify travel places (<i>Check all that apply, provide name of facility, address, and inclusive travel dates</i>)
                            </div>
                            <div class="card-body">
                                <div class="form-check">
                                    <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited1" value="Health Facility" {{(is_array(old('placevisited')) && in_array("Health Facility", old('placevisited'))) ? 'checked' : ''}}>
                                    Health Facility
                                    </label>
                                </div>
                                <div id="divLocal1" class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locName1">Name of Place</label>
                                                <input class="form-control" type="text" name="locName1" id="locName1" value="{{old('locName1')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locAddress1">Location</label>
                                                <input class="form-control" type="text" name="locAddress1" id="locAddress1" value="{{old('locAddress1')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">Inclusive Travel Dates</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateFrom1">From</label>
                                                                <input class="form-control" type="date" name="locDateFrom1" id="locDateFrom1" value="{{old('locDateFrom1')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateTo1">To</label>
                                                                <input class="form-control" type="date" name="locDateTo1" id="locDateTo1" value="{{old('locDateTo1')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locWithOngoingCovid1">With ongoing COVID-19 Community Transmission?</label>
                                                <select class="form-control" name="locWithOngoingCovid1" id="locWithOngoingCovid1">
                                                <option value="NO" {{(old('locWithOngoingCovid1') == "NO") ? 'selected' : ''}}>No</option>
                                                <option value="YES" {{(old('locWithOngoingCovid1') == "YES") ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited2" value="Closed Settings" {{(is_array(old('placevisited')) && in_array("Cloed Settings", old('placevisited'))) ? 'checked' : ''}}>
                                        Closed Settings
                                    </label>
                                </div>
                                <div id="divLocal2" class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locName2">Name of Place</label>
                                                <input class="form-control" type="text" name="locName2" id="locName2" value="{{old('locName2')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locAddress2">Location</label>
                                                <input class="form-control" type="text" name="locAddress2" id="locAddress2" value="{{old('locAddress2')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">Inclusive Travel Dates</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateFrom2">From</label>
                                                                <input class="form-control" type="date" name="locDateFrom2" id="locDateFrom2" value="{{old('locDateFrom2')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateTo2">To</label>
                                                                <input class="form-control" type="date" name="locDateTo2" id="locDateTo2" value="{{old('locDateTo2')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locWithOngoingCovid2">With ongoing COVID-19 Community Transmission?</label>
                                                <select class="form-control" name="locWithOngoingCovid2" id="locWithOngoingCovid2">
                                                <option value="NO" {{(old('locWithOngoingCovid2') == "NO") ? 'selected' : ''}}>No</option>
                                                <option value="YES" {{(old('locWithOngoingCovid2') == "YES") ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited3" value="School" {{(is_array(old('placevisited')) && in_array("School", old('placevisited'))) ? 'checked' : ''}}>
                                        School
                                    </label>
                                </div>
                                <div id="divLocal3" class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locName3">Name of Place</label>
                                                <input class="form-control" type="text" name="locName3" id="locName3" value="{{old('locName3')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locAddress3">Location</label>
                                                <input class="form-control" type="text" name="locAddress3" id="locAddress3" value="{{old('locAddress3')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">Inclusive Travel Dates</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateFrom3">From</label>
                                                                <input class="form-control" type="date" name="locDateFrom3" id="locDateFrom3" value="{{old('locDateFrom3')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateTo3">To</label>
                                                                <input class="form-control" type="date" name="locDateTo3" id="locDateTo3" value="{{old('locDateTo3')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locWithOngoingCovid3">With ongoing COVID-19 Community Transmission?</label>
                                                <select class="form-control" name="locWithOngoingCovid3" id="locWithOngoingCovid3">
                                                <option value="NO" {{(old('locWithOngoingCovid3') == "NO") ? 'selected' : ''}}>No</option>
                                                <option value="YES" {{(old('locWithOngoingCovid3') == "YES") ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited4" value="Workplace" {{(is_array(old('placevisited')) && in_array("Workplace", old('placevisited'))) ? 'checked' : ''}}>
                                        Workplace
                                    </label>
                                </div>
                                <div id="divLocal4" class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locName4">Name of Place</label>
                                                <input class="form-control" type="text" name="locName4" id="locName4" value="{{old('locName4')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locAddress4">Location</label>
                                                <input class="form-control" type="text" name="locAddress4" id="locAddress4" value="{{old('locAddress4')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">Inclusive Travel Dates</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateFrom4">From</label>
                                                                <input class="form-control" type="date" name="locDateFrom4" id="locDateFrom4" value="{{old('locDateFrom4')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateTo4">To</label>
                                                                <input class="form-control" type="date" name="locDateTo4" id="locDateTo4" value="{{old('locDateTo4')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locWithOngoingCovid4">With ongoing COVID-19 Community Transmission?</label>
                                                <select class="form-control" name="locWithOngoingCovid4" id="locWithOngoingCovid4">
                                                <option value="NO" {{(old('locWithOngoingCovid4') == "NO") ? 'selected' : ''}}>No</option>
                                                <option value="YES" {{(old('locWithOngoingCovid4') == "YES") ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited5" value="Market" {{(is_array(old('placevisited')) && in_array("Market", old('placevisited'))) ? 'checked' : ''}}>
                                        Market
                                    </label>
                                </div>
                                <div id="divLocal5" class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locName5">Name of Place</label>
                                                <input class="form-control" type="text" name="locName5" id="locName5" value="{{old('locName5')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locAddress5">Location</label>
                                                <input class="form-control" type="text" name="locAddress5" id="locAddress5" value="{{old('locAddress5')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">Inclusive Travel Dates</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateFrom5">From</label>
                                                                <input class="form-control" type="date" name="locDateFrom5" id="locDateFrom5" value="{{old('locDateFrom5')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateTo5">To</label>
                                                                <input class="form-control" type="date" name="locDateTo5" id="locDateTo5" value="{{old('locDateTo5')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locWithOngoingCovid5">With ongoing COVID-19 Community Transmission?</label>
                                                <select class="form-control" name="locWithOngoingCovid5" id="locWithOngoingCovid5">
                                                <option value="NO" {{(old('locWithOngoingCovid5') == "NO") ? 'selected' : ''}}>No</option>
                                                <option value="YES" {{(old('locWithOngoingCovid5') == "YES") ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited6" value="Social Gathering" {{(is_array(old('placevisited')) && in_array("Social Gathering", old('placevisited'))) ? 'checked' : ''}}>
                                        Social Gathering
                                    </label>
                                </div>
                                <div id="divLocal6" class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locName6">Name of Place</label>
                                                <input class="form-control" type="text" name="locName6" id="locName6" value="{{old('locName6')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locAddress6">Location</label>
                                                <input class="form-control" type="text" name="locAddress6" id="locAddress6" value="{{old('locAddress6')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">Inclusive Travel Dates</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateFrom6">From</label>
                                                                <input class="form-control" type="date" name="locDateFrom6" id="locDateFrom6" value="{{old('locDateFrom6')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateTo6">To</label>
                                                                <input class="form-control" type="date" name="locDateTo6" id="locDateTo6" value="{{old('locDateTo6')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locWithOngoingCovid6">With ongoing COVID-19 Community Transmission?</label>
                                                <select class="form-control" name="locWithOngoingCovid6" id="locWithOngoingCovid6">
                                                <option value="NO" {{(old('locWithOngoingCovid6') == "NO") ? 'selected' : ''}}>No</option>
                                                <option value="YES" {{(old('locWithOngoingCovid6') == "YES") ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited7" value="Others" {{(is_array(old('placevisited')) && in_array("Others", old('placevisited'))) ? 'checked' : ''}}>
                                        Others
                                    </label>
                                </div>
                                <div id="divLocal7" class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locName7">Name of Place</label>
                                                <input class="form-control" type="text" name="locName7" id="locName7" value="{{old('locName7')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locAddress7">Location</label>
                                                <input class="form-control" type="text" name="locAddress7" id="locAddress7" value="{{old('locAddress7')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">Inclusive Travel Dates</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateFrom7">From</label>
                                                                <input class="form-control" type="date" name="locDateFrom7" id="locDateFrom7" value="{{old('locDateFrom7')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="locDateTo7">To</label>
                                                                <input class="form-control" type="date" name="locDateTo7" id="locDateTo7" value="{{old('locDateTo7')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="locWithOngoingCovid7">With ongoing COVID-19 Community Transmission?</label>
                                                <select class="form-control" name="locWithOngoingCovid7" id="locWithOngoingCovid7">
                                                <option value="NO" {{(old('locWithOngoingCovid7') == "NO") ? 'selected' : ''}}>No</option>
                                                <option value="YES" {{(old('locWithOngoingCovid7') == "YES") ? 'selected' : ''}}>Yes</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="placevisited[]" id="placevisited8" value="Transport Service" {{(is_array(old('placevisited')) && in_array("Transport Service", old('placevisited'))) ? 'checked' : ''}}>
                                        Transport Service
                                    </label>
                                </div>
                                <div id="divLocal8" class="mt-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localVessel1">1. Airline/Sea vessel/Bus line/Train</label>
                                                <input type="text" class="form-control" name="localVessel1" id="localVessel1" value="{{old('localVessel1')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localVesselNo1">Flight/Vessel/Bus No.</label>
                                                <input type="text" class="form-control" name="localVesselNo1" id="localVesselNo1" value="{{old('localVesselNo1')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localOrigin1">Place of Origin</label>
                                                <input type="text" class="form-control" name="localOrigin1" id="localOrigin1" value="{{old('localOrigin1')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localDateDepart1">Departure Date</label>
                                                <input type="date" class="form-control" name="localDateDepart1" id="localDateDepart1" value="{{old('localDateDepart1')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localDest1">Destination</label>
                                                <input type="text" class="form-control" name="localDest1" id="localDest1" value="{{old('localDest1')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localDateArrive1">Date of Arrival</label>
                                                <input type="text" class="form-control" name="localDateArrive1" id="localDateArrive1" value="{{old('localDateArrive1')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localVessel2">2. Airline/Sea vessel/Bus line/Train</label>
                                                <input type="text" class="form-control" name="localVessel2" id="localVessel2" value="{{old('localVessel2')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localVesselNo2">Flight/Vessel/Bus No.</label>
                                                <input type="text" class="form-control" name="localVesselNo2" id="localVesselNo2" value="{{old('localVesselNo2')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localOrigin2">Place of Origin</label>
                                                <input type="text" class="form-control" name="localOrigin2" id="localOrigin2" value="{{old('localOrigin2')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localDateDepart2">Departure Date</label>
                                                <input type="date" class="form-control" name="localDateDepart2" id="localDateDepart2" value="{{old('localDateDepart2')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localDest2">Destination</label>
                                                <input type="text" class="form-control" name="localDest2" id="localDest2" value="{{old('localDest2')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="localDateArrive2">Date of Arrival</label>
                                                <input type="date" class="form-control" name="localDateArrive2" id="localDateArrive2" value="{{old('localDateArrive2')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-header">List the names of persons who were with you two days prior to onset of illness until this date and their contact numbers.</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="alert alert-info" role="alert">
                                        <p>- If symptomatic, provide names and contact numbers of persons who were with the patient two days prior to onset of illness until this date.</p>
                                        <p>- If asymptomatic, provide names and contact numbers of persons who were with the patient on the day specimen was submitted for testing until this date.</p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="card">
                                        <div class="card-header">Name</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact1Name" id="contact1Name" value="{{old('contact1Name')}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact2Name" id="contact2Name" value="{{old('contact2Name')}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact3Name" id="contact3Name" value="{{old('contact3Name')}}" style="text-transform: uppercase;">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact4Name" id="contact4Name" value="{{old('contact4Name')}}" style="text-transform: uppercase;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">Contact Number</div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact1No" id="contact1No" value="{{old('contact1No')}}">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact2No" id="contact2No" value="{{old('contact2No')}}">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact3No" id="contact3No" value="{{old('contact3No')}}">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="contact4No" id="contact4No" value="{{old('contact3No')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header font-weight-bold">Data Privacy Statement of General Trias</div>
                <div class="card-body text-center">
                    <p>Sa pamamagitan ng pagpili ng "Sumasang-ayon ako" at pag-click sa pindutang "Isumite" sa ibaba, kinikilala ko at napatunayan na maingat kong binasa at naintindihan ang Mga Tuntunin at Kundisyon ng Patakaran sa Data Privacy/Polisiya ng Pamahalaan ng Lunsod ng General Trias. Sa pamamagitan ng pagbibigay ng personal na impormasyon sa Pamahalaang Lungsod ng General Trias, kinukumpirma ko na ang data ay totoo at tama. Naiintindihan ko na ang Pamahalaang Lungsod ng General Trias ay may karapatang baguhin ang anumang desisyon na ginawa batay sa impormasyong ibinigay ko kung ang impormasyon ay mapatunayan na hindi totoo o hindi tama. Sumasang-ayon din ako na ang anumang isyu na maaaring lumabas na may kaugnayan sa pagproseso ng aking personal na impormasyon ay maaayos sa Pamahalaang Panlungsod ng General Trias bago gamitin ang naaangkop na arbitrasyon o paglilitis sa korte sa loob ng hurisdiksyon ng Pilipinas. Sa wakas, nagbibigay ako ng aking kusang-loob na pahintulot at permiso sa Pamahalaang Lungsod ng General Trias at ang mga kinatawan na pinahintulutan na ligal na maproseso ang aking data / impormasyon.</p>
                    <hr>
                    <p>By choosing "I Agree" and clicking the "Submit" button below, I hereby acknowledge and certify that I have carefully read and understood the Terms and Conditions of the Data Privacy Policy/Notice of the City Government of General Trias. By providing personal information to City Government of General Trias, I am confirming that the data is true and correct. I understand that City Government of General Trias reserves the right to revise any decision made on the basis of the information I provided should the information be found to be untrue or incorrect. I likewise agree that any issue that may arise in connection with the processing of my personal information will be settled amicably with City Government of General Trias before resorting to appropriate arbitration or court proceedings within the Philippine jurisdiction. Finally, I am providing my voluntary consent and authorization to City Government of General Trias and its authorized representatives to lawfully process my data/information.</p>
                    <div class="form-check">
                        <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="dpsagree" id="dpsagree" required>
                        Sumasang-ayon ako / I Agree
                        </label>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary btn-block" id="verifyButton" data-toggle="modal" data-target="#verifyDetails">Isumite / Submit</button>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="verifyDetails" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="font-family: Arial, Helvetica, sans-serif">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">Confirm Details</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text-center font-weight-bold text-danger">PLEASE DOUBLE CHECK YOUR DETAILS CAREFULLY BEFORE PROCEEDING. KINDLY CHECK IF THERE ARE TYPOGRAPHICAL ERRORS OR INCORRECT SPELLING IN YOUR NAME.</p>
                        <hr>
                        <p>Client Type: <span id="vpType"></span></p>
                        <p>For Hospitalization: <span id="visForHospitalization"></span></p>
                        <p>For Antigen: <span id="vforAntigen"></span></p>
                        <hr>
                        <p>Last Name: <span id="vlname"></span></p>
                        <p>First Name: <span id="vfname"></span></p>
                        <p>Middle Name: <span id="vmname"></span></p>
                        <p>Birthdate: <span id="vbdate"></span></p>
                        <p>Gender: <span id="vgender"></span></p>
                        <p>Civil Status: <span id="vcs"></span></p>
                        <p>Mobile Number: <span id="vmobile"></span></p>
                        <p>Philhealth Number: <span id="vphilhealth"></span></p>
                        <hr>
                        <p>House No./Lot/Bldg: <span id="vaddress_houseno"></span></p>
                        <p>Street/Purok/Sitio: <span id="vaddress_street"></span></p>
                        <p>Barangay: <span id="vaddress_brgy"></span></p>
                        <p>City/Municipality: <span id="vaddress_city"></span></p>
                        <p>Province: <span id="vaddress_province"></span></p>
                        <hr>
                        <p class="text-center font-weight-bold">If you would like to change some details, press <span class="text-secondary">[Go Back]</span>. If all details stated are correct upon checking, then press <span class="text-success">[Proceed]</span> to finish the registration.</p>
                        <p class="text-center font-weight-bold text-danger">NOTE: YOU CANNOT EDIT YOUR OWN DETAILS ONCE IT IS SUBMITTED.</p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Go Back</button>
                        <button type="button" id="proceedbtn" class="btn btn-success">Proceed</button>
                        <button type="submit" id="submitbtn" class="btn btn-success d-none">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form> 
    
    <div class="modal fade" id="announcement" tabindex="-1" role="dialog" style="font-family: Arial, Helvetica, sans-serif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Notice</h5>
                </div>
                <div class="modal-body text-center">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit a corporis deserunt maiores rem iure saepe magni dolorem accusamus, neque magnam facilis temporibus ducimus atque, laudantium blanditiis illum modi voluptates?</p>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div> 

    <script>
        @if(session('skipmodal') == false)
        $('#announcement').modal({backdrop: 'static', keyboard: false});
        $('#announcement').modal('show');
        @endif

        $('#dispositionType').change(function (e) {
                e.preventDefault();
                $('#dispositionDate').prop("type", "datetime-local");
                
                if($(this).val() == '1' || $(this).val() == '2') {
                    $('#dispositionName').prop('required', true);
                    $('#dispositionDate').prop('required', true);
                }
                else if ($(this).val() == '3' || $(this).val() == '4') {
                    $('#dispositionName').prop('required', false);
                    $('#dispositionDate').prop('required', true);
                }
                else if ($(this).val() == '5') {
                    $('#dispositionName').prop('required', true);
                    $('#dispositionDate').prop('required', false);
                }
                else if($(this).val().length == 0){
                    $('#dispositionName').prop('required', false);
                    $('#dispositionDate').prop('required', false);
                }

                if($(this).val() == '1') {
                    $('#divYes5').show();
                    $('#divYes6').show();

                    $('#dispositionlabel').text("Name of Hospital");
                    $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
                }
                if($(this).val() == '2') {
                    $('#divYes5').show();
                    $('#divYes6').show();

                    $('#dispositionlabel').text("Name of Facility");
                    $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
                }
                if($(this).val() == '3') {
                    $('#divYes5').hide();
                    $('#divYes6').show();

                    $('#dispositiondatelabel').text("Date and Time isolated/quarantined at home");
                }
                if($(this).val() == '4') {
                    $('#divYes5').hide();
                    $('#divYes6').show();

                    $('#dispositionDate').prop("type", "date");

                    $('#dispositiondatelabel').text("Date of Discharge");
                }
                if($(this).val() == '5') {
                    $('#divYes5').show();
                    $('#divYes6').hide();

                    $('#dispositionlabel').text("State Reason");
                }
                else if($(this).val().length == 0){
                    $('#divYes5').hide();
                    $('#divYes6').hide();
                }
            }).trigger('change');

        $('#isHealthCareWorker').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '0') {
                $('#divisHealthCareWorker').hide();
                $('#healthCareCompanyName').prop('required', false);
                $('#healthCareCompanyLocation').prop('required', false);
            }
            else {
                $('#divisHealthCareWorker').show();
                $('#healthCareCompanyName').prop('required', true);
                $('#healthCareCompanyLocation').prop('required', true);
            }
        }).trigger('change');

        $('#isOFW').change(function (e) {
            if($(this).val() == '0') {
                $('#divisOFW').hide();
                $('#OFWCountyOfOrigin').prop('required', false);
                $('#OFWPassportNo').prop('required', false);
            }
            else {
                $('#divisOFW').show();
                $('#OFWPassportNo').prop('required', true);
                $('#oaddressscountry').val('N/A');
                $('#OFWCountyOfOrigin').prop('required', true);
            }
        }).trigger('change');

        $('#OFWCountyOfOrigin').change(function (e) { 
            e.preventDefault();
            $('#oaddressscountry').val($(this).val());
        });

        $('#isFNT').change(function (e) {
            if($(this).val() == '0') {
                $('#divisFNT').hide();
                $('#FNTCountryOfOrigin').prop('required', false);
                $('#FNTPassportNo').prop('required', false);
            }
            else {
                $('#divisFNT').show();
                $('#FNTCountryOfOrigin').prop('required', true);
                $('#FNTPassportNo').prop('required', true);
            }
        }).trigger('change');

        $('#isLSI').change(function (e) {
            if($(this).val() == '0') {
                $('#divisLSI').hide();
                $('#LSIProvince').prop('required', false);
                $('#LSICity').prop('required', false);
            }
            else {
                $('#divisLSI').show();
                $('#LSIProvince').prop('required', true);
                $('#LSICity').prop('required', true);
            }
        }).trigger('change');

        $('#isLivesOnClosedSettings').change(function (e) {
            if($(this).val() == '0') {
                $('#divisLivesOnClosedSettings').hide();
                $('#institutionType').prop('required', false);
                $('#institutionName').prop('required', false);
            }
            else {
                $('#divisLivesOnClosedSettings').show();
                $('#institutionType').prop('required', true);
                $('#institutionName').prop('required', true);
            }
        }).trigger('change');
        
        $('#havePreviousCovidConsultation').change(function (e) { 
                e.preventDefault();
                if($(this).val() == '1') {
                    $('#divYes1').show();

                    $('#dateOfFirstConsult').prop('required', true);
                    $('#facilityNameOfFirstConsult').prop('required', true);
                }
                else {
                    $('#divYes1').hide();

                    $('#dateOfFirstConsult').prop('required', false);
                    $('#facilityNameOfFirstConsult').prop('required', false);
                }
            }).trigger('change');

        $('#proceedbtn').click(function (e) { 
            $('#verifyDetails').modal('hide');
            setTimeout(function(){
                $('#submitbtn').trigger('click');
            }, 500);
        });

        $('#address_houseno').keyup(function(){
            this.value = this.value.toUpperCase();
        });

        $('#address_street').keyup(function(){
            this.value = this.value.toUpperCase();
        });

        $('#verifyButton').click(function (e) { 
            e.preventDefault();
            if($('#pType').val() == 'TESTING') {
                $('#vpType').text('NOT A CASE OF COVID');
            }
            else if($('#pType').val() == 'CLOSE CONTACT') {
                $('#vpType').text('CLOSE CONTACT');
            }
            else if($('#pType').val() == 'PROBABLE') {
                $('#vpType').text('SUSPECTED');
            }
            if($('#isForHospitalization').val() == 1) {
                $('#visForHospitalization').text('YES');
            }
            else {
                $('#visForHospitalization').text('NO');
            }
            if($('#forAntigen').val() == 1) {
                $('#vforAntigen').text('YES');
            }
            else {
                $('#vforAntigen').text('NO');
            }
            $('#vlname').text($('#lname').val().toUpperCase());
            $('#vfname').text($('#fname').val().toUpperCase());
            $('#vmname').text($('#mname').val().toUpperCase());
            $('#vbdate').text($('#bdate').val());
            $('#vgender').text($('#gender').val());
            $('#vcs').text($('#cs').val());
            $('#vmobile').text($('#mobile').val());
            $('#vphilhealth').text($('#philhealth').val());
            $('#vaddress_province').text($('#address_province').val());
            $('#vaddress_city').text($('#address_city').val());
            $('#vaddress_brgy').text($('#address_brgy').val());
            $('#vaddress_houseno').text($('#address_houseno').val().toUpperCase());
            $('#vaddress_street').text($('#address_street').val().toUpperCase());
        });

        $(function(){
            var requiredCheckboxes = $('.comoOpt :checkbox[required]');
            requiredCheckboxes.change(function(){
                if(requiredCheckboxes.is(':checked')) {
                    requiredCheckboxes.removeAttr('required');
                } else {
                    requiredCheckboxes.attr('required', 'required');
                }
            }).trigger('change');;
        });
        
        $('#addresstext').hide();
        $('#saddress_city').prop('disabled', true);
		$('#address_brgy').prop('disabled', true);

        $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
			var sorted = data.sort(function(a, b) {
				if (a.provDesc > b.provDesc) {
				return 1;
				}
				if (a.provDesc < b.provDesc) {
				return -1;
				}
				return 0;
			});

			$.each(sorted, function(key, val) {
				$('#saddress_province').append($('<option>', {
					value: val.provCode,
					text: val.provDesc,
					selected: (val.provCode == '0421') ? true : false, //default for Cavite
				}));
			});
        });

        $('#saddress_province').change(function (e) {
			e.preventDefault();
			$('#saddress_city').prop('disabled', false);
			$('#address_brgy').prop('disabled', true);
			$('#saddress_city').empty();
			$("#saddress_city").append('<option value="" selected disabled>Choose...</option>');
			$('#address_brgy').empty();
			$("#address_brgy").append('<option value="" selected disabled>Choose...</option>');
			$("#address_province").val($('#saddress_province option:selected').text());
			$("#address_provincejson").val($('#saddress_province').val());
			
			$.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
				var sorted = data.sort(function(a, b) {
					if (a.citymunDesc > b.citymunDesc) {
					return 1;
					}
					if (a.citymunDesc < b.citymunDesc) {
					return -1;
					}
					return 0;
				});
				$.each(sorted, function(key, val) {
					if($('#saddress_province').val() == val.provCode) {
						$('#saddress_city').append($('<option>', {
							value: val.citymunCode,
							text: val.citymunDesc,
							selected: (val.citymunCode == '042108') ? true : false, //default for General Trias
						})); 
					}
				});
			});
		}).trigger('change');

        $('#saddress_city').change(function (e) { 
			e.preventDefault();
			$('#address_brgy').prop('disabled', false);
			$('#address_brgy').empty();
			$("#address_brgy").append('<option value="" selected disabled>Choose...</option>');
			$("#address_city").val($('#saddress_city option:selected').text());
			$('#address_cityjson').val($('#saddress_city').val());

			$.getJSON("{{asset('json/refbrgy.json')}}", function(data) {
				var sorted = data.sort(function(a, b) {
					if (a.brgyDesc > b.brgyDesc) {
					return 1;
					}
					if (a.brgyDesc < b.brgyDesc) {
					return -1;
					}
					return 0;
				});
				$.each(sorted, function(key, val) {
					if($('#saddress_city').val() == val.citymunCode) {
						$("#address_brgy").append('<option value="'+val.brgyDesc.toUpperCase()+'">'+val.brgyDesc.toUpperCase()+'</option>');
					}
				});
			});
		}).trigger('change');

		//for Setting Default values on hidden address/json for Cavite - General Trias
		$("#address_province").val('CAVITE');
		$("#address_provincejson").val('0421');
		$("#address_city").val('GENERAL TRIAS');
		$('#address_cityjson').val('042108');

        $('#haveOccupation').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#occupationRow').show();
                $('#occupation').prop('required', true);
                $('#natureOfWork').prop('required', true);
            }
            else {
                $('#occupationRow').hide();
                $('#occupation').prop('required', false);
                $('#natureOfWork').prop('required', false);
            }
        }).trigger('change');
        
        $('#LSICity').prop({'disabled': true, 'required': false});

        $.getJSON("{{asset('json/refprovince.json')}}", function(data) {
            var sorted = data.sort(function(a, b) {
                if (a.provDesc > b.provDesc) {
                return 1;
                }
                if (a.provDesc < b.provDesc) {
                return -1;
                }
                return 0;
            });
            $.each(sorted, function(key, val) {
                $("#LSIProvince").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
            });
        });

        $('#LSIProvince').change(function (e) { 
            e.preventDefault();
            $('#LSICity').prop({'disabled': false, 'required': true});
            $('#LSICity').empty();
            $("#LSICity").append('<option value="" selected disabled>Choose...</option>');
            $.getJSON("{{asset('json/refcitymun.json')}}", function(data) {
                var sorted = data.sort(function(a, b) {
                    if (a.citymunDesc > b.citymunDesc) {
                    return 1;
                    }
                    if (a.citymunDesc < b.citymunDesc) {
                    return -1;
                    }
                    return 0;
                });
                $.each(sorted, function(key, val) {
                    if($('#LSIProvince').val() == val.provCode) {
                        $("#LSICity").append('<option value="'+val.citymunCode+'">'+val.citymunDesc+'</option>');
                    }
                });
            });
        });

        $('#gender').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "MALE" || $(this).val() == null) {
                $('#ifGenderFemale').hide();
                $('#isPregnant').prop('required', false);
            }
            else {
                $('#ifGenderFemale').show();
                $('#isPregnant').prop('required', true);
            }
        }).trigger('change');

        $('#isPregnant').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '0' || $(this).val() == null) {
                $('#ifPregnant').hide();
                $('#lmp').prop('required', false);
            }
            else {
                $('#ifPregnant').show();
                $('#lmp').prop('required', true);
            }
        }).trigger('change');

        $('#natureOfWork').change(function (e) { 
			e.preventDefault();
			if($(this).val() == 'OTHERS') {
				$('#specifyWorkNatureDiv').show();
				$('#natureOfWorkIfOthers').prop('required', true);
			}
			else {
				$('#specifyWorkNatureDiv').hide();
				$('#natureOfWorkIfOthers').prop('required', false);
			}
		}).trigger('change');

        $('#expoitem1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 1) {
                $('#divExpoitem1').show();
                $('#expoDateLastCont').prop('required', true);
            }
            else {
                $('#divExpoitem1').hide();
                $('#expoDateLastCont').prop('required', false);
            }
        }).trigger('change');

        $('#signsCheck2').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divFeverChecked').show();
                $('#SASFeverDeg').prop('required', true);
            }
            else {
                $('#divFeverChecked').hide();
                $('#SASFeverDeg').prop('required', false);
            }
        }).trigger('change');

        $('#signsCheck18').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divSASOtherChecked').show();
                $('#SASOtherRemarks').prop('required', true);
            }
            else {
                $('#divSASOtherChecked').hide();
                $('#SASOtherRemarks').prop('required', false);
            }
        }).trigger('change');

        $('#haveSymptoms').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '0' || $(this).val() == null) {
                $('#ifHaveSymptoms').hide();
                $('#dateOnsetOfIllness').prop('required', false);
            }
            else {
                $('#ifHaveSymptoms').show();
                $('#dateOnsetOfIllness').prop('required', true);
            }
        }).trigger('change');

        $('#comCheck10').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divComOthersChecked').show();
                $('#COMOOtherRemarks').prop('required', true);
            }
            else {
                $('#divComOthersChecked').hide();
                $('#COMOOtherRemarks').prop('required', false);
            }
        }).trigger('change');
            
        $('#comCheck1').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#comCheck2').prop({'disabled': true, 'checked': false});
                $('#comCheck3').prop({'disabled': true, 'checked': false});
                $('#comCheck4').prop({'disabled': true, 'checked': false});
                $('#comCheck5').prop({'disabled': true, 'checked': false});
                $('#comCheck6').prop({'disabled': true, 'checked': false});
                $('#comCheck7').prop({'disabled': true, 'checked': false});
                $('#comCheck8').prop({'disabled': true, 'checked': false});
                $('#comCheck9').prop({'disabled': true, 'checked': false});
                $('#comCheck10').prop({'disabled': true, 'checked': false});
            }
            else {
                $('#comCheck2').prop({'disabled': false, 'checked': false});
                $('#comCheck3').prop({'disabled': false, 'checked': false});
                $('#comCheck4').prop({'disabled': false, 'checked': false});
                $('#comCheck5').prop({'disabled': false, 'checked': false});
                $('#comCheck6').prop({'disabled': false, 'checked': false});
                $('#comCheck7').prop({'disabled': false, 'checked': false});
                $('#comCheck8').prop({'disabled': false, 'checked': false});
                $('#comCheck9').prop({'disabled': false, 'checked': false});
                $('#comCheck10').prop({'disabled': false, 'checked': false});
            }
        });

        @if(is_null(old('comCheck')))
            $('#comCheck1').prop('checked', true);
        @endif

        $('#imagingDone').change(function (e) { 
            e.preventDefault();
            $('#divImagingOthers').hide();
            $('#imagingOtherFindings').val("");
            if($(this).val() == "None") {
                $('#imagingDoneDate').prop({disabled: true, required: false});
                $('#imagingResult').prop({disabled: true, required: false});
                $("#imagingResult").empty();
            }
            else {
                $('#imagingDoneDate').prop({disabled: false, required: true});
                $('#imagingResult').prop({disabled: false, required: true});
                $("#imagingResult").empty();
                $("#imagingResult").append(new Option("Normal", "NORMAL"));
                $("#imagingResult").append(new Option("Pending", "PENDING"));

                $('#divImagingOthers').hide();

                if($(this).val() == "Chest Radiography") {
                    $("#imagingResult").append(new Option("Hazy opacities, often rounded in morphology, with peripheral and lower lung dist.", "HAZY"));
                }
                else if($(this).val() == "Chest CT") {
                    $("#imagingResult").append(new Option("Multiple bilateral ground glass opacities, often rounded in morphology, w/ peripheral and lower lung dist.", "MULTIPLE"));
                }
                else if($(this).val() == "Lung Ultrasound") {
                    $("#imagingResult").append(new Option("Thickened pleural lines, B lines, consolidative patterns with or without air bronchograms.", "THICKENED"));
                }
                
                if($(this).val() != "OTHERS") {
                    $("#imagingResult").append(new Option("Other findings", "OTHERS"));
                }
            }
        }).trigger('change');

        $('#imagingResult').change(function (e) { 
            e.preventDefault();
            $('#imagingOtherFindings').val("");
            if($(this).val() == "OTHERS") {
                $('#divImagingOthers').show();
                $('imagingOtherFindings').prop({disabled: false, required: true});
            }
            else {
                $('#divImagingOthers').hide();
                $('imagingOtherFindings').prop({disabled: true, required: false});
            }
        }).trigger('change');

        $('#testedPositiveUsingRTPCRBefore').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "1") {
                $('#divIfTestedPositiveUsingRTPCR').show();
                $('#testedPositiveLab').prop('required', true);
                $('#testedPositiveSpecCollectedDate').prop('required', true);
            }
            else {
                $('#divIfTestedPositiveUsingRTPCR').hide();
                $('#testedPositiveLab').prop('required', false);
                $('#testedPositiveSpecCollectedDate').prop('required', false);
            }
        }).trigger('change');

        $('#testType1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'OTHERS' || $(this).val() == 'ANTIGEN') {
                $('#divTypeOthers1').show();
                $('#testTypeOtherRemarks1').prop('required', true);
                if($(this).val() == 'ANTIGEN') {
                    $('#ifAntigen1').show();
                    $('#antigenKit1').prop('required', true);
                }
                else {
                    $('#ifAntigen1').hide();
                    $('#antigenKit1').prop('required', false);
                }
            }
            else {
                $('#divTypeOthers1').hide();
                $('#testTypeOtherRemarks1').empty();
                $('#testTypeOtherRemarks1').prop('required', false);

                $('#ifAntigen1').hide();
                $('#antigenKit1').prop('required', false);
            }
        }).trigger('change');

        $('#testResult1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "OTHERS") {
                $('#divResultOthers1').show();
                $('#testResultOtherRemarks1').prop('required', true);
                $('#testDateReleased1').prop('required', true);
            }
            else {
                $('#divResultOthers1').hide();
                $('#testResultOtherRemarks1').empty();
                $('#testResultOtherRemarks1').prop('required', false);

                if($(this).val() == "POSITIVE" || $(this).val() == "NEGATIVE" || $(this).val() == "EQUIVOCAL") {
                    $('#testDateReleased1').prop('required', true);
                }
                else {
                    $('#testDateReleased1').prop('required', false);
                }
            }
        }).trigger('change');

        $('#pType').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "CLOSE CONTACT") {
                $('#expoitem1').empty();
                $('#expoitem1').append($('<option>', {
					value: '1',
					text: 'Oo / Yes',
					selected: true,
				}));
                $('#expoitem1').trigger('change');    
            }
            else {
                $('#expoitem1').empty();
                $('#expoitem1').append($('<option>', {
					value: "",
					text: 'Pumili... / Choose...',
					selected: true,
                    disabled: true,
				}));
                $('#expoitem1').append($('<option>', {
					value: '1',
					text: 'Oo / Yes',
				}));
                $('#expoitem1').append($('<option>', {
					value: '2',
					text: 'Hindi / No',
				}));
                $('#expoitem1').append($('<option>', {
					value: '3',
					text: 'Hindi sigurado / Unknown',
				}));
                $('#expoitem1').trigger('change');
            }
        });

        $('#forAntigen').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "1") {
                alert('You chose "Antigen" as the Type of Test for your COVID-19 Testing. Kindly take note that this is different from RT-PCR Test. To proceed in Antigen Testing, click OK to proceed. But if you want to undergo RT-PCR Testing, change this option to [NO].');
            }
        });
        
        $('#vaccineq1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#ifVaccinated').show();
                $('#howManyDose').prop('required', true);
                $('#nameOfVaccine').prop('required', true);
            }
            else {
                $('#ifVaccinated').hide();
                $('#howManyDose').prop('required', false);
                $('#nameOfVaccine').prop('required', false);
            }
        }).trigger('change');

        $('#howManyDose').change(function (e) { 
            e.preventDefault();
            if($(this).val() == '1') {
                $('#VaccineDose1').show();
                $('#VaccineDose2').hide();
                $('#vaccinationDate1').prop('required', true);
                $('#haveAdverseEvents1').prop('required', true);
                $('#vaccinationDate2').prop('required', false);
                $('#haveAdverseEvents2').prop('required', false);
            }
            else if($(this).val() == '2') {
                $('#VaccineDose1').show();
                $('#VaccineDose2').show();
                $('#vaccinationDate1').prop('required', true);
                $('#haveAdverseEvents1').prop('required', true);
                $('#vaccinationDate2').prop('required', true);
                $('#haveAdverseEvents2').prop('required', true);
            }
            else {
                $('#VaccineDose1').hide();
                $('#VaccineDose2').hide();
                $('#vaccinationDate1').prop('required', false);
                $('#haveAdverseEvents1').prop('required', false);
                $('#vaccinationDate2').prop('required', false);
                $('#haveAdverseEvents2').prop('required', false);
            }
        }).trigger('change');

        $('#expoitem1').change(function (e) { 
            e.preventDefault();
            if($(this).val() == "1") {
                $('#divExpoitem1').show();
                $('#expoDateLastCont').prop('required', true);
            }
            else {
                $('#divExpoitem1').hide();
                $('#expoDateLastCont').val(null);
                $('#expoDateLastCont').prop('required', false);
            }
        }).trigger('change');

        $('#expoitem2').change(function (e) {
            e.preventDefault();
            if($(this).val() == 0 || $(this).val() == 3) {
                $('#divTravelInt').hide();
                $('#divTravelLoc').hide();
            }
            else if($(this).val() == 1) {
                $('#divTravelInt').hide();

                $('#intCountry').prop('required', false);
                $('#intDateFrom').prop('required', false);
                $('#intDateTo').prop('required', false);
                $('#intWithOngoingCovid').prop('required', false);
                $('#intVessel').prop('required', false);
                $('#intVesselNo').prop('required', false);
                $('#intDateDepart').prop('required', false);
                $('#intDateArrive').prop('required', false);
                
                $('#divTravelLoc').show();
            }
            else if($(this).val() == 2) {
                $('#divTravelInt').show();

                $('#intCountry').prop('required', true);
                $('#intDateFrom').prop('required', false);
                $('#intDateTo').prop('required', false);
                $('#intWithOngoingCovid').prop('required', false);
                $('#intVessel').prop('required', false);
                $('#intVesselNo').prop('required', false);
                $('#intDateDepart').prop('required', false);
                $('#intDateArrive').prop('required', false);

                $('#divTravelLoc').hide();
            }
        }).trigger('change');

        $('#placevisited1').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal1').show();

                $('#locName1').prop('required', true);
                $('#locAddress1').prop('required', true);
                $('#locDateFrom1').prop('required', true);
                $('#locDateTo1').prop('required', true);
                $('#locWithOngoingCovid1').prop('required', true);
            }
            else {
                $('#divLocal1').hide();

                $('#locName1').prop('required', false);
                $('#locAddress1').prop('required', false);
                $('#locDateFrom1').prop('required', false);
                $('#locDateTo1').prop('required', false);
                $('#locWithOngoingCovid1').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited2').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal2').show();

                $('#locName2').prop('required', true);
                $('#locAddress2').prop('required', true);
                $('#locDateFrom2').prop('required', true);
                $('#locDateTo2').prop('required', true);
                $('#locWithOngoingCovid2').prop('required', true);
            }
            else {
                $('#divLocal2').hide();

                $('#locName2').prop('required', false);
                $('#locAddress2').prop('required', false);
                $('#locDateFrom2').prop('required', false);
                $('#locDateTo2').prop('required', false);
                $('#locWithOngoingCovid2').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited3').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal3').show();

                $('#locName3').prop('required', true);
                $('#locAddress3').prop('required', true);
                $('#locDateFrom3').prop('required', true);
                $('#locDateTo3').prop('required', true);
                $('#locWithOngoingCovid3').prop('required', true);
            }
            else {
                $('#divLocal3').hide();

                $('#locName3').prop('required', false);
                $('#locAddress3').prop('required', false);
                $('#locDateFrom3').prop('required', false);
                $('#locDateTo3').prop('required', false);
                $('#locWithOngoingCovid3').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited4').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal4').show();

                $('#locName4').prop('required', true);
                $('#locAddress4').prop('required', true);
                $('#locDateFrom4').prop('required', true);
                $('#locDateTo4').prop('required', true);
                $('#locWithOngoingCovid4').prop('required', true);
            }
            else {
                $('#divLocal4').hide();

                $('#locName4').prop('required', false);
                $('#locAddress4').prop('required', false);
                $('#locDateFrom4').prop('required', false);
                $('#locDateTo4').prop('required', false);
                $('#locWithOngoingCovid4').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited5').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal5').show();

                $('#locName5').prop('required', true);
                $('#locAddress5').prop('required', true);
                $('#locDateFrom5').prop('required', true);
                $('#locDateTo5').prop('required', true);
                $('#locWithOngoingCovid5').prop('required', true);
            }
            else {
                $('#divLocal5').hide();

                $('#locName5').prop('required', false);
                $('#locAddress5').prop('required', false);
                $('#locDateFrom5').prop('required', false);
                $('#locDateTo5').prop('required', false);
                $('#locWithOngoingCovid5').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited6').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal6').show();

                $('#locName6').prop('required', true);
                $('#locAddress6').prop('required', true);
                $('#locDateFrom6').prop('required', true);
                $('#locDateTo6').prop('required', true);
                $('#locWithOngoingCovid6').prop('required', true);
            }
            else {
                $('#divLocal6').hide();

                $('#locName6').prop('required', false);
                $('#locAddress6').prop('required', false);
                $('#locDateFrom6').prop('required', false);
                $('#locDateTo6').prop('required', false);
                $('#locWithOngoingCovid6').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited7').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal7').show();

                $('#locName7').prop('required', true);
                $('#locAddress7').prop('required', true);
                $('#locDateFrom7').prop('required', true);
                $('#locDateTo7').prop('required', true);
                $('#locWithOngoingCovid7').prop('required', true);
            }
            else {
                $('#divLocal7').hide();

                $('#locName7').prop('required', false);
                $('#locAddress7').prop('required', false);
                $('#locDateFrom7').prop('required', false);
                $('#locDateTo7').prop('required', false);
                $('#locWithOngoingCovid7').prop('required', false);
            }
        }).trigger('change');

        $('#placevisited8').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked') == true) {
                $('#divLocal8').show();

                //baguhin kapag kailangan kapag naka-check
                $('#localVessel1').prop('required', false);
                $('#localVesselNo1').prop('required', false);
                $('#localOrigin1').prop('required', false);
                $('#localDateDepart1').prop('required', false);
                $('#localDest1').prop('required', false);
                $('#localDateArrive1').prop('required', false);

                $('#localVessel2').prop('required', false);
                $('#localVesselNo2').prop('required', false);
                $('#localOrigin2').prop('required', false);
                $('#localDateDepart2').prop('required', false);
                $('#localDest2').prop('required', false);
                $('#localDateArrive2').prop('required', false);
            }
            else {
                $('#divLocal8').hide();

                $('#localVessel1').prop('required', false);
                $('#localVesselNo1').prop('required', false);
                $('#localOrigin1').prop('required', false);
                $('#localDateDepart1').prop('required', false);
                $('#localDest1').prop('required', false);
                $('#localDateArrive1').prop('required', false);

                $('#localVessel2').prop('required', false);
                $('#localVesselNo2').prop('required', false);
                $('#localOrigin2').prop('required', false);
                $('#localDateDepart2').prop('required', false);
                $('#localDest2').prop('required', false);
                $('#localDateArrive2').prop('required', false);

                $('localVessel1').val("");
                $('localVesselNo1').val("");
                $('localOrigin1').val("");
                $('localDateDepart1').val("");
                $('localDest1').val("");
                $('localDateArrive1').val("");

                $('localVessel2').val("");
                $('localVesselNo2').val("");
                $('localOrigin2').val("");
                $('localDateDepart2').val("");
                $('localDest2').val("");
                $('localDateArrive2').val("");
            }
        }).trigger('change');

        $('#myForm').on('submit', function() {
            $('#expoitem1').prop('disabled', false);
        });
    </script>
    @else
    <div class="container">
        <div class="card">
            <div class="card-header">Notice</div>
            <div class="card-body text-center">
                <p>As of July 10, 2021, <span class="text-primary">paswab.cesugentri.com</span> will require a valid Referral Code before proceeding into registration.</p>
                <p>
                    This is to prevent unauthorized and unmonitored patients from barangay to register. This will also provide information on where the patients information is coming from.
                </p>
            </div>
        </div>
    </div>
    @endif
@endsection