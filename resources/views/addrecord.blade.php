@extends('layouts.app')

@section('content')
<form action="{{ route('records.store')}}" method="post">
	@csrf
	<div class="container">
		<div class="card">
			<div class="card-header font-weight-bold text-info">Add New Record</div>
			<div class="card-body">
				@if(session('msg'))
				<div class="alert alert-danger" role="alert">
					{{session('msg')}} {{session('where')}}
				</div>
				@endif
				<div class="alert alert-success" role="alert">
					The record is not yet existing in the database. You can now proceed filling other required details.
				</div>
				<hr>
				<h5 class="font-weight-bold">Patient Information</h5>
				<hr>
				<div class="alert alert-info" role="alert">
					Note: All fields marked with an asterisk (<span class="text-danger font-weight-bold">*</span>) are required.
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="lname"><span class="text-danger font-weight-bold">*</span>Last Name</label>
							<input type="text" class="form-control" id="lname" name="lname" value="{{$lname}}" max="50" style="text-transform: uppercase;" readonly required>
							@error('lname')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="fname"><span class="text-danger font-weight-bold">*</span>First Name (and Suffix)</label>
							<input type="text" class="form-control" id="fname" name="fname" value="{{$fname}}" max="50" style="text-transform: uppercase;" readonly required>
							@error('fname')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="mname">Middle Name <small><i>(Leave blank if N/A)</i></small></label>
							<input type="text" class="form-control" id="mname" name="mname" value="{{$mname}}" max="50" style="text-transform: uppercase;" readonly required>
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
							<input type="date" class="form-control" id="bdate" name="bdate" value="{{old('bdate', $bdate)}}" min="1900-01-01" max="{{date('Y-m-d', strtotime('yesterday'))}}" readonly required>
							@error('bdate')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="gender"><span class="text-danger font-weight-bold">*</span>Gender</label>
						  	<select class="form-control" name="gender" id="gender">
								  <option value="" disabled {{(is_null(old('gender'))) ? 'selected' : ''}}>Choose...</option>
								  <option value="MALE">Male</option>
								  <option value="FEMALE">Female</option>
						  	</select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="cs"><span class="text-danger font-weight-bold">*</span>Civil Status</label>
							<select class="form-control" id="cs" name="cs" required>
								<option value="" disabled {{(is_null(old('cs'))) ? 'selected' : ''}}>Choose...</option>
								<option value="SINGLE" {{(old('cs') == 'SINGLE') ? 'selected' : ''}}>Single</option>
								<option value="MARRIED" {{(old('cs') == 'MARRIED') ? 'selected' : ''}}>Married</option>
								<option value="WIDOWED" {{(old('cs') == 'WIDOWED') ? 'selected' : ''}}>Widowed</option>
								<option value="N/A" {{(old('cs') == 'N/A') ? 'selected' : ''}}>N/A</option>
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
				<div id="pdiv" class="mb-3">
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="pregnant"><span class="text-danger font-weight-bold">*</span>Is the Patient Pregnant?</label>
								<select class="form-control" name="pregnant" id="pregnant" required>
								  <option value="0" @if(old('pregnant') == 0) {{'selected'}} @endif>No</option>
								  <option value="1" @if(old('pregnant') == 1) {{'selected'}} @endif>Yes</option>
								</select>
							</div>
						</div>
						<div class="col-md-6"></div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="mobile"><span class="text-danger font-weight-bold">*</span>Cellphone No.</label>
							<input type="text" class="form-control" id="mobile" name="mobile" value="{{old('mobile')}}" pattern="[0-9]{11}" placeholder="0917xxxxxxx" required>
							@error('mobile')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="phoneno">Home Phone No. (& Area Code)</label>
							<input type="text" class="form-control" id="phoneno" name="phoneno" value="{{old('phoneno')}}">
							@error('phoneno')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="email">Email Address</label>
							<input type="email" class="form-control" name="email" id="email" value="{{old('email')}}">
							@error('email')
								  <small class="text-danger">{{$message}}</small>
							@enderror
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="philhealth">Philhealth No. <small><i>(Leave blank if N/A)</i></small></label>
							<input type="text" class="form-control" id="philhealth" name="philhealth" value="{{old('philhealth')}}" minlength="12" maxlength="14">
							<small class="form-text text-muted">Note: If your input has no dashes, the system will automatically do that for you.</small>
							@error('philhealth')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
					</div>
				</div>
				<hr>
				<h5 class="font-weight-bold">Current Address</h5>
				<hr>
				<div id="addresstext">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							  <input type="text" class="form-control" name="address_province" id="address_province">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<input type="text" class="form-control" name="address_city" id="address_city">
							</div>
						</div>
						<div class="col-md-4">
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
							  <input type="text" class="form-control" name="address_provincejson" id="address_provincejson">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<input type="text" class="form-control" name="address_cityjson" id="address_cityjson">
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
						  <label for="saddress_city"><span class="text-danger font-weight-bold">*</span>City</label>
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
							<label for="address_street"><span class="text-danger font-weight-bold">*</span>Street/Purok/Sitio</label>
							<input type="text" class="form-control" id="address_street" name="address_street" style="text-transform: uppercase;" value="{{old('address_street')}}" required>
							@error('address_street')
								<small class="text-danger">{{$message}}</small>
							@enderror
						</div>
					</div>
				</div>
				<div class="card mb-3">
					<div class="card-header"><i class="fas fa-syringe mr-2"></i>COVID-19 Vaccination Information</div>
					<div class="card-body">
						<div class="form-group">
						  <label for="howManyDoseVaccine"><span class="text-danger font-weight-bold">*</span>If vaccinated, how many dose?</label>
						  <select class="form-control" name="howManyDoseVaccine" id="howManyDoseVaccine">
							<option value="" {{(is_null(old('howManyDoseVaccine'))) ? 'selected' : ''}}>N/A</option>
							<option value="1" {{(old('howManyDoseVaccine') == '1') ? 'selected' : ''}}>1st Dose only</option>
							<option value="2" {{(old('howManyDoseVaccine') == '2') ? 'selected' : ''}}>1st and 2nd Dose Completed</option>
						  </select>
						</div>
						<div id="ifVaccinated">
							<div class="form-group">
							  <label for="vaccineName"><span class="text-danger font-weight-bold">*</span>Name of Vaccine</label>
							  <select class="form-control" name="vaccineName" id="vaccineName">
								<option value="" disabled {{is_null(old('vaccineName')) ? 'selected' : ''}}>Choose...</option>
								<option value="BHARAT BIOTECH" {{(old('vaccineName') == "BHARAT BIOTECH") ? 'selected' : ''}}>Bharat BioTech</option>
								<option value="GAMALEYA SPUTNIK V" {{(old('vaccineName') == 'GAMALEYA SPUTNIK V') ? 'selected' : ''}}>Gamaleya Sputnik V</option>
								<option value="JANSSEN" {{(old('vaccineName') == "JANSSEN") ? 'selected' : ''}}>Janssen</option>
								<option value="MODERNA" {{(old('vaccineName') == 'MODERNA') ? 'selected' : ''}}>Moderna</option>
								<option value="NOVARAX" {{(old('vaccineName') == 'NOVARAX') ? 'selected' : ''}}>Novarax</option>
								<option value="OXFORD ASTRAZENECA" {{(old('vaccineName') == 'OXFORD ASTRAZENECA') ? 'selected' : ''}}>Oxford AstraZeneca</option>
								<option value="PFIZER BIONTECH" {{(old('vaccineName') == 'PFIZER BIONTECH') ? 'selected' : ''}}>Pfizer BioNTech</option>
								<option value="SINOPHARM" {{(old('vaccineName') == 'SINOPHARM') ? 'selected' : ''}}>Sinopharm</option>
								<option value="SINOVAC CORONAVAC" {{(old('vaccineName') == 'SINOVAC CORONAVAC') ? 'selected' : ''}}>Sinovac Coronavac</option>
							  </select>
							</div>
							<hr>
							<div id="ifFirstDoseVaccine">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="vaccinationDate1"><span class="text-danger font-weight-bold">*</span>First (1st) Dose Date</label>
											<input type="date" class="form-control" name="vaccinationDate1" id="vaccinationDate1" value="{{old('vaccinationDate1')}}" max="{{date('Y-m-d')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="haveAdverseEvents1"><span class="text-danger font-weight-bold">*</span>Adverse Event/s</label>
											<select class="form-control" name="haveAdverseEvents1" id="haveAdverseEvents1">
												<option value="0" {{(old('haveAdverseEvents1') == '0') ? 'selected' : ''}}>No</option>
												<option value="1" {{(old('haveAdverseEvents1') == '1') ? 'selected' : ''}}>Yes</option>
											</select>
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
								</div>
							</div>
							<div id="ifSecondDoseVaccine">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="vaccinationDate2"><span class="text-danger font-weight-bold">*</span>Second (2nd) Dose Date</label>
											<input type="date" class="form-control" name="vaccinationDate2" id="vaccinationDate2" value="{{old('vaccinationDate2')}}" max="{{date('Y-m-d')}}">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="haveAdverseEvents2"><span class="text-danger font-weight-bold">*</span>Adverse Event/s</label>
											<select class="form-control" name="haveAdverseEvents2" id="haveAdverseEvents2">
												<option value="0" {{(old('haveAdverseEvents2') == '0') ? 'selected' : ''}}>No</option>
												<option value="1" {{(old('haveAdverseEvents2') == '1') ? 'selected' : ''}}>Yes</option>
											</select>
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
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="addresscheck">
					<div class="form-check form-check-inline">
						<label for="" class="mr-3 mt-1">Current Address is Different from Permanent Address?</label>
						<label class="form-check-label">
							<input class="form-check-input" type="radio" name="paddressdifferent" id="paddressdifferent1" value="1" @if(old('paddressdifferent') == 1) {{'checked'}} @endif> Yes
						</label>
						<label class="form-check-label">
							<input class="form-check-input ml-3" type="radio" name="paddressdifferent" id="paddressdifferent0" value="0" @if(old('paddressdifferent') == 0) {{'checked'}} @endif> No
						</label>
					</div>
				</div>
				<div id="permaaddress_div">
					<hr>
					<h5 class="font-weight-bold">Permanent Address and Contact Information</h5>
					<hr>
					<div id="permaaddresstext">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
								  <input type="text" class="form-control" name="permaaddress_province" id="permaaddress_province">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" class="form-control" name="permaaddress_city" id="permaaddress_city">
								</div>
							</div>
							<div class="col-md-4">
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
								  <input type="text" class="form-control" name="permaaddress_provincejson" id="permaaddress_provincejson">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" class="form-control" name="permaaddress_cityjson" id="permaaddress_cityjson">
								</div>
							</div>
							<div class="col-md-4">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="spermaaddress_province"><span class="text-danger font-weight-bold">*</span>Province</label>
								<select class="form-control" name="spermaaddress_province" id="spermaaddress_province">
								  <option value="" selected disabled>Choose...</option>
								</select>
									@error('spermaaddress_province')
									  <small class="text-danger">{{$message}}</small>
								  @enderror
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="spermaaddress_city"><span class="text-danger font-weight-bold">*</span>City</label>
								<select class="form-control" name="spermaaddress_city" id="spermaaddress_city">
								  <option value="" selected disabled>Choose...</option>
								</select>
								  @error('spermaaddress_city')
									  <small class="text-danger">{{$message}}</small>
								  @enderror
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="permaaddress_brgy"><span class="text-danger font-weight-bold">*</span>Barangay</label>
								<select class="form-control" name="permaaddress_brgy" id="permaaddress_brgy">
								  <option value="" selected disabled>Choose...</option>
								</select>
									@error('permaaddress_brgy')
									  <small class="text-danger">{{$message}}</small>
								  @enderror
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="permaaddress_houseno"><span class="text-danger font-weight-bold">*</span>House No./Lot/Building</label>
								<input type="text" class="form-control" id="permaaddress_houseno" name="permaaddress_houseno" value="{{old('permaaddress_houseno')}}" style="text-transform: uppercase;">
								@error('permaaddress_houseno')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="permaaddress_street"><span class="text-danger font-weight-bold">*</span>Street/Purok/Sitio</label>
								<input type="text" class="form-control" id="permaaddress_street" name="permaaddress_street" value="{{old('permaaddress_street')}}" style="text-transform: uppercase;">
								@error('permaaddress_street')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="permamobile"><span class="text-danger font-weight-bold">*</span>Cellphone No.</label>
								<input type="text" class="form-control" id="permamobile" name="permamobile" value="{{old('permamobile')}}" pattern="[0-9]{11}" placeholder="0917xxxxxxx">
								@error('permamobile')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="permaphoneno">Home Phone No. (& Area Code)</label>
								<input type="number" class="form-control" id="permaphoneno" name="permaphoneno" value="{{old('permaphoneno')}}">
								@error('permaphoneno')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="permaemail">Email Address</label>
								<input type="permaemail" class="form-control" name="permaemail" id="permaemail" value="{{old('permaemail')}}">
								@error('permaemail')
									  <small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
					</div>
				</div>
				<hr>
				@if(auth()->user()->isCompanyAccount())
				<div class="form-check form-check-inline">
					<label for="" class="mr-3 mt-1">Patient has Occupation?</label>
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="hasoccupation" id="hasoccupation_yes" value="1" checked> Yes
					</label>
				</div>
				<div id="occupation_div">
					<hr>
					<h5 class="font-weight-bold">Current Workplace Information and Address</h5>
					<hr>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="occupation"><span class="text-danger font-weight-bold">*</span>Occupation</label>
								<input type="text" class="form-control" name="occupation" id="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
								@error('occupation')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
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
						</div>
					</div>
					<div id="specifyWorkNatureDiv">
						<div class="row">
							<div class="col-md-6">
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  	<label for="natureOfWorkIfOthers"><span class="text-danger font-weight-bold">*</span>Specify</label>
								  	<input type="text" class="form-control" name="natureOfWorkIfOthers" id="natureOfWorkIfOthers" value="{{old('natureOfWorkIfOthers')}}" style="text-transform: uppercase;">
								  	@error('natureOfWorkIfOthers')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="occupation_name">Name of Workplace</small></label>
								<input type="text" class="form-control" name="occupation_name" id="occupation_name" value="{{$list->companyName}}" style="text-transform: uppercase;" readonly>
								@error('occupation_name')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="worksInClosedSetting"><span class="text-danger font-weight-bold">*</span>Works in a closed setting?</label>
								<select class="form-control" name="worksInClosedSetting" id="worksInClosedSetting">
									<option value="UNKNOWN" {{(old('worksInClosedSetting') == "UNKNOWN") ? 'selected' : ''}}>Unknown</option>
									<option value="YES" {{(old('worksInClosedSetting') == "YES") ? 'selected' : ''}}>Yes</option>
								  	<option value="NO" {{(old('worksInClosedSetting') == "NO") ? 'selected' : ''}}>No</option>
								</select>
								@error('worksInClosedSetting')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="occupation_province">Province</label>
								<input type="text" class="form-control" name="occupation_province" id="occupation_province" value="{{$list->loc_province}}" readonly>
								@error('occupation_province')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="occupation_city">City</label>
								<input type="text" class="form-control" name="occupation_city" id="occupation_city" value="{{$list->loc_city}}" readonly>
								@error('occupation_city')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="occupation_brgy">Barangay</label>
								<input type="text" class="form-control" name="occupation_brgy" id="occupation_brgy" value="{{$list->loc_brgy}}" readonly>
								@error('occupation_brgy')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="occupation_lotbldg">Lot/Building</label>
								<input type="text" class="form-control" name="occupation_lotbldg" id="occupation_lotbldg" value="{{$list->loc_lotbldg}}" readonly>
								@error('occupation_lotbldg')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="occupation_street">Street</label>
								<input type="text" class="form-control" name="occupation_street" id="occupation_street" value="{{$list->loc_street}}" readonly>
								@error('occupation_street')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="occupation_mobile">Phone/Mobile No.</label>
								<input type="text" class="form-control" name="occupation_mobile" id="occupation_mobile" value="{{$list->contactNumber}}" readonly>
								@error('occupation_mobile')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="occupation_email">Email</label>
								<input type="email" class="form-control" name="occupation_email" id="occupation_email" value="{{$list->email}}" readonly>
								@error('occupation_email')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
					</div>
				</div>
				@else
				<div class="form-check form-check-inline">
					<label for="" class="mr-3 mt-1">Patient has Occupation?</label>
					<label class="form-check-label">
						<input class="form-check-input" type="radio" name="hasoccupation" id="hasoccupation_yes" value="1" @if(old('hasoccupation') == 1) {{'checked'}} @endif> Yes
					</label>
					<label class="form-check-label">
						<input class="form-check-input ml-3" type="radio" name="hasoccupation" id="hasoccupation_no" value="0" @if(old('hasoccupation') == 0) {{'checked'}} @endif> No
					</label>
				</div>
				<div id="occupation_div">
					<hr>
					<h5 class="font-weight-bold">Current Workplace Information and Address</h5>
					<hr>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="occupation"><span class="text-danger font-weight-bold">*</span>Occupation</label>
								<input type="text" class="form-control" name="occupation" id="occupation" value="{{old('occupation')}}" style="text-transform: uppercase;">
								@error('occupation')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
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
						</div>
					</div>
					<div id="specifyWorkNatureDiv">
						<div class="row">
							<div class="col-md-6">
							</div>
							<div class="col-md-6">
								<div class="form-group">
								  	<label for="natureOfWorkIfOthers"><span class="text-danger font-weight-bold">*</span>Specify</label>
								  	<input type="text" class="form-control" name="natureOfWorkIfOthers" id="natureOfWorkIfOthers" value="{{old('natureOfWorkIfOthers')}}" style="text-transform: uppercase;">
								  	@error('natureOfWorkIfOthers')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="occupation_name">Name of Workplace <small>(Optional)</small></label>
								<input type="text" class="form-control" name="occupation_name" id="occupation_name" value="{{old('occupation_name')}}" style="text-transform: uppercase;">
								@error('occupation_name')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="worksInClosedSetting"><span class="text-danger font-weight-bold">*</span>Works in a closed setting?</label>
								<select class="form-control" name="worksInClosedSetting" id="worksInClosedSetting">
									<option value="UNKNOWN" {{(old('worksInClosedSetting') == "UNKNOWN") ? 'selected' : ''}}>Unknown</option>
									<option value="YES" {{(old('worksInClosedSetting') == "YES") ? 'selected' : ''}}>Yes</option>
								  	<option value="NO" {{(old('worksInClosedSetting') == "NO") ? 'selected' : ''}}>No</option>
								</select>
								@error('worksInClosedSetting')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
					</div>
					<div id="occupationaddresstext">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
								  <input type="text" class="form-control" name="occupation_province" id="occupation_province">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" class="form-control" name="occupation_city" id="occupation_city">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
								  <input type="text" class="form-control" name="occupation_provincejson" id="occupation_provincejson">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<input type="text" class="form-control" name="occupation_cityjson" id="occupation_cityjson">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="soccupation_province">Province <small>(Optional)</small></label>
								<select class="form-control" name="soccupation_province" id="soccupation_province">
								  <option value="" selected disabled>Choose...</option>
								</select>
									@error('soccupation_province')
									  <small class="text-danger">{{$message}}</small>
								  @enderror
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="soccupation_city">City <small>(Optional)</small></label>
								<select class="form-control" name="soccupation_city" id="soccupation_city">
								  <option value="" selected disabled>Choose...</option>
								</select>
								  @error('soccupation_city')
									  <small class="text-danger">{{$message}}</small>
								  @enderror
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="occupation_brgy">Barangay <small>(Optional)</small></label>
								<select class="form-control" name="occupation_brgy" id="occupation_brgy">
								  <option value="" selected disabled>Choose...</option>
								</select>
									@error('occupation_brgy')
									  <small class="text-danger">{{$message}}</small>
								  @enderror
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="occupation_lotbldg">Lot/Building <small>(Optional)</small></label>
								<input type="text" class="form-control" id="occupation_lotbldg" name="occupation_lotbldg" value="{{old('occupation_lotbldg')}}" style="text-transform: uppercase;">
								@error('occupation_lotbldg')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="occupation_street">Street</label>
								<input type="text" class="form-control" id="occupation_street" name="occupation_street" value="{{old('occupation_street')}}" style="text-transform: uppercase;">
								@error('occupation_street')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="occupation_mobile">Phone/Mobile No. <small>(Optional)</small></label>
								<input type="text" class="form-control" id="occupation_mobile" name="occupation_mobile" pattern="[0-9]{11}" placeholder="0917xxxxxxx" value="{{old('occupation_mobile')}}">
								@error('occupation_mobile')
									<small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<label for="occupation_email">Email <small>(Optional)</small></label>
								<input type="email" class="form-control" name="occupation_email" id="occupation_email" value="{{old('occupation_email')}}">
								@error('occupation_email')
									  <small class="text-danger">{{$message}}</small>
								@enderror
							</div>
						</div>
					</div>
				</div>
				@endif
			</div>
			<div class="card-footer text-right">
				<button type="submit" class="btn btn-primary" id="submitBtn"><i class="fas fa-save mr-2"></i>Save (CTRL + S)</button>
			</div>
		</div>
	</div>
</form>

<script>
	$(document).bind('keydown', function(e) {
		if(e.ctrlKey && (e.which == 83)) {
			e.preventDefault();
			$('#submitBtn').trigger('click');
			$('#submitBtn').prop('disabled', true);
			return false;
		}
	});
	$(document).ready(function () {
		$('#saddress_province').select2({
			theme: "bootstrap",
		});
		$('#saddress_city').select2({
			theme: "bootstrap",
		});
		$('#address_brgy').select2({
			theme: "bootstrap",
		});
		$('#natureOfWork').select2({
			theme: "bootstrap",
		});
		$('#spermaaddress_province').select2({
			theme: "bootstrap",
		});
		$('#spermaaddress_city').select2({
			theme: "bootstrap",
		});
		$('#permaaddress_brgy').select2({
			theme: "bootstrap",
		});

		$('#addresstext').hide();
		$('#permaaddresstext').hide();
		$('#occupationaddresstext').hide();
		$('#saddress_city').prop('disabled', true);
		$('#address_brgy').prop('disabled', true);
		$('#spermaaddress_city').prop('disabled', true);
		$('#permaaddress_brgy').prop('disabled', true);
		$('#soccupation_city').prop('disabled', true);
		$('#occupation_brgy').prop('disabled', true);

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
				$("#spermaaddress_province").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
				$("#soccupation_province").append('<option value="'+val.provCode+'">'+val.provDesc+'</option>');
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

		$('#spermaaddress_province').change(function (e) {
			e.preventDefault();
			$('#spermaaddress_city').prop('disabled', false);
			$('#permaaddress_brgy').prop('disabled', true);
			$('#spermaaddress_city').empty();
			$("#spermaaddress_city").append('<option value="" selected disabled>Choose...</option>');
			$('#permaaddress_brgy').empty();
			$("#permaaddress_brgy").append('<option value="" selected disabled>Choose...</option>');
			$("#permaaddress_province").val($('#spermaaddress_province option:selected').text());
			$("#permaaddress_provincejson").val($('#spermaaddress_province').val());
			
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
					if($('#spermaaddress_province').val() == val.provCode) {
						$("#spermaaddress_city").append('<option value="'+val.citymunCode+'">'+val.citymunDesc+'</option>');
					}
				});
			});
		});

		$('#soccupation_province').change(function (e) {
			e.preventDefault();
			$('#soccupation_city').prop('disabled', false);
			$('#occupation_brgy').prop('disabled', true);
			$('#soccupation_city').prop('required', true);
			$('#occupation_brgy').prop('required', false);
			$('#soccupation_city').empty();
			$("#soccupation_city").append('<option value="" selected disabled>Choose...</option>');
			$('#occupation_brgy').empty();
			$("#occupation_brgy").append('<option value="" selected disabled>Choose...</option>');
			$("#occupation_province").val($('#soccupation_province option:selected').text());
			$("#occupation_provincejson").val($('#soccupation_province').val());
			
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
					if($('#soccupation_province').val() == val.provCode) {
						$("#soccupation_city").append('<option value="'+val.citymunCode+'">'+val.citymunDesc+'</option>');
					}
				});
			});
		});

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

		$('#spermaaddress_city').change(function (e) { 
			e.preventDefault();
			$('#permaaddress_brgy').prop('disabled', false);
			$('#permaaddress_brgy').empty();
			$("#permaaddress_brgy").append('<option value="" selected disabled>Choose...</option>');
			$("#permaaddress_city").val($('#spermaaddress_city option:selected').text());
			$('#permaaddress_cityjson').val($('#spermaaddress_city').val());

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
					if($('#spermaaddress_city').val() == val.citymunCode) {
						$("#permaaddress_brgy").append('<option value="'+val.brgyDesc.toUpperCase()+'">'+val.brgyDesc.toUpperCase()+'</option>');
					}
				});
			});
		});

		$('#soccupation_city').change(function (e) { 
			e.preventDefault();
			$('#occupation_brgy').prop('disabled', false);
			$('#occupation_brgy').prop('required', true);
			$('#occupation_brgy').empty();
			$("#occupation_brgy").append('<option value="" selected disabled>Choose...</option>');
			$("#occupation_city").val($('#soccupation_city option:selected').text());
			$('#occupation_cityjson').val($('#soccupation_city').val());

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
					if($('#soccupation_city').val() == val.citymunCode) {
						$("#occupation_brgy").append('<option value="'+val.brgyDesc.toUpperCase()+'">'+val.brgyDesc.toUpperCase()+'</option>');
					}
				});
			});
		});

		$('#pdiv').hide();
		$('#occupation_div').hide();
		if($('#hasoccupation_yes').is(':checked')) {
			$('#occupation_div').show();
		}

		if($('#hasoccupation_no').is(':checked')) {
			$('#occupation_div').hide();
		}

		$('#addresscheck').change(function() {
			if($("input[name='paddressdifferent']:checked").val() == 0) {
				$('#permaaddress_div').hide();

				$('#spermaaddress_province').prop('required', false);
				$('#spermaaddress_city').prop('required', false);
				$('#permaaddress_brgy').prop('required', false);
				$('#permaaddress_houseno').prop('required', false);
				$('#permaaddress_street').prop('required', false);
				$('#permamobile').prop('required', false);
			}
			else {
				$('#permaaddress_div').show();

				$('#spermaaddress_province').prop('required', true);
				$('#spermaaddress_city').prop('required', true);
				$('#permaaddress_brgy').prop('required', true);
				$('#permaaddress_houseno').prop('required', true);
				$('#permaaddress_street').prop('required', true);
				$('#permamobile').prop('required', true);
			}
		}).trigger('change');

		$('#gender').change(function (e) {
			e.preventDefault();
			if($('#gender').val() == 'FEMALE') {
				$('#pdiv').show();
			}
			else {
				$('#pdiv').hide();
			}
		}).trigger('change');

		$('input[type=radio][name=hasoccupation]').change(function() {
			if(this.value == "0") {
				$('#occupation_div').hide();

				$('#occupation_name').prop('required', false);
				$('#natureOfWork').prop('required', false);
				$('#occupation').prop('required', false);
				$('#soccupation_province').prop('required', false);
				$('#soccupation_city').prop('required', false);
				$('#occupation_brgy').prop('required', false);
				$('#occupation_lotbldg').prop('required', false);
				$('#occupation_street').prop('required', false);
				$('#worksInClosedSetting').prop('required', false);
				$('#natureOfWork').prop('required', false);
			}
			else {
				$('#occupation_div').show();

				$('#occupation_name').prop('required', false);
				$('#natureOfWork').prop('required', true);
				$('#occupation').prop('required', true);
				$('#soccupation_province').prop('required', false);
				$('#soccupation_city').prop('required', false);
				$('#occupation_brgy').prop('required', false);
				$('#occupation_lotbldg').prop('required', false);
				$('#occupation_street').prop('required', false);
				$('#worksInClosedSetting').prop('required', true);
				$('#natureOfWork').prop('required', true);
			}
		});

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

		$('#howManyDoseVaccine').change(function (e) { 
			e.preventDefault();
			if($(this).val() == '') {
				$('#vaccineName').prop('required', false);

				$('#ifVaccinated').hide();
				$('#ifFirstDoseVaccine').hide();
				$('#ifSecondDoseVaccine').hide();

				$('#vaccinationDate1').prop('required', false);
				$('#haveAdverseEvents1').prop('required', false);
				$('#vaccinationDate2').prop('required', false);
				$('#haveAdverseEvents2').prop('required', false);
			}
			else if($(this).val() == '1') {
				$('#vaccineName').prop('required', true);

				$('#ifVaccinated').show();
				$('#ifFirstDoseVaccine').show();
				$('#ifSecondDoseVaccine').hide();

				$('#vaccinationDate1').prop('required', true);
				$('#haveAdverseEvents1').prop('required', true);
				$('#vaccinationDate2').prop('required', false);
				$('#haveAdverseEvents2').prop('required', false);
			}
			else if($(this).val() == '2') {
				$('#vaccineName').prop('required', true);

				$('#ifVaccinated').show();
				$('#ifFirstDoseVaccine').show();
				$('#ifSecondDoseVaccine').show();

				$('#vaccinationDate1').prop('required', true);
				$('#haveAdverseEvents1').prop('required', true);
				$('#vaccinationDate2').prop('required', true);
				$('#haveAdverseEvents2').prop('required', true);
			}
		}).trigger('change');
	});
</script>
@endsection
