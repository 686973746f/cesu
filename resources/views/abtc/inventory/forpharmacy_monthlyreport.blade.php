@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>ABTC Pharmacy Report</b></div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th colspan="11">DOH</th>
                    </tr>
                    <tr>
                        <th rowspan="2">Rabies Program</th>
                        <th rowspan="2">Ending Inventory from the Previous Month ({{$previous_month->format('M')}})</th>
                        <th colspan="4">Deliveries</th>
                        <th colspan="2">Stock Transfer (DM 2014-0317)</th>
                        <th rowspan="2">Monthly Consumption</th>
                        <th rowspan="2">Expired Stocks</th>
                        <th rowspan="2">End of Stocks for the Month</th>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>LN/BN</th>
                        <th>Exp. Date</th>
                        <th>Received</th>
                        <th>Transferred</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($doh_final as $l)
                    <tr>
                        <td>{{$l['name']}}</td>
                        <td class="text-center">{{$l['ending_previous_month']}}</td>
                        <td class="text-center">
                            @if(!empty($l['deliveries_array']))
                            <ul>
                                @foreach($l['deliveries_array'] as $m)
                                    @if($m['sub_id'] == $l['sub_id'])
                                    <li>{{$m['quantity']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!empty($l['deliveries_array']))
                            <ul>
                                @foreach($l['deliveries_array'] as $m)
                                    @if($m['sub_id'] == $l['sub_id'])
                                    <li>{{$m['date']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!empty($l['deliveries_array']))
                            <ul>
                                @foreach($l['deliveries_array'] as $m)
                                    @if($m['sub_id'] == $l['sub_id'])
                                    <li>{{$m['batchlot_no']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!empty($l['deliveries_array']))
                            <ul>
                                @foreach($l['deliveries_array'] as $m)
                                    @if($m['sub_id'] == $l['sub_id'])
                                    <li>{{$m['expiry_date']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-center">{{$l['branch_received_total']}}</td>
                        <td class="text-center">{{$l['branch_transfer_total']}}</td>
                        <td class="text-center">{{$l['used_qty']}}</td>
                        <td class="text-center">{{$l['expired_qty']}}</td>
                        <td class="text-center">{{$l['ending_current_month']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th colspan="11">LGU</th>
                    </tr>
                    <tr>
                        <th rowspan="2">Rabies Program</th>
                        <th rowspan="2">Ending Inventory from the Previous Month ({{$previous_month->format('M')}})</th>
                        <th colspan="4">Deliveries</th>
                        <th colspan="2">Stock Transfer (DM 2014-0317)</th>
                        <th rowspan="2">Monthly Consumption</th>
                        <th rowspan="2">Expired Stocks</th>
                        <th rowspan="2">End of Stocks for the Month</th>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>LN/BN</th>
                        <th>Exp. Date</th>
                        <th>Received</th>
                        <th>Transferred</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lgu_final as $l)
                    <tr>
                        <td>{{$l['name']}}</td>
                        <td class="text-center">{{$l['ending_previous_month']}}</td>
                        <td class="text-center">
                            @if(!empty($l['deliveries_array']))
                            <ul>
                                @foreach($l['deliveries_array'] as $m)
                                    @if($m['sub_id'] == $l['sub_id'])
                                    <li>{{$m['quantity']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!empty($l['deliveries_array']))
                            <ul>
                                @foreach($l['deliveries_array'] as $m)
                                    @if($m['sub_id'] == $l['sub_id'])
                                    <li>{{$m['date']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!empty($l['deliveries_array']))
                            <ul>
                                @foreach($l['deliveries_array'] as $m)
                                    @if($m['sub_id'] == $l['sub_id'])
                                    <li>{{$m['batchlot_no']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!empty($l['deliveries_array']))
                            <ul>
                                @foreach($l['deliveries_array'] as $m)
                                    @if($m['sub_id'] == $l['sub_id'])
                                    <li>{{$m['expiry_date']}}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @else
                            N/A
                            @endif
                        </td>
                        <td class="text-center">{{$l['branch_received_total']}}</td>
                        <td class="text-center">{{$l['branch_transfer_total']}}</td>
                        <td class="text-center">{{$l['used_qty']}}</td>
                        <td class="text-center">{{$l['expired_qty']}}</td>
                        <td class="text-center">{{$l['ending_current_month']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <td><b>Number of Patients Vaccinated</b></td>
                        <td>{{$abtc_numberofpatients_ofmonth}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection