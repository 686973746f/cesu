@extends('layouts.app')

@section('content')
    @if($mode == 'EDIT')
    <form action="{{route('etcl_maternal_update', $d->id)}}" method="POST">
    @else
    <form action="{{route('etcl_maternal_store', $patient->id)}}" method="POST">
    @endif
    @csrf
    <input type="hidden" name="request_uuid" value="{{Str::uuid()}}">
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Maternal Care</b></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="registration_date"><b class="text-danger">*</b>Date of Registration</label>
                            <input type="date" class="form-control" name="registration_date" id="registration_date" value="{{old('registration_date', $d->registration_date)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="">Family Serial No.</label>
                          <input type="text" class="form-control" value="{{ $patient->inhouseFamilySerials->inhouse_familyserialno ?? 'N/A' }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Full Name / Age</label>
                            <input type="text" class="form-control" value="{{ $patient->getName() }} ({{$patient->getAge()}} {{Str::plural('year', $patient->getAge())}} old)" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">Complete Address</label>
                            <input type="text" class="form-control" value="{{ $patient->getFullAddress() }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Last Menstrual Period (LMP)</label>
                            <input type="date" class="form-control" value="{{ old('lmp', $d->lmp) }}" max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b class="text-danger">*</b>Gravida</label>
                                  <input type="number" class="form-control" name="gravida" id="gravida" value="{{old('gravida', $d->gravida)}}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b class="text-danger">*</b>Parity</label>
                                  <input type="number" class="form-control" name="parity" id="parity" value="{{old('parity', $d->parity)}}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edc"><b class="text-danger">*</b>Expected Date of Delivery (EDD)</label>
                            <input type="date" class="form-control" value="{{ old('edc', $d->edc) }}" max="{{date('Y-m-d', strtotime('+1 Year'))}}" required>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="card">
                    <div class="card-header"><b>Date of Prenatal Check-up (8ANC)</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for=""><b>*</b>Visit 1 (Estimated)</label>
                                  <input type="date" class="form-control" name="visit1_est" id="visit1_est" required>
                                </div>
                                <div class="form-group">
                                    <label for=""><b>*</b>Visit 1 (Actual)</label>
                                    <input type="date" class="form-control" name="visit1_est" id="visit1_est" required>
                                </div>
                            </div>
                            <div class="col-md-3">
        
                            </div>
                            <div class="col-md-3">
        
                            </div>
                            <div class="col-md-3">
        
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    </form>
@endsection