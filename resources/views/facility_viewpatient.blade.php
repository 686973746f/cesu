@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('facility.update', ['id' => $data->id])}}" method="POST">
            @csrf
            <div class="card mb-3">
                <div class="card-header">View Patient Information</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="lname">Patient ID (#)</label>
                        <input type="text" class="form-control" value="{{$data->records->id}}" readonly>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <input type="text" class="form-control" value="{{$data->records->lname}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="fname">First Name (and Suffix)</label>
                                <input type="text" class="form-control" value="{{$data->records->fname}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mname">Middle Name</label>
                                <input type="text" class="form-control" value="{{(!is_null($data->records->mname)) ? $data->records->mname : 'N/A'}}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bdate">Birthdate</label>
                                <input type="date" class="form-control" value="{{$data->records->bdate}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">Age / Gender</label>
                                <input type="text" class="form-control" value="{{$data->records->getAge().' / '.$data->records->gender}}" readonly>
                            </div>
                            @if($data->records->gender == 'FEMALE')
                            <div class="form-group">
                                <label for="isPregnant">Is the Patient Pregnant?</label>
                                <input type="text" class="form-control" value="{{($data->records->isPregnant == 1) ? 'YES' : 'NO'}}" readonly>
                            </div>
                            @if($data->records->isPregnant == 1)
                            <div class="form-group">
                                <label for="lmp">Last Menstrual Period (LMP)</label>
                                <input type="text" class="form-control" value="{{($data->records->isPregnant == 1) ? date('m/d/Y', strtotime($data->PregnantLMP)) : 'N/A'}}" readonly>
                            </div>
                            @endif
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cs">Civil Status</label>
                                <input type="text" class="form-control" value="{{$data->records->cs}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nationality">Nationality</label>
                                <input type="text" class="form-control" value="{{$data->records->nationality}}" readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label for="facility_remarks">Remarks <small>(Optional)</small></label>
                      <input type="text" name="facility_remarks" id="facility_remarks" class="form-control" value="{{old('facility_remarks', $data->facility_remarks)}}">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                </div>
            </div>
        </form>
        <form action="{{route('facility.initdischarge', ['id' => $data->id])}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">Discharge Patient</div>
                <div class="card-body">
                    <div class="form-group">
                      <label for="dispoDate"><span class="text-danger font-weight-bold">*</span>Date of Discharge / Recovery</label>
                      <input type="date" class="form-control" name="dispoDate" id="dispoDate" min="{{date('Y-m-d', strtotime('-14 Days'))}}" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}">
                    </div>
                    <div class="form-group">
                      <label for="facility_remarks">Remarks <small>(Optional)</small></label>
                      <input type="text" class="form-control" name="facility_remarks" id="facility_remarks">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Note: You cannot revert this process once it is done. Click OK to Proceed.')">Discharge Patient</button>
                </div>
            </div>
        </form>
    </div>
@endsection