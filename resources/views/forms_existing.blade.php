@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card border-warning mb-3">
            <div class="card-header text-center bg-warning text-danger font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i>CIF Already Exists for {{$form->records->getName()}} <small>(#{{$form->records->id}})</small></div>
            <div class="card-body text-center">
                @if(auth()->user()->isCesuAccount())
                <form action="{{route('forms.soloprint.cif', ['id' => $form->id])}}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-file-csv mr-2"></i>Export to .CSV</button>
                    @if($form->getLatestTestType() == 'ANTIGEN')
                    <a href="{{route('forms.soloprint.antigen', ['id' => $form->id, 'testType' => $form->getTestNum()])}}" class="btn btn-sm btn-primary"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print Antigen Result</a>
                    @endif
                </form>
                @endif
                <p><strong>Philhealth: </strong> {{(!is_null($form->records->philhealth)) ? $form->records->getPhilhealth() : 'N/A'}} • 
                    <strong>Mobile: </strong> {{$form->records->mobile}}</p>
                    <p><strong>Date Encoded / By:</strong> {{date('m/d/Y h:i A', strtotime($form->created_at))}} ({{$form->user->name}}) 
                        @if(!is_null($form->updated_by)) • <strong>Date Edited / By:</strong> {{date('m/d/Y h:i A', strtotime($form->updated_at))}} ({{$form->getEditedBy()}})@endif</p>
                    <p><strong>Morbidity Month / Week:</strong> {{date('m/d/Y (W)', strtotime($form->morbidityMonth))}} •
                        <strong>Date Reported:</strong> {{date('m/d/Y', strtotime($form->dateReported))}}</p>
                    <p><strong>Patient Type:</strong> {{$form->getType()}} • 
                        <strong>Health Status: </strong> {{$form->healthStatus}} • 
                        <strong>Classification:</strong> <span class="{{($form->caseClassification == 'Confirmed') ? 'text-danger font-weight-bold' : ''}}">{{$form->caseClassification}}</span>
                    </p>
                    <p>
                        <strong>Quarantine/Disposition Status:</strong> {{$form->getQuarantineStatus()}} • 
                        <strong>Outcome:</strong> <span class="{{($form->outcomeCondition == 'Recovered') ? 'font-weight-bold text-success' : ''}}">{{$form->outcomeCondition}}</span>
                    </p>
                    <hr>
                    @if($form->ifScheduled())
                    <p><strong>Latest Date of Swab Collection:</strong> {{$form->getLatestTestDate()}} • 
                        <strong>Test Type:</strong> {{$form->getLatestTestType()}} • 
                        @if(!is_null($form->getLatestTestDateReleased()))
                        <strong>Date Released: </strong> {{$form->getLatestTestDateReleased()}} • 
                        @endif
                        <strong>Result:</strong> <span class="{{($form->getLatestTestResult() == 'POSITIVE' ? 'text-danger font-weight-bold' : '')}}">{{$form->getLatestTestResult()}}</span>
                    </p>
                    @php
                    if($form->getAttendedOnSwab() == 'PENDING') {
                        $atext = 'text-warning';
                    }
                    else if($form->getAttendedOnSwab() == 'YES') {
                        $atext = 'text-success';
                    }
                    else if($form->getAttendedOnSwab() == 'NO') {
                        $atext = 'text-danger';
                    }
                    @endphp
                    <p><strong>Attended: <span class="{{$atext}}">{{$form->getAttendedOnSwab()}}</span></strong></p>
                    @else
                    <p>No Swab Schedule Date found.</p>
                    @endif
                    @if($form->is_disobedient == 1)
                    <hr>
                    <p>This patient was marked as <strong class="text-danger">Disobedient</strong> on this Case.</p>
                    <p><strong>Reason: </strong>{{$form->disobedient_remarks}}</p>
                    @endif
            </div>
            <div class="card-footer text-center">
                For more details regarding the CIF of the patient, click <strong><a href="{{route('forms.edit', ['form' => $form->id])}}">HERE</a></strong>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><i class="fas fa-search mr-2"></i>New/Search CIF</div>
            <div class="card-body">
                @if(auth()->user()->isCesuAccount())
                <div class="alert alert-info text-center" role="alert">
                    <strong class="text-danger">Notice:</strong> Pending Pa-swab list can now be also searched here.
                </div>
                @endif
                <div class="form-group">
                    <label for="newList">Select Patient to Create or Search (If existing)</label>
                    <select class="form-control" name="newList" id="newList"></select>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#newList').select2({
                theme: "bootstrap",
                placeholder: 'Search by Name / Patient ID ...',
                ajax: {
                    url: "{{route('forms.ajaxList')}}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.text,
                                    id: item.id,
                                    class: item.class,
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });

        $('#newList').change(function (e) { 
            e.preventDefault();
            var d = $('#newList').select2('data')[0].class;
            if(d == 'cif') {
                var url = "{{route("forms.new", ['id' => ':id']) }}";
            }
            else if (d == 'paswab') {
                var url = "{{route("paswab.viewspecific", ['id' => ':id']) }}";
            }

            url = url.replace(':id', $(this).val());
            window.location.href = url;
        });
    </script>
@endsection