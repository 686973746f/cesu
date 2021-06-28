@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{request()->ip()}}
        <div class="card">
            <div class="card-header">Pa-Swab List</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th>Date Submitted</th>
                            <th>Name</th>
                            <th>Client Type</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($list as $item)
                            <tr>
                                <td class="text-center">{{date('m/d/Y h:i:s A', strtotime($item->created_at))}}</td>
                                <td>{{$item->getName()}}</td>
                                <td class="text-center">{{$item->pType}}</td>
                                <td><small>{{$item->getAddress()}}</small></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approve_{{$item->id}}"><i class="fa fa-check-circle mr-2" aria-hidden="true"></i> Approve</button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject_{{$item->id}}"><i class="fa fa-times-circle mr-2" aria-hidden="true"></i> Reject</button>
                                </td>
                            </tr>
                        @empty
                            empty
                        @endforelse
                    </tbody>
                </table>

                <div class="pagination justify-content-center mt-3">
                    {{$list->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </div>

    @foreach($list as $item)
    <form action="">
        <div class="modal fade" id="approve_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        Body
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="reject_{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    Body
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection