@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>Report</b> (Branch: {{auth()->user()->pharmacybranch->name}})</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <table class="table table-bordered table-striped text-center">
                        <thead class="thead-light">
                            <tr>
                                <th>Item Name</th>
                                <th>JAN</th>
                                <th>FEB</th>
                                <th>MAR</th>
                                <th>APR</th>
                                <th>MAY</th>
                                <th>JUN</th>
                                <th>JUL</th>
                                <th>AUG</th>
                                <th>SEP</th>
                                <th>OCT</th>
                                <th>NOV</th>
                                <th>DEC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PARACETAMOL</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-striped table-bordered text-center">
                        <thead class="thead-light">
                            <tr>
                                <th colspan="3">Expiring Items (after 3 Months)</th>
                            </tr>
                            <tr>
                                <th>Items Name</th>
                                <th>Quantity (in Boxes)</th>
                                <th>Expiration Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expired_list as $expired_item)
                            <tr>
                                <td>{{$expired_item->pharmacysupply->name}}</td>
                                <td>{{$expired_item->current_box_stock}}</td>
                                <td><b class="text-danger">{{date('m/d/Y', strtotime($expired_item->expiration_date))}}</b></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection