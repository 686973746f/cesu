@extends('layouts.app')

@section('content')
<style>
    div.dataTables_paginate {text-align: center}
</style>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><b>Stock Masterlist</b></div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Description</th>
                            @foreach($branches as $branch)
                                <th>{{ $branch->name }}</th>
                            @endforeach
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicines as $ind => $medicine)
                        @if($medicine->pharmacysub->firstWhere('pharmacy_branch_id', auth()->user()->pharmacy_branch_id)->include_inreport == 'Y')
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td>{{ $medicine->name }}</td>
                            @php
                                $grandTotal = 0;
                            @endphp
                            @foreach($branches as $branch)
                                @php
                                    $sub = $medicine->pharmacysub->firstWhere('pharmacy_branch_id', $branch->id);
                                    $totalStock = $sub ? $sub->substock->sum('current_piece_stock') : 0;
                                    $grandTotal += $totalStock;
                                @endphp
                                <td>
                                    <strong>Total: {{ $totalStock }}</strong><br>
                                    @if($sub)
                                        @foreach($sub->substock as $stock)
                                            @if($stock->current_piece_stock != 0)
                                            Batch: {{ $stock->batch_number }}  
                                            (Exp: {{ \Carbon\Carbon::parse($stock->expiration)->format('M d, Y') }})  
                                            → {{ $stock->current_piece_stock }}
                                            <br>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center"><strong>{{ $grandTotal }}</strong></td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('#mainTbl').dataTable({
            dom: 'QBfritp',
            buttons: [
                {
                    extend: 'excel',
                    title: '',
                },
                'copy',
            ],
        });
    </script>
@endsection