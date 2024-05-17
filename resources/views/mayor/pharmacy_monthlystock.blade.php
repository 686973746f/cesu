@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>View Monthly Stock (Year: {{$year}}, Branch: {{auth()->user()->pharmacybranch->name}})</b></div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th rowspan="2">Name</th>
                            <th rowspan="2">Category</th>
                            <th rowspan="2" data-orderable="false">Unit</th>
                            <th rowspan="2" data-orderable="false">Current Stock</th>
                            @for($i=1;$i<=$currentMonth2;$i++)
                            <th colspan="2" data-orderable="false">{{mb_strtoupper(Carbon\Carbon::create()->month($i)->format('M'))}}</th>
                            @endfor
                        </tr>
                        <tr>
                            @for($i=1;$i<=$currentMonth2;$i++)
                            <th class="text-success" data-orderable="false">+</th>
                            <th class="text-danger" data-orderable="false">-</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($si_array as $key => $si)
                        <tr>
                            <td><b>{{$si['name']}}</b></td>
                            <td>{{$si['category']}}</td>
                            <td class="text-center">{{$si['unit']}}</td>
                            <td class="text-center"><small>{{$si['current_stock']}}</small></td>
                            @foreach($si['monthly_stocks'] as $ms)
                            <td class="text-center {{($ms['received'] != 0) ? 'text-success font-weight-bold' : ''}}">{{$ms['received']}}</td>
                            <td class="text-center {{($ms['issued'] != 0) ? 'text-danger font-weight-bold' : ''}}">{{$ms['issued']}}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('#mainTbl').dataTable({
            dom: 'Bflrtip', // This shows the buttons at the top
            buttons: [
                'excel' // This adds the Excel button
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
    </script>
@endsection