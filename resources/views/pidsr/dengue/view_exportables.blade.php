@extends('layouts.app')

@section('content')
    <form action="{{route('edcs_process_exportables', [$f->sys_code1, 'DENGUE'])}}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>Dengue - List of to Upload in EDCS-IS</div>
                        <div>
                            <button type="submit" class="btn btn-success" id="downloadCsv" name="submit" value="downloadCsv" disabled>Download as CSV</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
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
                                    <td class="text-center">
                                        <div>{{date('m/d/Y H:i:s', strtotime($d->created_at))}}</div>
                                        <div>by {{$d->user->name}}</div>
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