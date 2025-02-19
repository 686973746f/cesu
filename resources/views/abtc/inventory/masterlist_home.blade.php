@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>ABTC Inventory Masterlist</b></div>
                        <div><a href="{{route('abtcinv_home')}}" class="btn btn-secondary">Back</a></div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#newItemModal">New Item</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif

                <table class="table table-bordered" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Enabled</th>
                            <th>Unit of Measurement</th>
                            <th>Created at/by</th>
                            <th>Updated at/by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $d)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td>
                                <button type="button" class="btn btn-link" data-toggle="modal" data-target="#view_modal{{$ind}}">
                                    <b>{{$d->name}}</b>
                                </button>

                                <form action="{{route('abtcinv_masterlist_item_update', $d->id)}}" method="POST">
                                    @csrf
                                    <div class="modal fade" id="view_modal{{$ind}}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Master Item</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="name"><b class="text-danger">*</b>Name</label>
                                                        <input type="text" class="form-control" name="name" id="name" style="text-transform: uppercase;" value="{{old('name', $d->name)}}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="enabled"><b class="text-danger">*</b>Enabled</label>
                                                        <select class="form-control" name="enabled" id="enabled" required>
                                                            <option value="Y" {{(old('enabled', $d->enabled) == 'Y') ? 'selected' : ''}}>Yes</option>
                                                            <option value="N" {{(old('enabled', $d->enabled) == 'N') ? 'selected' : ''}}>No</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <input type="text" class="form-control" name="description" id="description" value="{{old('description', $d->description)}}" style="text-transform: uppercase;">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="uom"><b class="text-danger">*</b>Unit of Measurement (UOM)</label>
                                                        <select class="form-control" name="uom" id="uom" required>
                                                            <option value="VIAL" {{(old('oum', $d->uom) == 'VIAL') ? 'selected' : ''}}>Vial</option>
                                                            <option value="BOX" {{(old('oum', $d->uom) == 'BOX') ? 'selected' : ''}}>Box</option>
                                                            <option value="Piece" {{(old('oum', $d->uom) == 'BOX') ? 'selected' : ''}}>Piece</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success btn-block">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td class="text-center">{{$d->enabled}}</td>
                            <td class="text-center">{{$d->uom}}</td>
                            <td class="text-center">
                                <div>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</div>
                                <div>by {{$d->user->name}}</div>
                            </td>
                            <td class="text-center">
                                @if(!is_null($d->getUpdatedBy()))
                                <div>{{date('m/d/Y h:i A', strtotime($d->updated_at))}}</div>
                                <div>by {{$d->getUpdatedBy->name}}</div>
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

    <form action="{{route('abtcinv_masterlist_store')}}" method="POST">
        @csrf
        <div class="modal fade" id="newItemModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                          <label for="name"><b class="text-danger">*</b>Name</label>
                          <input type="text" class="form-control" name="name" id="name" style="text-transform: uppercase;" value="{{old('name')}}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" name="description" id="description" value="{{old('description')}}" style="text-transform: uppercase;">
                        </div>
                        <div class="form-group">
                          <label for="uom"><b class="text-danger">*</b>Unit of Measurement (UOM)</label>
                          <select class="form-control" name="uom" id="uom" required>
                            <option value="" disabled {{(is_null(old('oum')) ? 'selected' : '')}}>Choose...</option>
                            <option value="VIAL" {{(old('oum') == 'VIAL') ? 'selected' : ''}}>Vial</option>
                            <option value="BOX" {{(old('oum') == 'BOX') ? 'selected' : ''}}>Box</option>
                            <option value="PIECE" {{(old('oum') == 'PIECE') ? 'selected' : ''}}>Piece</option>
                            <option value="AMPS" {{(old('oum') == 'AMPS') ? 'selected' : ''}}>Amps</option>
                          </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        $('#mainTbl').dataTable();
    </script>
@endsection