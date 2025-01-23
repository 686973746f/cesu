@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>View More Stocks - {{$d->master->name}}</b> (ID: {{$d->id}})</div>
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
                            @foreach($list as $e)
                            <tr>
                                <td>{{$e->id}}</td>
                                <td>{{$e->source}}</td>
                                <td>{{$e->batch_no}}</td>
                                <td>{{date('m/d/Y', strtotime($e->expiry_date))}}</td>
                                <td>{{$e->current_qty}}</td>
                                <td>
                                    <div>{{date('m/d/Y h:i A', strtotime($e->created_at))}}</div>
                                    <div>by {{$e->user->name}}</div>
                                </td>
                                <td>
                                    @if(!is_null($e->getUpdatedBy()))
                                    <div>{{date('m/d/Y h:i A', strtotime($e->updated_at))}}</div>
                                    <div>by {{$e->getUpdatedBy->name}}</div>
                                    @else
                                    <div>N/A</div>
                                    @endif
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