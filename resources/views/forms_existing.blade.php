@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card border-warning mb-3">
            <div class="card-header text-center bg-warning text-danger font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i>COVID-19 CIF Already Exists for <a href="{{route('records.edit', ['record' => $form->records->id])}}">{{$form->records->getName()}}</a> <small>(Patient ID: #{{$form->records->id}} | COVID-19 CIF ID: #{{$form->id}})</small></div>
            <div class="card-body text-center">
                @if(session('msg'))
                <div class="alert alert-{{session('msgType')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                @if(auth()->user()->isCesuAccount())
                <form action="{{route('forms.soloprint.cif', ['id' => $form->id])}}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-secondary"><i class="fas fa-file-csv mr-2"></i>Export to .CSV</button>
                    @if($form->getLatestTestType() == 'ANTIGEN')
                    <a href="{{route('forms.soloprint.antigen', ['id' => $form->id, 'testType' => $form->getTestNum()])}}" class="btn btn-secondary"><i class="fa fa-print mr-2" aria-hidden="true"></i>Print Antigen Result</a>
                    @endif
                </form>
                <form action="{{route('forms.options')}}" method="POST">
                    @csrf
                    <input type="text" class="form-control d-none" name="listToPrint[]" id="" value="{{$form->id}}">
                    <div class="btn-group mb-3">
                        <button type="submit" class="btn btn-secondary" id="exportBtnStk2" name="submit" value="printsticker_alllasalle"><i class="fas fa-print mr-2"></i>Print VTM Sticker (LaSalle)</button>
                        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="exportDropdown">
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu">
                            <button type="submit" class="dropdown-item" id="exportBtnStk" name="submit" value="printsticker"><i class="fas fa-print mr-2"></i>Print VTM Sticker (ONI & LaSalle)</button>
                        </div>
                    </div>
                </form>
                @endif
                <p><strong>Birthdate: </strong> {{date('m/d/Y', strtotime($form->records->bdate))}} •
                <strong>Age/Sex: </strong> {{$form->records->getAge()}} / {{substr($form->records->gender,0,1)}}</p>
                <p><strong>Philhealth: </strong> {{(!is_null($form->records->philhealth)) ? $form->records->getPhilhealth() : 'N/A'}} • 
                    <strong>Mobile: </strong> {{$form->records->mobile}}</p>
                    <p><strong>Address: </strong> {{$form->records->getAddress()}}</p>
                    <hr>
                    <p><strong>Date Encoded / By:</strong> {{date('m/d/Y h:i A', strtotime($form->created_at))}} ({{$form->user->name}}) 
                        @if(!is_null($form->updated_by)) • <strong>Date Edited / By:</strong> {{date('m/d/Y h:i A', strtotime($form->updated_at))}} ({{$form->getEditedBy()}})@endif</p>
                    <p><strong>Morbidity Month / Week:</strong> {{date('m/d/Y (W)', strtotime($form->morbidityMonth))}} •
                        <strong>Date Reported:</strong> {{date('m/d/Y', strtotime($form->dateReported))}}</p>
                    <p><strong>DRU: </strong> {{$form->drunit}} ({{$form->drregion}} {{$form->drprovince}})</p>
                    <p><strong>Patient Type:</strong> {{$form->getType()}} • 
                        <strong>Health Status: </strong> {{$form->healthStatus}} • 
                        <strong>Classification:</strong> <span class="{{($form->caseClassification == 'Confirmed') ? 'text-danger font-weight-bold' : ''}}">{{$form->caseClassification}}</span>
                    </p>
                    <p>
                        <strong>Quarantine Status:</strong> {{$form->getQuarantineStatus()}} ({{date('m/d/Y - D', strtotime($form->dispoDate))}}) • 
                        <strong>Outcome:</strong> <span class="{{($form->outcomeCondition == 'Recovered') ? 'font-weight-bold text-success' : ''}}">{{$form->outcomeCondition}} {{(!is_null($form->getOutcomeDate())) ? '('.$form->getOutcomeDate().' - '.date('D', strtotime($form->getOutcomeDate())).')' : ''}}</span>
                    </p>
                    <hr>
                    @if($form->ifScheduled())
                    <p><strong>Most Recent Swab Date:</strong> {{$form->getLatestTestDate()}} ({{date('D', strtotime($form->getLatestTestDate()))}}) • 
                        <strong>Test Type:</strong> {{$form->getLatestTestType()}}</p>
                    <p>
                        @if(!is_null($form->getLatestTestDateReleased()))
                        <strong>Date Released: </strong> {{$form->getLatestTestDateReleased()}} ({{date('D', strtotime($form->getLatestTestDateReleased()))}}) • 
                        @endif
                        @if(!is_null($form->getLatestTestLaboratory()))
                        <strong>Laboratory: </strong> {{$form->getLatestTestLaboratory()}} • 
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
                    <p><strong>Attended: <span class="{{$atext}}">{{$form->getAttendedOnSwab()}}</span></strong>@if($l) • <strong>Linelist Last Attended Date:</strong> {{date('m/d/Y', strtotime($l->dateAndTimeCollected))}}@endif</p>
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
                <a href="{{route('forms.edit', ['form' => $form->id])}}" class="btn btn-primary btn-block">View / Edit</a>
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