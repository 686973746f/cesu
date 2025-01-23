@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>View Branch Item - {{$d->master->name}}</b> (ID: {{$d->id}})</div>
                        <div><a href="{{route('abtcinv_branchinv_home')}}" class="btn btn-secondary">Back</a></div>
                    </div>
                    <div>
                        
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <div class="card">
                    <div class="card-header"><b>Stocks List</b></div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Stock ID</th>
                                        <th>Source</th>
                                        <th>Batch No.</th>
                                        <th>Expiration Date</th>
                                        <th>Available Quantity</th>
                                        <th>Created by/at</th>
                                        <th>Updated by/at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stock_list as $s)
                                    <tr>
                                        <td>{{$s->id}}</td>
                                        <td>{{$s->source}}</td>
                                        <td>{{$s->batch_no}}</td>
                                        <td>{{date('m/d/Y', strtotime($s->expiry_date))}}</td>
                                        <td>{{$s->current_qty}}</td>
                                        <td>
                                            <div>{{date('m/d/Y h:i A', strtotime($s->created_at))}}</div>
                                            <div>by {{$s->user->name}}</div>
                                        </td>
                                        <td>
                                            @if(!is_null($s->getUpdatedBy()))
                                            <div>{{date('m/d/Y h:i A', strtotime($s->updated_at))}}</div>
                                            <div>by {{$s->getUpdatedBy->name}}</div>
                                            @else
                                            <div>N/A</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header"><b>Transactions</b> (Total: {{number_format($transaction_list->total())}})</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Transaction Date</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Quantity After</th>
                                        <th>PO No.</th>
                                        <th>Unit Price</th>
                                        <th>Total Price</th>
                                        <th>Remarks</th>
                                        <th>Date Posted/by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction_list as $t)
                                    <tr>
                                        <td>{{$t->id}}</td>
                                        <td>{{date('m/d/Y', strtotime($t->transaction_date))}}</td>
                                        <td>{{$t->displayType()}}</td>
                                        <td>{{$t->displayProcessQty()}}</td>
                                        <td>{{$t->after_qty}}</td>
                                        <td>{{$t->po_number ?: 'N/A'}}</td>
                                        <td>{{$t->unit_price ?: ''}}</td>
                                        <td>{{$t->unit_price_amount ?: ''}}</td>
                                        <td>{{$t->remarks ?: 'N/A'}}</td>
                                        <td>
                                            <div>{{date('m/d/Y H:i:s', strtotime($t->created_at))}}</div>
                                            <div>by {{$t->user->name}}</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($transaction_list->total() > 30)
                    <div class="card-footer text-right">
                        <a href="{{route('abtc_viewmore_transactions', $d->id)}}">View More Transactions</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection