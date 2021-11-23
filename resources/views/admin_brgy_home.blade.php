@extends('layouts.app')

@section('content')
<div class="container">
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach($errors->all() as $error)
            <p class="mb-0">{{$error}}</p>
            @endforeach
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div class="font-weight-bold">Barangay Accounts</div>
                <div>
                    <a href="{{route('adminpanel.brgy.view.code')}}" class="btn btn-primary">View Referral Codes</a>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBrgyModal"><i class="fa fa-plus-circle mr-2" aria-hidden="true"></i>Add Barangay</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('adminpanel.brgy.index')}}" method="GET">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search Barangay / ID" required>
                            <div class="input-group-append">
                              <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @if(request()->input('q'))
            <div class="alert alert-info" role="alert">
                <i class="fa fa-info-circle mr-2" aria-hidden="true"></i>The search returned {{$lists->count()}} {{Str::plural('result', $lists->count())}}.
            </div>
            @endif
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <table class="table table-bordered">
                <thead class="text-center bg-light">
                    <tr>
                        <th>#</th>
                        <th>Province</th>
                        <th>City</th>
                        <th>Barangay</th>
                        <th>Number of Users</th>
                        <th>Estimated Population</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lists as $key => $list)
                    <tr>
                        <td class="text-center" style="vertical-align: middle;">{{$lists->firstItem() + $key}}</td>
                        <td class="text-center">{{$list->city->province->provinceName}}</td>
                        <td class="text-center">{{$list->city->cityName}}</td>
                        <td><a href="{{route('adminpanel.brgy.view', ['id' => $list->id])}}">{{$list->brgyName}}</a></td>
                        <td class="text-center" style="vertical-align: middle;">{{number_format($users->where('brgy_id', $list->id)->count())}}</td>
                        <td class="text-center">{{($list->estimatedPopulation == 0) ? 'N/A' : number_format($list->estimatedPopulation)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination justify-content-center mt-3">
                {{$lists->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>

@foreach($lists as $list)
    @if($users->where('brgy_id', $list->id)->count())
    <div class="modal fade" id="modal_{{$list->id}}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Existing Users in {{$list->brgyName}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users->where('brgy_id', $list->id) as $item)
                            <tr>
                                <td scope="row" style="vertical-align: middle;">{{$item->name}}</td>
                                <td style="vertical-align: middle;">{{$item->email}}</td>
                                <td style="vertical-align: middle;" class="{{($item->enabled == 1) ? 'text-success' : 'text-danger'}} text-center font-weight-bold">{{($item->enabled == 1) ? 'Enabled': 'Disabled'}}</td>
                                <td style="vertical-align: middle;" class="text-center">
                                    <button type="button" class="btn btn-primary">Disable</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach

<form action="{{route('adminpanel.brgy.store')}}" method="POST" autocomplete="off">
    @csrf
    <div class="modal fade" id="addBrgyModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Barangay</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="brgyName">Barangay Name</label>
                      <input type="text" name="brgyName" id="brgyName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection