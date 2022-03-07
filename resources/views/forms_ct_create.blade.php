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
                    <div class="form-group">
                        <label for="primarycc_id"><span class="text-danger font-weight-bold">*</span>Link Name of Primary CC to {{$data->records->getName()}}</label>
                        <select class="form-control" name="primarycc_id" id="primarycc_id" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exposure_date"><span class="text-danger font-weight-bold">*</span>Primary Close Contact Exposure Date</label>
                        <input type="date" class="form-control" name="exposure_date" id="exposure_date" min="{{date('Y-m-d', strtotime('-3 Months'))}}" max="{{date('Y-m-d')}}" value="{{old('exposure_date')}}" required>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Add</button>
                </div>
            </div>
        </form>
    </div>

    <script>
    $('#primarycc_id').select2({
        theme: "bootstrap",
        placeholder: 'Search Primary CC by Name / Patient ID ...',
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