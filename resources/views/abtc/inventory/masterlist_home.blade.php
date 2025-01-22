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
                            <option value="Piece" {{(old('oum') == 'BOX') ? 'selected' : ''}}>Piece</option>
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
@endsection