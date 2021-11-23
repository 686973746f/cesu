@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">List of Account Referral Codes</div>
            <div class="card-body text-center">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Referral Code</th>
                                <th>URL</th>
                                <th>Type</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $key => $item)
                            <tr>
                                <td scope="row">{{$data->firstItem() + $key}}</td>
                                <td>{{$item->bCode}}</td>
                                <td><small><a href="{{route('rcode.check')}}?refCode={{$item->bCode}}">{{route('rcode.check')}}?refCode={{$item->bCode}}</a></small></td>
                                <td><small>{{$item->getType()}}</small></td>
                                <td><small>{{date('m/d/Y h:i A', strtotime($item->created_at))}}</small></td>
                                <td>{{$item->user->name}}</td>
                                <td class="text-{{($item->ifEnabled()) ? 'success' : 'danger'}} font-weight-bold">{{($item->ifEnabled()) ? 'ACTIVE' : 'ALREADY USED'}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination justify-content-center mt-3">
                    {{$data->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection