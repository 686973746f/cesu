@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{route($postRoute, ['id' => $data->id, 'date' => $date, 'mer' => $mer])}}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">Update Monitoring Sheet</div>
                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>Currently Updating Monitoring Sheet for:
                            <hr>
                            <li>Name: {{$data->forms->records->getName()}} <small>(#{{$data->forms->records->id}})</small></li>
                            <li>Monitoring Date: {{date('m/d/Y', strtotime($date))}}</li>
                            <li>Day: {{$mer}}</li>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="fever" id="feverBox" value="1" {{($subdata && $subdata->fever) ? 'checked' : ''}}>
                                Fever
                            </label>
                        </div>
                        <div id="fBox">
                            <div class="form-group mt-3">
                                <label for="fevertemp"><span class="text-danger font-weight-bold">*</span>Temperature (in Celcius)</label>
                                <input type="number" class="form-control" name="fevertemp" id="fevertemp" step=".1" min="37.5" max="50" value="{{old('fevertemp', ($subdata && $subdata->fever) ? $subdata->fever : '')}}">
                            </div>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="cough" id="cough" value="1" {{($subdata && $subdata->cough == 1) ? 'checked' : ''}}>
                                Cough
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="sorethroat" id="sorethroat" value="1" {{($subdata && $subdata->sorethroat == 1) ? 'checked' : ''}}>
                                Sore Throat
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="dob" id="dob" value="1" {{($subdata && $subdata->dob == 1) ? 'checked' : ''}}>
                                Difficulty of Breathing
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="colds" id="colds" value="1" {{($subdata && $subdata->colds == 1) ? 'checked' : ''}}>
                                Colds
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="diarrhea" id="diarrhea" value="1" {{($subdata && $subdata->diarrhea == 1) ? 'checked' : ''}}>
                                Diarrhea
                            </label>
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Other Symptoms #1 <small><i>(Leave Blank if Not Applicable)</i></small></label>
                            <input type="text" class="form-control" name="os1" id="os1" value="{{old('os1', ($subdata && $subdata->os1) ? $subdata->os1 : '')}}">
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Other Symptoms #2 <small><i>(Leave Blank if Not Applicable)</i></small></label>
                            <input type="text" class="form-control" name="os2" id="os2" value="{{old('os2', ($subdata && $subdata->os2) ? $subdata->os2 : '')}}">
                        </div>
                        <div class="form-group mt-3">
                            <label for="">Other Symptoms #3 <small><i>(Leave Blank if Not Applicable)</i></small></label>
                            <input type="text" class="form-control" name="os3" id="os3" value="{{old('os3', ($subdata && $subdata->os3) ? $subdata->os3 : '')}}">
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" onclick="return confirm('You are about to submit your status for the Date [{{date('m/d/Y', strtotime($date))}} - {{$mer}}]. You cannot update again after your request has been submitted. Click OK to Proceed.')">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#fBox').hide();

        $('#feverBox').change(function (e) { 
            e.preventDefault();
            if(this.checked) {
                $('#fBox').show();
                $('#fevertemp').prop('required', true);
            }
            else {
                $('#fBox').hide();
                $('#fevertemp').prop('required', false);
            }
        }).trigger('change');
    });
</script>
@endsection