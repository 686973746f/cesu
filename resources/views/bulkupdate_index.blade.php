@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('bulkupdate.store')}}" method="POST" class="mainForm">
            @csrf
            <div class="card">
                <div class="card-header">Bulk Update CIFs</div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <div id="divClone">
                        <div class="itm" id="0">
                            <div class="card mb-3">
                                <div class="card-header" id="headnum">#1</div>
                                <div class="card-body">
                                    <div class="form-group">
                                      <label for="forms_id"><span class="text-danger font-weight-bold">*</span>Name of Patient to Update</label>
                                      <select class="patient form-control" name="bu[0][forms_id]" required>
                                      </select>
                                    </div>
                                    <div class="ifPatientSelected">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="morbidityMonth">Update Morbidity Month <small>(Leave Blank if No Changes)</small></label>
                                                    <input type="date" class="morbidityMonth form-control" name="bu[0][morbidityMonth]" max="{{date('Y-m-d')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="dateReported">Update Date Reported <small>(Leave Blank if No Changes)</small></label>
                                                    <input type="date" class="dateReported form-control" name="bu[0][dateReported]" max="{{date('Y-m-d')}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="testResult">Update Result of Recent Test</label>
                                            <select class="testResult form-control" name="bu[0][testResult]">
                                              <option value="">No Changes</option>
                                              <option value="POSITIVE">Positive (+) (Will also update Case Classification to 'Confirmed')</option>
                                              <option value="NEGATIVE">Negative (-) (Will also update Case Classification to 'Non-COVID-19 Case')</option>
                                            </select>
                                        </div>
                                        <div class="ifResult">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                      <label for="dateReleased"><span class="text-danger font-weight-bold">*</span>Date Released</label>
                                                      <input type="date" class="dateReleased form-control" name="bu[0][dateReleased]" max="{{date('Y-m-d')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="timeReleased">Time Released <small>(Optional)</small></label>
                                                        <input type="time" class="timeReleased form-control" name="bu[0][timeReleased]">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="dispositionType">Update Disposition</label>
                                            <select class="dispositionType form-control" name="bu[0][dispositionType]">
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
                                                    <input type="text" class="dispositionName form-control" name="bu[0][dispositionName]" style="text-transform: uppercase;">
                                                </div>
                                            </div>
                                            <div id="divYes6">
                                                <div class="form-group">
                                                    <label for="dispositionDate" id="dispositiondatelabel"></label>
                                                    <input type="datetime-local" class="dispositionDate form-control" name="bu[0][dispositionDate]">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="outcomeCondition">Update Outcome</label>
                                            <select class="outcomeCondition form-control" name="bu[0][outcomeCondition]">
                                                <option value="">No Changes</option>
                                                <option value="Recovered">Recovered</option>
                                                <option value="Died">Died</option>
                                            </select>
                                            <div class="outcomeWarning"><small class="text-danger">Note: When Changing the Outcome to Recovered or Died, the [2.4 Case Classification] of the patient will be automatically set to "Confirmed Case".</small></div>
                                        </div>
                                        <div class="ifRecovered">
                                            <div class="form-group">
                                              <label for="dateRecovered"><span class="text-danger font-weight-bold">*</span>Date of Recovery</label>
                                              <input type="date" class="dateRecovered form-control" name="bu[0][dateRecovered]" max="{{date('Y-m-d')}}">
                                            </div>
                                        </div>
                                        <div class="ifDied" id="ifDied_0">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="outcomeDeathDate"><span class="text-danger font-weight-bold">*</span>Date of Death</label>
                                                        <input type="date" class="outcomeDeathDate form-control" name="bu[0][outcomeDeathDate]" max="{{date('Y-m-d')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="deathImmeCause"><span class="text-danger font-weight-bold">*</span>Immediate Cause</label>
                                                        <input type="text" class="deathImmeCause form-control" name="bu[0][deathImmeCause]" style="text-transform: uppercase;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="deathAnteCause">Antecedent Cause <small>(Optional)</small></label>
                                                        <input type="text" class="deathAnteCause form-control" name="bu[0][deathAnteCause]" style="text-transform: uppercase;">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="deathUndeCause">Underlying Cause <small>(Optional)</small></label>
                                                        <input type="text" class="deathUndeCause form-control" name="bu[0][deathUndeCause]" style="text-transform: uppercase;">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="contriCondi">Contributory Conditions <small>(Optional)</small></label>
                                                        <input type="text" class="contriCondi form-control" name="bu[0][contriCondi]" style="text-transform: uppercase;">
                                                    </div>
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
                    <button type="submit" class="btn btn-primary">Submit Changes</button>
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

        $('.mainForm').on('change', '.patient', function () {
            if($(this).val() == null) {
                $(this).closest('.itm').find('.ifPatientSelected').hide();
            }
            else {
                $(this).closest('.itm').find('.ifPatientSelected').show();
            }
        });

        $('.patient').trigger('change');

        $('.mainForm').on('change', '.testResult', function (e) {
            if($(this).val() == '') {
                $(this).closest('.itm').find('.ifResult').hide();
                $(this).closest('.itm').find('.dateReleased').prop('required', false);
            }
            else {
                $(this).closest('.itm').find('.ifResult').show();
                $(this).closest('.itm').find('.dateReleased').prop('required', true);
            }
        });

        $('.testResult').trigger('change');

        $('.mainForm').on('change', '.outcomeCondition', function () {
            if($(this).val() == '') {
                $(this).closest('.itm').find('.ifRecovered').hide();
                $(this).closest('.itm').find('.ifDied').hide();

                $(this).closest('.itm').find('.dateRecovered').prop('required', false);
                $(this).closest('.itm').find('.outcomeDeathDate').prop('required', false);
                $(this).closest('.itm').find('.deathImmeCause').prop('required', false);

                $(this).closest('.itm').find('.outcomeWarning').hide();    
            }
            else if($(this).val() == 'Recovered') {
                $(this).closest('.itm').find('.ifRecovered').show();
                $(this).closest('.itm').find('.ifDied').hide();

                $(this).closest('.itm').find('.dateRecovered').prop('required', true);
                $(this).closest('.itm').find('.outcomeDeathDate').prop('required', false);
                $(this).closest('.itm').find('.deathImmeCause').prop('required', false);

                $(this).closest('.itm').find('.outcomeWarning').show();
            }
            else if($(this).val() == 'Died') {
                $(this).closest('.itm').find('.ifRecovered').hide();
                $(this).closest('.itm').find('.ifDied').show();

                $(this).closest('.itm').find('.dateRecovered').prop('required', false);
                $(this).closest('.itm').find('.outcomeDeathDate').prop('required', true);
                $(this).closest('.itm').find('.deathImmeCause').prop('required', true);

                $(this).closest('.itm').find('.outcomeWarning').show();
            }
        });

        $('.outcomeCondition').trigger('change');

        $('.mainForm').on('change', '.dispositionType', function () {
            $(this).closest('.itm').find('.dispositionDate').prop("type", "datetime-local");

            if($(this).val() == '1' || $(this).val() == '2') {
                $(this).closest('.itm').find('#dispositionName').prop('required', true);
                $(this).closest('.itm').find('#dispositionDate').prop('required', true);
            }
            else if ($(this).val() == '3' || $(this).val() == '4') {
                $(this).closest('.itm').find('#dispositionName').prop('required', false);
                $(this).closest('.itm').find('#dispositionDate').prop('required', true);
            }
            else if ($(this).val() == '5') {
                $(this).closest('.itm').find('#dispositionName').prop('required', true);
                $(this).closest('.itm').find('#dispositionDate').prop('required', false);
            }
            else if($(this).val().length == 0){
                $(this).closest('.itm').find('#dispositionName').prop('required', false);
                $(this).closest('.itm').find('#dispositionDate').prop('required', false);
            }

            if($(this).val() == '1') {
                $(this).closest('.itm').find('#divYes5').show();
                $(this).closest('.itm').find('#divYes6').show();

                $(this).closest('.itm').find('#dispositionlabel').text("Name of Hospital");
                $(this).closest('.itm').find('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
            }
            if($(this).val() == '2') {
                $(this).closest('.itm').find('#divYes5').show();
                $(this).closest('.itm').find('#divYes6').show();

                $(this).closest('.itm').find('#dispositionlabel').text("Name of Facility");
                $(this).closest('.itm').find('#dispositiondatelabel').text("Date and Time Admitted in Hospital");
            }
            if($(this).val() == '3') {
                $(this).closest('.itm').find('#divYes5').hide();
                $(this).closest('.itm').find('#divYes6').show();

                $(this).closest('.itm').find('#dispositiondatelabel').text("Date and Time isolated/quarantined at home");
            }
            if($(this).val() == '4') {
                $(this).closest('.itm').find('#divYes5').hide();
                $(this).closest('.itm').find('#divYes6').show();

                $(this).closest('.itm').find('#dispositionDate').prop("type", "date");

                $(this).closest('.itm').find('#dispositiondatelabel').text("Date of Discharge");
            }
            if($(this).val() == '5') {
                $(this).closest('.itm').find('#divYes5').show();
                $(this).closest('.itm').find('#divYes6').hide();

                $(this).closest('.itm').find('#dispositionlabel').text("State Reason");
            }
            else if($(this).val().length == 0){
                $(this).closest('.itm').find('#divYes5').hide();
                $(this).closest('.itm').find('#divYes6').hide();
            }
        });

        $('.dispositionType').trigger('change');

        $('#rowsToAdd').change(function (e) { 
            e.preventDefault();
            var m = parseInt($('#rowsToAdd').val());
            iteration = (0 + n);

            if(n < m) {
                while(iteration < m ) {
                    $('.patient').select2("destroy");

                    var clone = $(newRowContent).clone();
                    $(clone).find('.patient').val('');
                    $(clone).find('.itm').attr('id', parseInt(iteration));
                    $(clone).find('#headnum').text('#'+(iteration+1));

                    //hiding sections
                    $(clone).find('.ifPatientSelected').hide();
                    $(clone).find('.ifResult').hide();
                    $(clone).find('#divYes5').hide();
                    $(clone).find('#divYes6').hide();
                    $(clone).find('.ifRecovered').hide();
                    $(clone).find('.ifDied').hide();
                    $(clone).find('.outcomeWarning').hide();

                    //clearing values
                    $(clone).find('.morbidityMonth').val('');
                    $(clone).find('.dateReported').val('');
                    $(clone).find('.dateReleased').val('');
                    $(clone).find('.timeReleased').val('');
                    $(clone).find('.dispositionName').val('');
                    $(clone).find('.dispositionDate').val('');
                    $(clone).find('.dateRecovered').val('');
                    $(clone).find('.outcomeDeathDate').val('');
                    $(clone).find('.deathImmeCause').val('');
                    $(clone).find('.deathAnteCause').val('');
                    $(clone).find('.deathUndeCause').val('');
                    $(clone).find('.contriCondi').val('');

                    $(clone).find('.patient').attr('name', "bu[" + iteration + "][forms_id]");
                    $(clone).find('.morbidityMonth').attr('name', "bu[" + iteration + "][morbidityMonth]");
                    $(clone).find('.dateReported').attr('name', "bu[" + iteration + "][dateReported]");
                    $(clone).find('.testResult').attr('name', "bu[" + iteration + "][testResult]");
                    $(clone).find('.dateReleased').attr('name', "bu[" + iteration + "][dateReleased]");
                    $(clone).find('.timeReleased').attr('name', "bu[" + iteration + "][timeReleased]");
                    $(clone).find('.dispositionType').attr('name', "bu[" + iteration + "][dispositionType]");
                    $(clone).find('.dispositionName').attr('name', "bu[" + iteration + "][dispositionName]");
                    $(clone).find('.dispositionDate').attr('name', "bu[" + iteration + "][dispositionDate]");
                    $(clone).find('.outcomeCondition').attr('name', "bu[" + iteration + "][outcomeCondition]");
                    $(clone).find('.dateRecovered').attr('name', "bu[" + iteration + "][dateRecovered]");
                    $(clone).find('.outcomeDeathDate').attr('name', "bu[" + iteration + "][outcomeDeathDate]");
                    $(clone).find('.deathImmeCause').attr('name', "bu[" + iteration + "][deathImmeCause]");
                    $(clone).find('.deathAnteCause').attr('name', "bu[" + iteration + "][deathAnteCause]");
                    $(clone).find('.deathUndeCause').attr('name', "bu[" + iteration + "][deathUndeCause]");
                    $(clone).find('.contriCondi').attr('name', "bu[" + iteration + "][contriCondi]");
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