@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('msg'))
        <div class="alert alert-{{session('msgtype')}}" role="alert">
            {{session('msg')}}
        </div>
        @endif
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
                                  <label for="forms_id">Name of Patient to Update</label>
                                  <select class="patient form-control" name="bu[0][forms_id]" required>
                                  </select>
                                </div>
                                <div class="ifPatientSelected">
                                    <div class="form-group">
                                        <label for="testResult">Update Result of Recent Test</label>
                                        <select class="testResult form-control" name="bu[0][testResult]">
                                          <option value="">No Changes</option>
                                          <option value="POSITIVE">Positive</option>
                                          <option value="NEGATIVE">Negative</option>
                                        </select>
                                    </div>
                                    <div class="ifResult">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                  <label for="dateReleased">Date Released</label>
                                                  <input type="date" class="dateReleased form-control" name="bu[0][dateReleased]">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="timeReleased">Time Released</label>
                                                    <input type="time" class="timeReleased form-control" name="bu[0][timeReleased]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="dispoType">Update Disposition</label>
                                        <select class="dispoType form-control" name="bu[0][dispoType]">
                                            <option value="">No Changes</option>
                                            <option value="1">Admitted in hospital</option>
                                            <option value="2">Admitted in isolation/quarantine facility</option>
                                            <option value="3">In home isolation/quarantine</option>
                                            <option value="4">Discharged to home</option>
                                            <option value="5">Others</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="outcomeCondition">Update Outcome</label>
                                        <select class="outcomeCondition form-control" name="bu[0][outcomeCondition]">
                                            <option value="">No Changes</option>
                                            <option value="Recovered">Recovered</option>
                                            <option value="Died">Died</option>
                                        </select>
                                    </div>
                                    <div class="ifRecovered">
                                        <div class="form-group">
                                          <label for="dateRecovered">Date of Recovery</label>
                                          <input type="date" class="dateRecovered form-control" name="bu[0][dateRecovered]" max="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="ifDied">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="outcomeDeathDate"><span class="text-danger font-weight-bold">*</span>Date of Death</label>
                                                    <input type="date" class="outcomeDeathDate form-control" name="outcomeDeathDate" max="2021-09-08" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="deathImmeCause"><span class="text-danger font-weight-bold">*</span>Immediate Cause</label>
                                                    <input type="text" class="form-control" name="deathImmeCause" id="deathImmeCause" value="" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="deathAnteCause">Antecedent Cause <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="deathAnteCause" id="deathAnteCause" value="" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="deathUndeCause">Underlying Cause <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="deathUndeCause" id="deathUndeCause" value="" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="deathUndeCause">Contributory Conditions <small>(Optional)</small></label>
                                                    <input type="text" class="form-control" name="contriCondi" id="contriCondi" value="" style="text-transform: uppercase;">
                                                </div>
                                            </div>
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
        var iteration = 0;

        $('.patient').change(function (e) { 
            e.preventDefault();
            if($(this).val() == null) {
                $('.ifPatientSelected').hide();
            }
            else {
                $('.ifPatientSelected').show();
            }
        }).trigger('change');
        
        $('.testResult').change(function (e) { 
            e.preventDefault();
            e.preventDefault();
            if($(this).val() == '') {
                $('.ifResult').hide();
                $('.dateReleased').prop('required', false);
            }
            else {
                $('.ifResult').show();
                $('.dateReleased').prop('required', true);
            }
        }).trigger('change');

        $('.outcomeCondition').change(function (e) {
            e.preventDefault();
            if($(this).val() == '') {
                $('.ifRecovered').hide();
                $('.ifDied').hide();

                $('.dateRecovered').prop('required', false);
                $('.outcomeDeathDate').prop('required', false);
                $('.deathImmeCause').prop('required', false);
            }
            else if($(this).val() == 'Recovered') {
                $('.ifRecovered').show();
                $('.ifDied').hide();

                $('.dateRecovered').prop('required', true);
                $('.outcomeDeathDate').prop('required', false);
                $('.deathImmeCause').prop('required', false);
            }
            else if($(this).val() == 'Died') {
                $('.ifRecovered').hide();
                $('.ifDied').show();

                $('.dateRecovered').prop('required', false);
                $('.outcomeDeathDate').prop('required', true);
                $('.deathImmeCause').prop('required', true);
            }
        }).trigger('change');

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
                    $(clone).find('#outcomeCondition').attr('name', "bu[" + iteration + "][outcomeCondition]");
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

                    $(clone).find('.patient').change(function (e) { 
                        e.preventDefault();
                        if($(this).val() == null) {
                            $(clone).find('.ifPatientSelected').hide();
                        }
                        else {
                            $(clone).find('.ifPatientSelected').show();
                        }
                    });

                    $(clone).find('.testResult').change(function (e) { 
                        e.preventDefault();
                        if($(this).val() == '') {
                            $(clone).find('.ifResult').hide();
                            $(clone).find('.dateReleased').prop('required', false);
                        }
                        else {
                            $(clone).find('.ifResult').show();
                            $(clone).find('.dateReleased').prop('required', true);
                        }
                    });

                    $(clone).find('.outcomeCondition').change(function (e) {
                        e.preventDefault();
                        if($(this).val() == '') {
                            $(clone).find('.ifRecovered').hide();
                            $(clone).find('.ifDied').hide();

                            $(clone).find('.dateRecovered').prop('required', false);
                            $(clone).find('.outcomeDeathDate').prop('required', false);
                            $(clone).find('.deathImmeCause').prop('required', false);
                        }
                        else if($(this).val() == 'Recovered') {
                            $(clone).find('.ifRecovered').show();
                            $(clone).find('.ifDied').hide();

                            $(clone).find('.dateRecovered').prop('required', true);
                            $(clone).find('.outcomeDeathDate').prop('required', false);
                            $(clone).find('.deathImmeCause').prop('required', false);
                        }
                        else if($(this).val() == 'Died') {
                            $(clone).find('.ifRecovered').hide();
                            $(clone).find('.ifDied').show();

                            $(clone).find('.dateRecovered').prop('required', false);
                            $(clone).find('.outcomeDeathDate').prop('required', true);
                            $(clone).find('.deathImmeCause').prop('required', true);
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