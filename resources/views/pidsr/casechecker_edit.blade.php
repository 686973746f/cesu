@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('pidsr_casechecker_update', [$disease, $d->EPIID])}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>Quick Edit Case</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="">Disease</label>
                          <input type="text" class="form-control" value="{{$disease}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">EPI ID</label>
                            <input type="text" class="form-control" value="{{$d->EPIID}}" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="FamilyName"><b class="text-danger">*</b>Last Name/Surname</label>
                            <input type="text" class="form-control" value="{{$d->FamilyName}}" id="FamilyName" name="FamilyName" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="FirstName"><b class="text-danger">*</b>First Name</label>
                            <input type="text" class="form-control" value="{{$d->FirstName}}" id="FirstName" name="FirstName" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" class="form-control" value="{{$d->middle_name}}" id="middle_name" name="middle_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <input type="text" class="form-control" value="{{$d->suffix}}" id="suffix" name="suffix">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="Barangay"><b class="text-danger">*</b>Barangay</label>
                    <select class="form-control" name="Barangay" id="Barangay" required>
                        @foreach($brgy_list as $b)
                        <option value="{{$b->id}}" {{($b->brgyName == $d->Barangay) ? 'selected' : ''}}>{{$b->brgyName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                          <label for="system_subdivision_id"><b class="text-danger">*</b>Subdivision</label>
                          <select class="form-control" name="system_subdivision_id" id="system_subdivision_id" required>
                          </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Streetpurok">Street/Purok</label>
                            <input type="text" class="form-control" value="{{$d->Streetpurok}}" id="Streetpurok" name="Streetpurok">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label for="system_remarks">Remarks</label>
                  <textarea class="form-control" name="system_remarks" id="system_remarks" rows="3">{{old('system_remarks', $d->system_remarks)}}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Save</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#Barangay').select2({
            theme: 'bootstrap',
        });

        $('#Barangay').on('change', function() {
            var brgy_id = $(this).val();
            if (brgy_id) {
                $.ajax({
                    url: '{{ route("getSubdivisions", ":id") }}'.replace(':id', brgy_id),
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('#system_subdivision_id').empty();
                        $('#system_subdivision_id').append('<option value="" selected disabled>Choose...</option>');
                        $.each(data, function(key, value) {
                            $('#system_subdivision_id').append('<option value="'+ key +'">'+ value +'</option>');
                        });
                        $('#system_subdivision_id').select2({
                            theme: 'bootstrap',
                        });
                        var existingSubdivisionId = '{{ $d->system_subdivision_id }}'; // Assuming you pass the existing subdivision ID from the backend
                        if(existingSubdivisionId) {
                            $('#system_subdivision_id').val(existingSubdivisionId).trigger('change');
                        }
                    }
                });
            } else {
                $('#system_subdivision_id').empty();
            }
        }).trigger('change');
    });

</script>
@endsection