@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('pharmacy_update_prescription', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>Update Prescription</b> (ID: #{{$d->id}} - Patient: {{$d->pharmacypatient->getName()}})</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="concerns_list"><span class="text-danger font-weight-bold">*</span>Requesting Medicine/s for <i>(Select all that apply)</i></label>
                    <select class="form-control" name="concerns_list[]" id="concerns_list" multiple required>
                      @foreach(App\Models\PharmacyPatient::getReasonList() as $rea)
                      <option value="{{$rea}}" {{(in_array($rea, explode(',', old('concerns_list', $d->concerns_list)))) ? 'selected' : ''}}>{{$rea}}</option>
                      @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary btn-block">Update Changes</button>
            </div>
        </div>
    </form>
</div>

<script>
    $('#concerns_list').select2({
        theme: 'bootstrap',
    });
</script>
@endsection