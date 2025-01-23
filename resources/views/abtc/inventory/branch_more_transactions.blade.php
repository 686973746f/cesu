@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>View More Transactions - {{$d->master->name}}</b> (ID: {{$d->id}})</div>
                        <div><a href="{{route('abtcinv_branchinv_view', $d->id)}}" class="btn btn-secondary">Back</a></div>
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
                            @foreach($list as $e)
                            <tr>
                                <td>{{$e->id}}</td>
                                <td>{{date('m/d/Y', strtotime($e->transaction_date))}}</td>
                                <td>{{$e->displayType()}}</td>
                                <td>{{$e->displayProcessQty()}}</td>
                                <td>{{$e->after_qty}}</td>
                                <td>{{$e->po_number ?: 'N/A'}}</td>
                                <td>{{$e->unit_price ?: ''}}</td>
                                <td>{{$e->unit_price_amount ?: ''}}</td>
                                <td>{{$e->remarks ?: 'N/A'}}</td>
                                <td>
                                    <div>{{date('m/d/Y H:i:s', strtotime($e->created_at))}}</div>
                                    <div>by {{$e->user->name}}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pagination justify-content-center mt-3">
                    {{$list->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection