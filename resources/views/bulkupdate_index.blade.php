@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('bulkupdate.store')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">Bulk Update CIFs</div>
                <div class="card-body">
                    <div class="divClone">
                        <div class="card">
                            <div class="card-header">#1</div>
                            <div class="card-body">
                                <div class="form-group">
                                  <label for="forms_id">Name of Patient</label>
                                  <select class="patient form-control" name="bu[0]['forms_id']" required>
                                  </select>
                                </div>
                                <div class="form-group">
                                    <label for="testResult">Update Result of Recent Test</label>
                                    <select class="form-control" name="bu[0]['testType']" id="testResult">
                                      <option value="">No Changes</option>
                                      <option value="POSITIVE">Positive</option>
                                      <option value="NEGATIVE">Negative</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="">Date Released</label>
                                          <input type="date" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Time Released</label>
                                            <input type="time" class="form-control" name="" id="" aria-describedby="helpId" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Update Disposition</label>
                                            <select class="form-control" name="" id="">
                                                <option value="">No Changes</option>
                                                <option value="1">Admitted in hospital</option>
                                                <option value="2">Admitted in isolation/quarantine facility</option>
                                                <option value="3">In home isolation/quarantine</option>
                                                <option value="4">Discharged to home</option>
                                                <option value="5">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Update Outcome</label>
                                            <select class="form-control" name="" id="">
                                                <option value="">No Changes</option>
                                                <option value="Recovered">Recovered</option>
                                                <option value="Died">Died</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                          <label for="numOfEntries">Number of Entries</label>
                          <input type="number" class="form-control" name="" id="rowsToAdd" value="1" min="1" max="500">
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function () {
            $('.patient').select2({
                theme: "bootstrap",
                placeholder: 'Choose...',
                ajax: {
                    url: "{{route('bulkupdate.ajax')}}",
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
        });

        var newRowContent = $('.divClone');
        var n = 1;

        $('#rowsToAdd').change(function (e) { 
            e.preventDefault();
            var m = $('#rowsToAdd').val();
            var iteration = 0;

            if(n < m) {
                while((n+iteration) < m ) {
                    $('.patient').select2("destroy");

                    var clone = $(newRowContent).clone();
                    $(clone).find('.patient').val('');
                    $(clone).appendTo($('.divClone'));
                    $('.patient').select2({
                        theme: "bootstrap",
                        placeholder: 'Choose...',
                        ajax: {
                            url: "{{route('bulkupdate.ajax')}}",
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
                    iteration++;
                }
                
            }
            else {
                
            }
            
            
        });
    </script>
@endsection