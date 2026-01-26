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
                            <input type="date" class="form-control" name="lmp" id="lmp" value="{{ old('lmp', $d->lmp) }}" max="{{date('Y-m-d')}}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                  <label for=""><b class="text-danger">*</b>Height (cm)</label>
                                  <input type="number" class="form-control" name="height" id="height" step="0.1" value="{{old('height', $d->height)}}" min="1" max="900" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b class="text-danger">*</b>Weight (kg)</label>
                                    <input type="number" class="form-control" name="weight" id="weight" step="0.1" value="{{old('weight', $d->weight)}}" min="1" max="900" required>
                                </div>
                            </div>
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
                            <input type="date" id="edc" name="edc" class="form-control" value="{{ old('edc', $d->edc) }}" max="{{date('Y-m-d', strtotime('+1 Year'))}}" required>
                        </div>

                        <div class="form-group">
                          <label for=""><b class="text-danger">*</b>High Risk Pregnancy</label>
                          <select class="form-control" name="highrisk" id="highrisk" required>
                            <option value="" disabled {{old('highrisk', $d->highrisk) ? '' : 'selected'}}>Choose...</option>
                            <option value="Y" {{old('highrisk', $d->highrisk) == 'Y' ? 'selected' : ''}}>Yes</option>
                            <option value="N" {{old('highrisk', $d->highrisk) == 'N' ? 'selected' : ''}}>No</option>
                          </select>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header"><b>Date of Prenatal Check-up (8ANC)</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                  <label for=""><b class="text-danger">*</b>Visit 1 (Estimated)</label>
                                  <input type="date" class="form-control" name="visit1_est" id="visit1_est" value="{{old('visit1_est', $d->visit1_est)}}" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Visit 1 (Actual)</label>
                                    <input type="date" class="form-control" name="visit1" id="visit1" value="{{old('visit1', $d->visit1)}}" max="{{date('Y-m-d')}}" required>
                                    <small class="text-muted">8-13 weeks</small>
                                </div>
                                <div class="form-group">
                                  <label for="">Visit 1 Type</label>
                                  <select class="form-control" name="visit1_type" id="visit1_type">
                                    <option value="" disabled {{old('visit1_type', $d->visit1_type) ? '' : 'selected'}}>Choose...</option>
                                    <option value="PUBLIC" {{old('visit1_type', $d->visit1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                    <option value="PRIVATE" {{old('visit1_type', $d->visit1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                    <option value="OTHER RHU/BHS" {{old('visit1_type', $d->visit1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Visit 2 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit2_est" id="visit2_est" value="{{old('visit2_est', $d->visit2_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="">Visit 2 (Actual)</label>
                                      <input type="date" class="form-control" name="visit2" id="visit2" value="{{old('visit2', $d->visit2)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">14-20 weeks</small>
                                  </div>
                                  <div class="form-group">
                                    <label for="">Visit 2 Type</label>
                                    <select class="form-control" name="visit2_type" id="visit2_type">
                                      <option value="" disabled {{old('visit2_type', $d->visit2_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('visit2_type', $d->visit2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('visit2_type', $d->visit2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('visit2_type', $d->visit2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Visit 3 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit3_est" id="visit3_est" value="{{old('visit3_est', $d->visit3_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="">Visit 3 (Actual)</label>
                                      <input type="date" class="form-control" name="visit3" id="visit3" value="{{old('visit3', $d->visit3)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">21-27 weeks</small>
                                  </div>
                                  <div class="form-group">
                                    <label for="">Visit 3 Type</label>
                                    <select class="form-control" name="visit3_type" id="visit3_type">
                                      <option value="" disabled {{old('visit3_type', $d->visit3_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('visit3_type', $d->visit3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('visit3_type', $d->visit3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('visit3_type', $d->visit3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Visit 4 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit4_est" id="visit4_est" value="{{old('visit4_est', $d->visit4_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="">Visit 4 (Actual)</label>
                                      <input type="date" class="form-control" name="visit4" id="visit4" value="{{old('visit4', $d->visit4)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">28-30 weeks</small>
                                  </div>
                                  <div class="form-group">
                                    <label for="">Visit 4 Type</label>
                                    <select class="form-control" name="visit4_type" id="visit4_type">
                                      <option value="" disabled {{old('visit4_type', $d->visit4_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('visit4_type', $d->visit4_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('visit4_type', $d->visit4_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('visit4_type', $d->visit4_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                  </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Visit 5 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit5_est" id="visit5_est" value="{{old('visit5_est', $d->visit5_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="">Visit 5 (Actual)</label>
                                      <input type="date" class="form-control" name="visit5" id="visit5" value="{{old('visit5', $d->visit5)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">31-34 weeks</small>
                                  </div>
                                  <div class="form-group">
                                    <label for="">Visit 5 Type</label>
                                    <select class="form-control" name="visit5_type" id="visit5_type">
                                      <option value="" disabled {{old('visit5_type', $d->visit5_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('visit5_type', $d->visit5_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('visit5_type', $d->visit5_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('visit5_type', $d->visit5_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Visit 6 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit6_est" id="visit6_est" value="{{old('visit6_est', $d->visit6_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="">Visit 6 (Actual)</label>
                                      <input type="date" class="form-control" name="visit6" id="visit6" value="{{old('visit6', $d->visit6)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">35 weeks</small>
                                  </div>
                                  <div class="form-group">
                                    <label for="">Visit 6 Type</label>
                                    <select class="form-control" name="visit6_type" id="visit6_type">
                                      <option value="" disabled {{old('visit6_type', $d->visit6_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('visit6_type', $d->visit6_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('visit6_type', $d->visit6_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('visit6_type', $d->visit6_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Visit 7 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit7_est" id="visit7_est" value="{{old('visit7_est', $d->visit7_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="">Visit 7 (Actual)</label>
                                      <input type="date" class="form-control" name="visit7" id="visit7" value="{{old('visit7', $d->visit7)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">36 weeks</small>
                                  </div>
                                  <div class="form-group">
                                    <label for="">Visit 7 Type</label>
                                    <select class="form-control" name="visit7_type" id="visit7_type">
                                      <option value="" disabled {{old('visit7_type', $d->visit7_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('visit7_type', $d->visit7_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('visit7_type', $d->visit7_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('visit7_type', $d->visit7_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                  </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Visit 8 (Estimated)</label>
                                    <input type="date" class="form-control" name="visit8_est" id="visit8_est" value="{{old('visit8_est', $d->visit8_est)}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="">Visit 8 (Actual)</label>
                                      <input type="date" class="form-control" name="visit8" id="visit8" value="{{old('visit8', $d->visit8)}}" max="{{date('Y-m-d')}}">
                                      <small class="text-muted">37-40 weeks</small>
                                  </div>
                                  <div class="form-group">
                                    <label for="">Visit 8 Type</label>
                                    <select class="form-control" name="visit8_type" id="visit8_type">
                                      <option value="" disabled {{old('visit8_type', $d->visit8_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('visit8_type', $d->visit8_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('visit8_type', $d->visit8_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('visit8_type', $d->visit8_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header"><b>Tetanus Diptheria (Td)</b></div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Td1</label>
                                    <input type="date" class="form-control" name="td1" id="td1" value="{{old('td1', $d->td1)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Td1 Type</label>
                                    <select class="form-control" name="td1_type" id="td1_type">
                                      <option value="" disabled {{old('td1_type', $d->td1_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('td1_type', $d->td1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td1_type', $d->td1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td1_type', $d->td1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Td2</label>
                                    <input type="date" class="form-control" name="td2" id="td2" value="{{old('td2', $d->td2)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Td2 Type</label>
                                    <select class="form-control" name="td2_type" id="td2_type">
                                      <option value="" disabled {{old('td2_type', $d->td2_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('td2_type', $d->td2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td2_type', $d->td2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td2_type', $d->td2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Td3</label>
                                    <input type="date" class="form-control" name="td3" id="td3" value="{{old('td3', $d->td3)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Td3 Type</label>
                                    <select class="form-control" name="td3_type" id="td3_type">
                                      <option value="" disabled {{old('td3_type', $d->td3_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('td3_type', $d->td3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td3_type', $d->td3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td3_type', $d->td3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Td4</label>
                                    <input type="date" class="form-control" name="td4" id="td4" value="{{old('td4', $d->td4)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Td4 Type</label>
                                    <select class="form-control" name="td4_type" id="td4_type">
                                      <option value="" disabled {{old('td4_type', $d->td4_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('td4_type', $d->td4_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td4_type', $d->td4_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td4_type', $d->td4_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Td5</label>
                                    <input type="date" class="form-control" name="td5" id="td5" value="{{old('td5', $d->td5)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Td5 Type</label>
                                    <select class="form-control" name="td5_type" id="td5_type">
                                      <option value="" disabled {{old('td5_type', $d->td5_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('td5_type', $d->td5_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('td5_type', $d->td5_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('td5_type', $d->td5_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="">Deworming Date</label>
                    <input type="date" class="form-control" name="deworming_date" id="deworming_date" value="{{old('deworming_date', $d->deworming_date)}}" max="{{date('Y-m-d')}}">
                </div>

                <div class="card mt-3">
                    <div class="card-header"><b>Iron with Folic Acid (IFA) Supplementation</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">1st Visit (1st tri)</label>
                                    <input type="date" class="form-control" name="ifa1_date" id="ifa1_date" value="{{old('ifa1_date', $d->ifa1_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa1_dosage" id="ifa1_dosage" value="{{old('ifa1_dosage', $d->ifa1_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="ifa1_type" id="ifa1_type">
                                      <option value="" disabled {{old('ifa1_type', $d->ifa1_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('ifa1_type', $d->ifa1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa1_type', $d->ifa1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa1_type', $d->ifa1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">2nd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="ifa2_date" id="ifa2_date" value="{{old('ifa2_date', $d->ifa2_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa2_dosage" id="ifa2_dosage" value="{{old('ifa2_dosage', $d->ifa2_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="ifa2_type" id="ifa2_type">
                                      <option value="" disabled {{old('ifa2_type', $d->ifa2_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('ifa2_type', $d->ifa2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa2_type', $d->ifa2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa2_type', $d->ifa2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">3rd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="ifa3_date" id="ifa3_date" value="{{old('ifa3_date', $d->ifa3_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa3_dosage" id="ifa3_dosage" value="{{old('ifa3_dosage', $d->ifa3_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="ifa3_type" id="ifa3_type">
                                      <option value="" disabled {{old('ifa3_type', $d->ifa3_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('ifa3_type', $d->ifa3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa3_type', $d->ifa3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa3_type', $d->ifa3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">4th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="ifa4_date" id="ifa4_date" value="{{old('ifa4_date', $d->ifa4_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa4_dosage" id="ifa4_dosage" value="{{old('ifa4_dosage', $d->ifa4_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="ifa4_type" id="ifa4_type">
                                      <option value="" disabled {{old('ifa4_type', $d->ifa4_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('ifa4_type', $d->ifa4_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa4_type', $d->ifa4_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa4_type', $d->ifa4_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">5th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="ifa5_date" id="ifa5_date" value="{{old('ifa5_date', $d->ifa5_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa5_dosage" id="ifa5_dosage" value="{{old('ifa5_dosage', $d->ifa5_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="ifa5_type" id="ifa5_type">
                                      <option value="" disabled {{old('ifa5_type', $d->ifa5_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('ifa5_type', $d->ifa5_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa5_type', $d->ifa5_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa5_type', $d->ifa5_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">6th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="ifa6_date" id="ifa6_date" value="{{old('ifa6_date', $d->ifa6_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="ifa6_dosage" id="ifa6_dosage" value="{{old('ifa6_dosage', $d->ifa6_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="ifa6_type" id="ifa6_type">
                                      <option value="" disabled {{old('ifa6_type', $d->ifa6_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('ifa6_type', $d->ifa6_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('ifa6_type', $d->ifa6_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('ifa6_type', $d->ifa6_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header"><b>Multiple Micronutrient Supplementation (MMS)</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">1st Visit (1st tri)</label>
                                    <input type="date" class="form-control" name="mms1_date" id="mms1_date" value="{{old('mms1_date', $d->mms1_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms1_dosage" id="mms1_dosage" value="{{old('mms1_dosage', $d->mms1_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="mms1_type" id="mms1_type">
                                      <option value="" disabled {{old('mms1_type', $d->mms1_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('mms1_type', $d->mms1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms1_type', $d->mms1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms1_type', $d->mms1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">2nd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="mms2_date" id="mms2_date" value="{{old('mms2_date', $d->mms2_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms2_dosage" id="mms2_dosage" value="{{old('mms2_dosage', $d->mms2_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="mms2_type" id="mms2_type">
                                      <option value="" disabled {{old('mms2_type', $d->mms2_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('mms2_type', $d->mms2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms2_type', $d->mms2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms2_type', $d->mms2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">3rd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="mms3_date" id="mms3_date" value="{{old('mms3_date', $d->mms3_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms3_dosage" id="mms3_dosage" value="{{old('mms3_dosage', $d->mms3_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="mms3_type" id="mms3_type">
                                      <option value="" disabled {{old('mms3_type', $d->mms3_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('mms3_type', $d->mms3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms3_type', $d->mms3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms3_type', $d->mms3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">4th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="mms4_date" id="mms4_date" value="{{old('mms4_date', $d->mms4_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms4_dosage" id="mms4_dosage" value="{{old('mms4_dosage', $d->mms4_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="mms4_type" id="mms4_type">
                                      <option value="" disabled {{old('mms4_type', $d->mms4_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('mms4_type', $d->mms4_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms4_type', $d->mms4_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms4_type', $d->mms4_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">5th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="mms5_date" id="mms5_date" value="{{old('mms5_date', $d->mms5_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms5_dosage" id="mms5_dosage" value="{{old('mms5_dosage', $d->mms5_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="mms5_type" id="mms5_type">
                                      <option value="" disabled {{old('mms5_type', $d->mms5_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('mms5_type', $d->mms5_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms5_type', $d->mms5_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms5_type', $d->mms5_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">6th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="mms6_date" id="mms6_date" value="{{old('mms6_date', $d->mms6_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="mms6_dosage" id="mms6_dosage" value="{{old('mms6_dosage', $d->mms6_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="mms6_type" id="mms6_type">
                                      <option value="" disabled {{old('mms6_type', $d->mms6_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('mms6_type', $d->mms6_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('mms6_type', $d->mms6_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('mms6_type', $d->mms6_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3 d-none" id="highrisk_div">
                    <div class="card-header">
                        <b>
                        <div>FOR HIGH RISK PREGNANT</div>
                        <div>Calcium Carbonate (CC) Supplementation</div>
                        </b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">2nd Visit (2nd tri)</label>
                                    <input type="date" class="form-control" name="calcium1_date" id="calcium1_date" value="{{old('calcium1_date', $d->calcium1_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="calcium1_dosage" id="calcium1_dosage" value="{{old('calcium1_dosage', $d->calcium1_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="calcium1_type" id="calcium1_type">
                                      <option value="" disabled {{old('calcium1_type', $d->calcium1_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('calcium1_type', $d->calcium1_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('calcium1_type', $d->calcium1_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('calcium1_type', $d->calcium1_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">3rd Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="calcium2_date" id="calcium2_date" value="{{old('calcium2_date', $d->calcium2_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="calcium2_dosage" id="calcium2_dosage" value="{{old('calcium2_dosage', $d->calcium2_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="calcium2_type" id="calcium2_type">
                                      <option value="" disabled {{old('calcium2_type', $d->calcium2_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('calcium2_type', $d->calcium2_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('calcium2_type', $d->calcium2_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('calcium2_type', $d->calcium2_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">4th Visit (3rd tri)</label>
                                    <input type="date" class="form-control" name="calcium3_date" id="calcium3_date" value="{{old('calcium3_date', $d->calcium3_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="calcium3_dosage" id="calcium3_dosage" value="{{old('calcium3_dosage', $d->calcium3_dosage)}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select class="form-control" name="calcium3_type" id="calcium3_type">
                                      <option value="" disabled {{old('calcium3_type', $d->calcium3_type) ? '' : 'selected'}}>Choose...</option>
                                      <option value="PUBLIC" {{old('calcium3_type', $d->calcium3_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                                      <option value="PRIVATE" {{old('calcium3_type', $d->calcium3_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                                      <option value="OTHER RHU/BHS" {{old('calcium3_type', $d->calcium3_type) == 'OTHER RHU/BHS' ? 'selected' : ''}}>Other RHU/BHS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header"><b>Laboratory Screenings</b></div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Syphilis Date Screened</label>
                                    <input type="date" class="form-control" name="syphilis_date" id="syphilis_date" value="{{old('syphilis_date', $d->syphilis_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Result</label>
                                    <select class="form-control" name="syphilis_result" id="syphilis_result">
                                      <option value="" disabled {{old('syphilis_result', $d->syphilis_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('syphilis_result', $d->syphilis_result) == '1' ? 'selected' : ''}}>Positive</option>
                                      <option value="0" {{old('syphilis_result', $d->syphilis_result) == '0' ? 'selected' : ''}}>Negative</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">HIV</label>
                                    <input type="date" class="form-control" name="hiv_date" id="hiv_date" value="{{old('hiv_date', $d->hiv_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Result</label>
                                    <select class="form-control" name="hiv_result" id="hiv_result">
                                      <option value="" disabled {{old('hiv_result', $d->hiv_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('hiv_result', $d->hiv_result) == '1' ? 'selected' : ''}}>Reactive</option>
                                      <option value="0" {{old('hiv_result', $d->hiv_result) == '0' ? 'selected' : ''}}>Negative</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Hepatitis B</label>
                                    <input type="date" class="form-control" name="hb_date" id="hb_date" value="{{old('hb_date', $d->hb_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Result</label>
                                    <select class="form-control" name="hb_result" id="hb_result">
                                      <option value="" disabled {{old('hb_result', $d->hb_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('hb_result', $d->hb_result) == '1' ? 'selected' : ''}}>Reactive</option>
                                      <option value="0" {{old('hb_result', $d->hb_result) == '0' ? 'selected' : ''}}>Negative</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">CBC/Hgb&Hct Count</label>
                                    <input type="date" class="form-control" name="cbc_date" id="cbc_date" value="{{old('cbc_date', $d->cbc_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Result</label>
                                    <select class="form-control" name="cbc_result" id="cbc_result">
                                      <option value="" disabled {{old('cbc_result', $d->cbc_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('cbc_result', $d->cbc_result) == '1' ? 'selected' : ''}}>With Anemia</option>
                                      <option value="0" {{old('cbc_result', $d->cbc_result) == '0' ? 'selected' : ''}}>W/out Anemia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Gestational Diabetes Mellitus</label>
                                    <input type="date" class="form-control" name="diabetes_date" id="diabetes_date" value="{{old('diabetes_date', $d->diabetes_date)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Result</label>
                                    <select class="form-control" name="diabetes_result" id="diabetes_result">
                                      <option value="" disabled {{old('diabetes_result', $d->diabetes_result) ? '' : 'selected'}}>Choose...</option>
                                      <option value="1" {{old('diabetes_result', $d->diabetes_result) == '1' ? 'selected' : ''}}>Positive</option>
                                      <option value="0" {{old('diabetes_result', $d->diabetes_result) == '0' ? 'selected' : ''}}>Negative</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3" id="outcome_div">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Outcome</label>
                            <select class="form-control" name="outcome" id="outcome">
                              <option value="" {{old('outcome', $d->outcome) ? '' : 'selected'}}>N/A</option>
                              <option value="FT" {{old('outcome', $d->outcome) == 'FT' ? 'selected' : ''}}>Full Term</option>
                              <option value="PT" {{old('outcome', $d->outcome) == 'PT' ? 'selected' : ''}}>Pre-term</option>
                              <option value="FD" {{old('outcome', $d->outcome) == 'FD' ? 'selected' : ''}}>Fetal Death</option>
                              <option value="AB" {{old('outcome', $d->outcome) == 'AB' ? 'selected' : ''}}>Abortion/Miscarriage</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Date and Time of Delivery</label>
                            <input type="datetime-local" class="form-control" name="delivery_date" id="delivery_date" value="{{old('delivery_date', $d->delivery_date)}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group d-none" id="delivery_type_div">
                            <label for=""><b class="text-danger">*</b>Delivery Type</label>
                            <select class="form-control" name="delivery_type" id="delivery_type">
                              <option value="" disabled {{old('delivery_type', $d->delivery_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="CS" {{old('delivery_type', $d->delivery_type) == 'CS' ? 'selected' : ''}}>Caesarean Section</option>
                              <option value="VD" {{old('delivery_type', $d->delivery_type) == 'VD' ? 'selected' : ''}}>Vaginal Delivery</option>
                              <option value="CVCD" {{old('delivery_type', $d->delivery_type) == 'CVCD' ? 'selected' : ''}}>Combined Vaginal-Cesarean Delivery</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Facility Type</label>
                            <select class="form-control" name="facility_type" id="facility_type">
                              <option value="" disabled {{old('facility_type', $d->facility_type) ? '' : 'selected'}}>Choose...</option>
                              <option value="PUBLIC" {{old('facility_type', $d->facility_type) == 'PUBLIC' ? 'selected' : ''}}>Public</option>
                              <option value="PRIVATE" {{old('facility_type', $d->facility_type) == 'PRIVATE' ? 'selected' : ''}}>Private</option>
                              <option value="NON/HEALTH FACILITY" {{old('facility_type', $d->facility_type) == 'NON/HEALTH FACILITY' ? 'selected' : ''}}>Non-Health Facility</option>
                            </select>
                        </div>
                        <div id="nonhealth_div">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Non-Health Facility Type</label>
                                <select class="form-control" name="nonhealth_type" id="nonhealth_type">
                                  <option value="" disabled {{old('nonhealth_type', $d->nonhealth_type) ? '' : 'selected'}}>Choose...</option>
                                  <option value="1" {{old('nonhealth_type', $d->nonhealth_type) == '1' ? 'selected' : ''}}>Home</option>
                                  <option value="2" {{old('nonhealth_type', $d->nonhealth_type) == '2' ? 'selected' : ''}}>Others (including emergency transport)</option>
                                </select>
                            </div>
                        </div>
                        <div id="publicprivate_div">
                            <div class="form-group">
                                <label for=""><b class="text-danger">*</b>Specify BHS, RHU/UHU, government hospitals, public infirmaries, DOH-licensed ambulance</label>
                                <input type="text" class="form-control" name="place_of_delivery" id="place_of_delivery" value="{{old('place_of_delivery', $d->place_of_delivery)}}" style="text-transform: uppercase;">
                            </div>
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="bcemoncapable" id="bcemoncapable" value="Y" {{old('bcemoncapable', $d->bcemoncapable) == 'Y' ? 'checked' : ''}}>
                                BEmONC/CEmONC capable facility
                              </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for=""><b class="text-danger">*</b>Birth Attendant</label>
                            <select class="form-control" name="attendant" id="attendant">
                              <option value="" disabled {{old('attendant', $d->attendant) ? '' : 'selected'}}>Choose...</option>
                              <option value="MD" {{old('attendant', $d->attendant) == 'MD' ? 'selected' : ''}}>Doctor</option>
                              <option value="RN" {{old('attendant', $d->attendant) == 'RN' ? 'selected' : ''}}>Nurse</option>
                              <option value="MW" {{old('attendant', $d->attendant) == 'MW' ? 'selected' : ''}}>Midwife</option>
                              <option value="O" {{old('attendant', $d->attendant) == 'O' ? 'selected' : ''}}>Others</option>
                            </select>
                        </div>
                        <div class="form-group" id="otherattendant_div">
                            <label for=""><b class="text-danger">*</b>Please specify</label>
                            <input type="text" class="form-control" name="attendant_others" id="attendant_others" value="{{old('attendant_others', $d->attendant_others)}}" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        
                    </div>
                </div>

                <div class="card mt-3 postnatal_div">
                    <div class="card-header"><b>Date of Postnatal Care</b></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Contact 1 (within 24 hours after delivery)</label>
                                    <input type="date" class="form-control" name="pnc1" id="pnc1" value="{{old('pnc1', $d->pnc1)}}" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Contact 2 (on day 3)</label>
                                    <input type="date" class="form-control" name="pnc2" id="pnc2" value="{{old('pnc2', $d->pnc2)}}" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Contact 3 (between 7-14 days)</label>
                                    <input type="date" class="form-control" name="pnc3" id="pnc3" value="{{old('pnc3', $d->pnc3)}}" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Contact 4 (6 weeks after birth)</label>
                                    <input type="date" class="form-control" name="pnc4" id="pnc4" value="{{old('pnc4', $d->pnc4)}}" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3 mb-3 postnatal_div">
                    <div class="card-header">
                        <div><b>Postpartum Supplementation</b></div>
                        <div><b>Iron with Folic Acid Supplementation</b></div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">1st Visit</label>
                                    <input type="date" class="form-control" name="pp_td1" id="pp_td1" value="{{old('pp_td1', $d->pp_td1)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="pp_td1_dosage" id="pp_td1_dosage" value="{{old('pp_td1_dosage', $d->pp_td1_dosage)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">2nd Visit</label>
                                    <input type="date" class="form-control" name="pp_td2" id="pp_td2" value="{{old('pp_td2', $d->pp_td2)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="pp_td2_dosage" id="pp_td2_dosage" value="{{old('pp_td2_dosage', $d->pp_td2_dosage)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">3rd Visit</label>
                                    <input type="date" class="form-control" name="pp_td3" id="pp_td3" value="{{old('pp_td3', $d->pp_td3)}}" max="{{date('Y-m-d')}}">
                                </div>
                                <div class="form-group">
                                    <label for="">Number of tablets given</label>
                                    <input type="number" class="form-control" name="pp_td3_dosage" id="pp_td3_dosage" value="{{old('pp_td3_dosage', $d->pp_td3_dosage)}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Date Completed Vit. A Supplementation</label>
                                    <input type="date" class="form-control" name="vita" id="vita" value="{{old('vita', $d->vita)}}" max="{{date('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group postnatal_div mt-3">
                  <label for="">Remarks</label>
                  <select class="form-control" name="pp_remarks" id="pp_remarks">
                    <option value="" disabled {{old('pp_remarks', $d->pp_remarks) ? '' : 'selected'}}>Choose...</option>
                    <option value="A" {{old('pp_remarks', $d->pp_remarks) == 'A' ? 'selected' : ''}}>Trans In</option>
                    <option value="B" {{old('pp_remarks', $d->pp_remarks) == 'B' ? 'selected' : ''}}>Trans Out before completing 4PNC</option>
                  </select>
                </div>

                <div class="form-group mt-3">
                  <label for="">Notes/Remarks</label>
                  <textarea class="form-control" name="system_remarks" id="system_remarks" rows="3">{{old('system_remarks', $d->system_remarks)}}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" id="submitBtn" class="btn btn-success btn-block">Save (CTRL + S)</button>
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
                setTimeout(function() {
                    $('#submitBtn').prop('disabled', false);
                }, 2000);
                return false;
            }
        });

        $(document).ready(function () {
            $('#edc').on('change', function () {
                if($(this).val() == '') {
                    $('#outcome_div').hide();
                }
                else {
                    let selectedDate = new Date($(this).val());
                    let today = new Date();

                    // Remove time portion
                    selectedDate.setHours(0,0,0,0);
                    today.setHours(0,0,0,0);

                    if (selectedDate <= today) {
                        $('#outcome_div').show();
                    } else {
                        $('#outcome_div').hide();
                    }
                }
            });
            
            $('#edc').trigger('change');
        });

        $('#visit1').on('change', function () {
            $('#visit1_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#visit2').on('change', function () {
            $('#visit2_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#visit3').on('change', function () {
            $('#visit3_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#visit4').on('change', function () {
            $('#visit4_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#visit5').on('change', function () {
            $('#visit5_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#visit6').on('change', function () {
            $('#visit6_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#visit7').on('change', function () {
            $('#visit7_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#visit8').on('change', function () {
            $('#visit8_type').prop('required', !!$(this).val());
        }).trigger('change');

        $('#highrisk').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'Y') {
                $('#highrisk_div').removeClass('d-none');
            }
            else {
                $('#highrisk_div').addClass('d-none');
            }
        }).trigger('change');

        $('#outcome').change(function (e) { 
            e.preventDefault();
            $('#delivery_type_div').addClass('d-none');
            $('#delivery_date').prop('required', false);
            $('#delivery_type').prop('required', false);
            $('#facility_type').prop('required', false);
            $('#attendant').prop('required', false);

            $('.postnatal_div').hide();
            $('#pp_remarks').prop('required', false);

            if($(this).val() == 'FT' || $(this).val() == 'PT' || $(this).val() == 'FD' || $(this).val() == 'AB') {
                $('#delivery_type_div').removeClass('d-none');
                $('#delivery_date').prop('required', true);
                $('#delivery_type').prop('required', true);
                $('#facility_type').prop('required', true);
                $('#attendant').prop('required', true);

                $('.postnatal_div').show();
                $('#pp_remarks').prop('required', true);
            }
        }).trigger('change');

        $('#facility_type').change(function (e) { 
            e.preventDefault();
            $('#nonhealth_div').hide();
            $('#publicprivate_div').hide();
            $('#nonhealth_type').prop('required', false);
            $('#place_of_delivery').prop('required', false);

            if($(this).val() == 'NON/HEALTH FACILITY') {
                $('#nonhealth_div').show();
                $('#nonhealth_type').prop('required', true);
            }
            else if($(this).val() == 'PUBLIC' || $(this).val() == 'PRIVATE') {
                $('#publicprivate_div').show();
                $('#place_of_delivery').prop('required', true);
            }
        }).trigger('change');

        $('#attendant').change(function (e) { 
            e.preventDefault();
            $('#otherattendant_div').hide();
            $('#attendant_others').prop('required', false);

            if($(this).val() == 'O') {
                $('#otherattendant_div').show();
                $('#attendant_others').prop('required', true);
            }
        }).trigger('change');
    </script>
@endsection