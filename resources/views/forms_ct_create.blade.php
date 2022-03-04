@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('ct_exposure_store', ['form' => $data->id])}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header font-weight-bold">Add Exposure History to Patient {{$data->records->getName()}} (#{{$data->records->id}})</div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <p>{{Str::plural('Error', $errors->count())}} detected while creating the Exposure History of CIF:</p>
                        <hr>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </div>
                    @endif
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgType')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="is_primarycc" id="is_primarycc" value="1" {{(old('is_primarycc') == 1) ? 'checked' : ''}}>
                                    Is Primary Contact <small>(Check if Yes)</small>
                                </label>
                            </div>
                            <div id="primarycc_div" class="d-none">
                                <div class="form-group">
                                    <label for="is_primarycc_date"><span class="text-danger font-weight-bold">*</span>Primary Close Contact Exposure Date</label>
                                    <input type="date" class="form-control" name="is_primarycc_date" id="is_primarycc_date" min="{{date('Y-m-d', strtotime('-3 Months'))}}" max="{{date('Y-m-d')}}" value="{{old('is_primarycc_date')}}">
                                </div>
                                <div class="form-group">
                                  <label for="primarycc_id"></label>
                                  <select class="form-control" name="primarycc_id" id="primarycc_id">
                                  </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" name="is_secondarycc" id="is_secondarycc" value="1" {{(old('is_secondarycc') == 1) ? 'checked' : ''}}>
                                    Is Secondary Close Contact <small>(Check if Yes)</small>
                                </label>
                            </div>
                            <div id="secondarycc_div" class="d-none">
                                <div class="form-group">
                                    <label for="is_secondarycc_date"><span class="text-danger font-weight-bold">*</span>Secondary Close Contact Exposure Date</label>
                                    <input type="date" class="form-control" name="is_secondarycc_date" id="is_secondarycc_date" min="{{date('Y-m-d', strtotime('-3 Months'))}}" max="{{date('Y-m-d')}}" value="{{old('is_secondarycc_date')}}">
                                </div>
                                <div class="form-group">
                                    <label for="secondarycc_id"></label>
                                    <select class="form-control" name="secondarycc_id" id="secondarycc_id">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check">
                                <label class="form-check-label">
                                  <input type="checkbox" class="form-check-input" name="is_tertiarycc" id="is_tertiarycc" value="1" {{(old('is_tertiarycc') == 1) ? 'checked' : ''}}>
                                  Is Tertiary Close Contact <small>(Check if Yes)</small>
                                </label>
                            </div>
                            <div id="tertiarycc_div" class="d-none">
                                <div class="form-group">
                                    <label for="is_tertiarycc_date"><span class="text-danger font-weight-bold">*</span>Tertiary Close Contact Exposure Date</label>
                                    <input type="date" class="form-control" name="is_tertiarycc_date" id="is_tertiarycc_date" min="{{date('Y-m-d', strtotime('-3 Months'))}}" max="{{date('Y-m-d')}}" value="{{old('is_tertiarycc_date')}}">
                                </div>
                                <div class="form-group">
                                    <label for="tertiarycc_id"></label>
                                    <select class="form-control" name="tertiarycc_id" id="tertiarycc_id">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Save</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $('#is_primarycc').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked')) {
                $('#primarycc_div').removeClass('d-none');
                $('#is_primarycc_date').prop('required', true);
            }
            else {
                $('#primarycc_div').addClass('d-none');
                $('#is_primarycc_date').prop('required', false);
            }
        }).trigger('change');

        $('#is_secondarycc').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked')) {
                $('#secondarycc_div').removeClass('d-none');
                $('#is_secondarycc_date').prop('required', true);
            }
            else {
                $('#secondarycc_div').addClass('d-none');
                $('#is_secondarycc_date').prop('required', false);
            }
        }).trigger('change');

        $('#is_tertiarycc').change(function (e) { 
            e.preventDefault();
            if($(this).prop('checked')) {
                $('#tertiarycc_div').removeClass('d-none');
                $('#is_tertiarycc_date').prop('required', true);
            }
            else {
                $('#tertiarycc_div').addClass('d-none');
                $('#is_tertiarycc_date').prop('required', false);
            }
        }).trigger('change');

        $('#primarycc_id, #secondarycc_id, #tertiarycc_id').select2({
                theme: "bootstrap",
                placeholder: 'Search by Name / Patient ID ...',
                ajax: {
                    url: "{{route('forms.ajaxcclist')}}?self_id={{$data->records->id}}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.text,
                                    id: item.id,
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
    </script>
@endsection