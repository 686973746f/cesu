@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>View More Transaction</b></div>
                        <div><a href="{{route('pharmacy_itemlist_viewitem', $d->id)}}" class="btn btn-secondary">Go Back</a></div>
                    </div>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="card_tbl">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Received</th>
                                <th>Issued</th>
                                <th>Balance</th>
                                <th>Total Cost</th>
                                <th>DR/SI/RIS/PTR/BL No.</th>
                                <th>Recipient/Remarks</th>
                                <th>Processed by</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $ind => $s)
                            <tr class="text-center">
                                <td class="text-center">{{$s->id}}</td>
                                <td>{{date('m/d/Y h:i A', strtotime($s->created_at))}}</td>
                                <td class="text-success">{{($s->type == 'RECEIVED') ? '+ '.$s->getQtyAndType() : ''}}</td>
                                <td class="text-danger">{{($s->type == 'ISSUED') ? '- '.$s->getQtyAndType() : ''}}</td>
                                <td>{{$s->getBalance()}}</td>
                                <td>{{($s->total_cost) ? $s->total_cost : 'N/A'}}</td>
                                <td>{{($s->drsi_number) ? $s->drsi_number : 'N/A'}}</td>
                                <td>{{$s->getRecipientAndRemarks()}}</td>
                                <td>{{$s->user->name}}</td>
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