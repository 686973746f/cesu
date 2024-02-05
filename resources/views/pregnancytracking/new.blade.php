@extends('layouts.app')

@section('content')
<form action="{{route('ptracking_store')}}" method="POST">
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Pregnancy Tracking Form - Add New Patient</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="form-group">
                  <label for="catchment_brgy"><b class="text-danger">*</b>Barangay/Catchment</label>
                  <select class="form-control" name="catchment_brgy" id="catchment_brgy" required>
                    <option value="" {{(is_null(old('catchment_brgy'))) ? 'selected' : ''}} disabled>Choose...</option>
                    @foreach($brgy_list as $b)
                    <option value="{{$b->brgyName}}" {{(old('catchment_brgy') == $b->brgyName) ? 'selected' : ''}}>{{$b->brgyName}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lname"><b class="text-danger">*</b>Last Name</label>
                            <input type="text" class="form-control" name="lname" id="lname" value="{{old('lname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="fname"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" name="fname" id="fname" value="{{old('fname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mname">Middle Name</label>
                            <input type="text" class="form-control" name="mname" id="mname" value="{{old('mname')}}" minlength="2" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\- 'Ññ]+">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="age"><b class="text-danger">*</b>Age</label>
                          <input type="number" class="form-control" name="age" id="age" min="9" max="100" value="{{old('age')}}" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                          <label for="street_purok"><b class="text-danger">*</b>Street/Purok</label>
                          <input type="text" class="form-control" name="street_purok" id="street_purok" minlength="3" value="{{old('street_purok')}}" style="text-transform: uppercase;" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lmp"><b class="text-danger">*</b>LMP</label>
                            <input type="date" class="form-control" name="lmp" id="lmp" value="{{old('lmp')}}" max="{{date('Y-m-d')}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edc"><b class="text-danger">*</b>EDC</label>
                            <input type="date" class="form-control" name="edc" id="edc" value="{{old('edc')}}" max="{{date('Y-m-d', strtotime('+12 Months'))}}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pc_done1"><b class="text-danger">*</b>Prenatal Care Done 1</label>
                            <input type="date" class="form-control" name="pc_done1" id="pc_done1" value="{{old('pc_done1')}}" max="{{date('Y-m-d')}}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pc_done2">Prenatal Care Done 2</label>
                            <input type="date" class="form-control" name="pc_done2" id="pc_done2" value="{{old('pc_done2')}}" max="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pc_done3">Prenatal Care Done 3</label>
                            <input type="date" class="form-control" name="pc_done3" id="pc_done3" value="{{old('pc_done3')}}" max="{{date('Y-m-d')}}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pc_done4">Prenatal Care Done 4</label>
                            <input type="date" class="form-control" name="pc_done4" id="pc_done4" value="{{old('pc_done4')}}" max="{{date('Y-m-d')}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">

                    </div>
                </div>
                <div class="form-group">
                    <label for="outcome"><b class="text-danger">*</b>Pregnancy Outcome</label>
                    <select class="form-control" name="outcome" id="outcome" required>
                        <option value="N/A" {{(old('outcome') == 'N/A') ? 'selected' : ''}}>N/A</option>
                        <option value="ALIVE" {{(old('outcome') == 'ALIVE') ? 'selected' : ''}}>ALIVE</option>
                        <option value="DIED" {{(old('outcome') == 'DIED') ? 'selected' : ''}}>DIED</option>
                    </select>
                    <small>(If the Outcome is death, accomplish MMRWN Report Form and submit to CHO immediately)</small>
                </div>
                
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Submit</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('#catchment_brgy').select2({
        theme: 'bootstrap',
    });
</script>
@endsection