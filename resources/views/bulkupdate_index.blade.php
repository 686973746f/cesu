@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('msg'))
        <div class="alert alert-{{session('msgtype')}}" role="alert">
            {{session('msg')}}
        </div>
        @endif
        <form action="{{route('bulkupdate.store')}}" method="POST" class="mainForm">
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
                                <div class="form-group">
                                    <label for="testResult">Update Result of Recent Test</label>
                                    <select class="testResult form-control" name="bu[0][testResult]" id="0">
                                      <option value="">No Changes</option>
                                      <option value="POSITIVE">Positive</option>
                                      <option value="NEGATIVE">Negative</option>
                                    </select>
                                </div>
                                <div id="ifResult">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="dateReleased"><span class="text-danger font-weight-bold">*</span>Date Released</label>
                                              <input type="date" class="dateReleased form-control" name="bu[0][dateReleased]" id="dateReleased_0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="timeReleased">Time Released</label>
                                                <input type="time" class="timeReleased form-control" name="bu[0][timeReleased]" id="timeReleased_0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="dispositionType">Update Disposition</label>
                                    <select class="form-control" name="dispositionType" id="dispositionType">
                                        <option value="">No Changes</option>
                                        <option value="1" {{(old('dispositionType') == 1) ? 'selected' : ''}}>Admitted in hospital</option>
                                        <option value="2" {{(old('dispositionType') == 2) ? 'selected' : ''}}>Admitted in isolation/quarantine facility</option>
                                        <option value="3" {{(old('dispositionType') == 3) ? 'selected' : ''}}>In home isolation/quarantine</option>
                                        <option value="4" {{(old('dispositionType') == 4) ? 'selected' : ''}}>Discharged to home</option>
                                        <option value="5" {{(old('dispositionType') == 5) ? 'selected' : ''}}>Others</option>
                                    </select>
                                </div>
                                <div id="updateDisposition">
                                    <div id="divYes5">
                                        <div class="form-group">
                                            <label for="dispositionName" id="dispositionlabel"></label>
                                            <input type="text" class="form-control" name="dispositionName" id="dispositionName" value="{{old('dispositionName')}}" style="text-transform: uppercase;">
                                        </div>
                                    </div>
                                    <div id="divYes6">
                                        <div class="form-group">
                                            <label for="dispositionDate" id="dispositiondatelabel"></label>
                                            <input type="datetime-local" class="form-control" name="dispositionDate" id="dispositionDate" value="{{old('dispositionDate', date('Y-m-d\TH:i'))}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="outcomeCondition">Update Outcome</label>
                                    <select class="outcomeCondition form-control" name="bu[0][outcomeCondition]" id="outcomeCondition_0">
                                        <option value="">No Changes</option>
                                        <option value="Recovered">Recovered</option>
                                        <option value="Died">Died</option>
                                    </select>
                                </div>
                                <div class="ifRecovered" id="ifRecovered_0">
                                    <div class="form-group">
                                      <label for="dateRecovered">Date of Recovery</label>
                                      <input type="date" class="dateRecovered form-control" name="bu[0][dateRecovered]" max="{{date('Y-m-d')}}" id="dateRecovered_0">
                                    </div>
                                </div>
                                <div class="ifDied" id="ifDied_0">
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
        var iteration = 0;
        var n = 1;
        var newRowContent = $('#divClone');
        
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

        $('.mainForm').on('change', '.testResult', function (e) {
            //var tid = parseInt($(e.target).attr('id'));

            if($(this).val() == '') {
                $('#divClone').closest('#ifResult').hide();
            }
            else {
                $('#divClone').closest('#ifResult').show();
            }
        });

        $('.testResult').trigger('change');

        $('#dispositionType').change(function (e) {
                e.preventDefault();
                $('#dispositionDate').prop("type", "datetime-local");
                
                if($(this).val() == '1' || $(this).val() == '2') {
                    $('#dispositionName').prop('required', true);
                    $('#dispositionDate').prop('required', true);
                }
                else if ($(this).val() == '3' || $(this).val() == '4') {
                    $('#dispositionName').prop('required', false);
                    $('#dispositionDate').prop('required', true);
                }
                else if ($(this).val() == '5') {
                    $('#dispositionName').prop('required', true);
                    $('#dispositionDate').prop('required', false);
                }
                else if($(this).val().length == 0){
                    $('#dispositionName').prop('required', false);
                    $('#dispositionDate').prop('required', false);
                }

                if($(this).val() == '1') {
                    $('#divYes5').show();
                    $('#divYes6').show();

                    $('#dispositionlabel').text("Name of Hospital");
                    $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
                }
                if($(this).val() == '2') {
                    $('#divYes5').show();
                    $('#divYes6').show();

                    $('#dispositionlabel').text("Name of Facility");
                    $('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
                }
                if($(this).val() == '3') {
                    $('#divYes5').hide();
                    $('#divYes6').show();

                    $('#dispositiondatelabel').text("Date and Time isolated/quarantined at home");
                }
                if($(this).val() == '4') {
                    $('#divYes5').hide();
                    $('#divYes6').show();

                    $('#dispositionDate').prop("type", "date");

                    $('#dispositiondatelabel').text("Date of Discharge");
                }
                if($(this).val() == '5') {
                    $('#divYes5').show();
                    $('#divYes6').hide();

                    $('#dispositionlabel').text("State Reason");
                }
                else if($(this).val().length == 0){
                    $('#divYes5').hide();
                    $('#divYes6').hide();
                }
            }).trigger('change');
        
        /*
        $('#testResult_'+iteration).change(function (e) { 
            e.preventDefault();
            if($(this).val() == '') {
                $('#ifResult_'+iteration).hide();
                $('#dateReleased_'+iteration).prop('required', false);
            }
            else {
                $('#ifResult_'+iteration).show();
                $('#dateReleased_'+iteration).prop('required', true);
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
        */

        

        $('#rowsToAdd').change(function (e) { 
            e.preventDefault();
            var m = parseInt($('#rowsToAdd').val());
            iteration = (0 + n);

            if(n < m) {
                while(iteration < m ) {
                    $('.patient').select2("destroy");

                    var clone = $(newRowContent).clone();
                    $(clone).find('.patient').val('');
                    $(clone).find('#headnum').text('#'+(iteration+1));
                    $(clone).find('.patient').attr('name', "bu[" + iteration + "][forms_id]");
                    $(clone).find('.testResult').attr('id', parseInt(iteration)).attr('name', "bu[" + iteration + "][testResult]");
                    $(clone).find('#ifResult0').attr('id', 'ifResult'+iteration);
                    $(clone).find('.dateReleased').attr('name', "bu[" + iteration + "][dateReleased]");
                    $(clone).find('.timeReleased').attr('name', "bu[" + iteration + "][timeReleased]");
                    $(clone).find('.dispoType').attr('name', "bu[" + iteration + "][dispoType]");
                    $(clone).find('.outcomeCondition').attr('name', "bu[" + iteration + "][outcomeCondition]");
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