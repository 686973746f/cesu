@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('bulkupdate.store')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">Bulk Update CIFs</div>
                <div class="card-body">
                    <div id="divClone">
                        <div class="card mb-3">
                            <div class="card-header" id="headnum">#1</div>
                            <div class="card-body">
                                <div class="form-group">
                                  <label for="forms_id">Name of Patient</label>
                                  <select class="patient form-control" name="bu[0][forms_id]" required>
                                  </select>
                                </div>
                                <div class="form-group">
                                    <label for="testResult">Update Result of Recent Test</label>
                                    <select class="form-control" name="bu[0][testType]" id="testResult">
                                      <option value="">No Changes</option>
                                      <option value="POSITIVE">Positive</option>
                                      <option value="NEGATIVE">Negative</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label for="dateReleased">Date Released</label>
                                          <input type="date" class="form-control" name="bu[0][dateReleased]" id="dateReleased">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="timeReleased">Time Released</label>
                                            <input type="time" class="form-control" name="bu[0][timeReleased]" id="timeReleased">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dispoType">Update Disposition</label>
                                            <select class="form-control" name="bu[0][dispoType]" id="dispoType">
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
                                            <label for="outcome">Update Outcome</label>
                                            <select class="form-control" name="bu[0][outcome]" id="outcome">
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
                    <div id="cloneHere"></div>
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                              <label for="numOfEntries">Number of Entries</label>
                              <input type="number" class="form-control" name="" id="rowsToAdd" value="1" min="1" max="500">
                            </div>
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

        
        var n = 1;
        var newRowContent = $('#divClone');

        $('#rowsToAdd').change(function (e) { 
            e.preventDefault();
            var m = parseInt($('#rowsToAdd').val());
            var iteration = (0 + n);

            if(n < m) {
                while(iteration < m ) {
                    $('.patient').select2("destroy");

                    var clone = $(newRowContent).clone();
                    $(clone).find('.patient').val('');
                    $(clone).find('#headnum').text('#'+(iteration+1));
                    $(clone).find('.patient').attr('name', "bu[" + iteration + "][forms_id]");
                    $(clone).find('#testResult').attr('name', "bu[" + iteration + "][testResult]");
                    $(clone).find('#dateReleased').attr('name', "bu[" + iteration + "][dateReleased]");
                    $(clone).find('#timeReleased').attr('name', "bu[" + iteration + "][timeReleased]");
                    $(clone).find('#dispoType').attr('name', "bu[" + iteration + "][dispoType]");
                    $(clone).find('#outcome').attr('name', "bu[" + iteration + "][outcome]");
                    $(clone).appendTo($('#cloneHere'));
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

                n = m;
            }
            else {
                while(iteration > m && iteration != 0) {
                    $('#cloneHere #divClone:last').remove();

                    iteration--;
                }

                n = m;
            }
        });
    </script>
@endsection