@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-header"><b>Pharmacy Inventory System</b></div>
                    <div class="card-body">
                        @if(session('msg'))
                        <div class="alert alert-{{session('msgtype')}}" role="alert">
                            {{session('msg')}}
                        </div>
                        @endif
                        <div class="text-center"><p class="h5">Current Branch: <b>{{auth()->user()->pharmacybranch->name}}</b></p></div>
                        <button type="button" class="btn btn-secondary btn-block" data-toggle="modal" data-target="#changeBranch">Select Branch</button>
                        <hr>
                        <a href="" class="btn btn-primary btn-block">Report Dashboard</a>
                        <a href="" class="btn btn-primary btn-block">Monthly Stock</a>
                        <a href="" class="btn btn-primary btn-block">Stocks Masterlist</a>
                        <a href="" class="btn btn-primary btn-block">Medicine Dispensary</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{route('mayor_pharmacy_change_branch')}}" method="POST">
        @csrf
        <div class="modal fade" id="changeBranch" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Pharmacy Branch</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="select_branch"><b class="text-danger"></b>Select Branch:</label>
                          <select class="form-control" name="select_branch" id="select_branch" required>
                            @foreach($list_branches as $b)
                            <option value="{{$b->id}}" {{($b->id == auth()->user()->pharmacy_branch_id) ? 'selected' : ''}}>{{$b->name}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Change</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection