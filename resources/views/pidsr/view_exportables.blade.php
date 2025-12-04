@extends('layouts.app')

@section('content')
    <form action="{{route('edcs_process_exportables', [$f->sys_code1, $disease])}}" method="POST">
        @csrf
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div><b>{{$disease}} - List of to Upload in EDCS-IS</b></div>
                        <div>
                            <button type="submit" class="btn btn-success" id="downloadCsv" name="submit" value="downloadCsv" disabled>Download as CSV</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <b class="text-danger">NOTE:</b> After downloading it to .CSV, please don't forget to upload the file on the EDCS-IS website at <a href="https://pidsr.doh.gov.ph">https://pidsr.doh.gov.ph</a> under Batch Upload using your respective account.
                        <hr>
                        In case if you encoded something but does not appear here, most likely the case does not fit the case definition and tagged as not eligible to upload.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Age/Sex</th>
                                    <th>Street/Purok</th>
                                    <th>Barangay</th>
                                    <th>Type</th>
                                    <th>Case Classification</th>
                                    <th>Outcome</th>
                                    <th>Created at/by</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($list as $d)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="ids[]" class="ids" value="{{$d->id}}">
                                    </td>
                                    <td class="text-center">{{$d->id}}</td>
                                    <td>{{$d->getName()}}</td>
                                    <td class="text-center">{{$d->AgeYears}}/{{$d->Sex}}</td>
                                    <td>{{$d->Streetpurok}}</td>
                                    <td class="text-center">{{$d->Barangay}}</td>
                                    <td class="text-center">{{$d->ClinClass}}</td>
                                    <td class="text-center">{{$d->getClassificationString()}}</td>
                                    <td class="text-center">{{$d->getOutcome()}}</td>
                                    <td class="text-center">
                                        <div>{{date('m/d/Y H:i:s', strtotime($d->created_at))}}</div>
                                        @if($d->created_by)
                                        <div>by {{$d->user->name}}</div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $('#checkAll').change(function() {
                $('.ids').prop('checked', $(this).prop('checked'));
                toggleDownloadButton();
            });

            $('.ids').change(function() {
                if ($('.ids:checked').length === $('.ids').length) {
                    $('#checkAll').prop('checked', true);
                } else {
                    $('#checkAll').prop('checked', false);
                }
                toggleDownloadButton();
            });

            function toggleDownloadButton() {
                if ($('.ids:checked').length > 0) {
                    $('#downloadCsv').prop('disabled', false);
                } else {
                    $('#downloadCsv').prop('disabled', true);
                }
            }
        });
    </script>
@endsection