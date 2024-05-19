@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>View Dispensary</b></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="mainTbl">
                        <thead class="thead-light">
                            <tr>
                                <th>Date/Time</th>
                                <th>Name</th>
                                <th>Age/Sex</th>
                                <th>Barangay</th>
                                <th>Medicine Given</th>
                                <th>Quantity</th>
                                <th>Encoder</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let table = $('#mainTbl').DataTable({
                ajax: {
                    url: "{{route('mayor_pharmacy_ajaxdispensary', ['sdate' => $sdate])}}",
                    dataSrc: ''
                },
                columns: [
                    { data: 'datetime' },
                    { data: 'name' },
                    { data: 'agesex' },
                    { data: 'barangay' },
                    { data: 'medicine_given' },
                    { data: 'quantity' },
                    { data: 'encoder' },
                ],
                order: [[0, 'desc']],
            });

            @if($sdate == date('Y-m-d'))
            setInterval(() => {
                table.ajax.reload(null, false); // user paging is not reset on reload
            }, 5000); // reload every 5 seconds
            @endif
        });
    </script>
@endsection