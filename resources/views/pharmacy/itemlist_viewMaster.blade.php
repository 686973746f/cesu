@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('pharmacy_update_masteritem', $d->id)}}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><b>Manage Master Item</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="form-group">
                  <label for="name"><b class="text-danger">*</b>Name</label>
                  <input type="text" class="form-control" name="name" id="name" value="{{old('name', $d->name)}}" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku_code"><b class="text-danger">*</b>Master SKU Code</label>
                            <input type="text" class="form-control" name="sku_code" id="sku_code" value="{{old('sku_code', $d->sku_code)}}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sku_code_doh">DOH SKU Code</label>
                            <input type="text" class="form-control" name="sku_code_doh" id="sku_code_doh" value="{{old('sku_code_doh', $d->sku_code_doh)}}">
                          </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" name="description" id="description" value="{{old('description', $d->description)}}">
                </div>
                <div class="form-group">
                    <label for="category"><b class="text-danger">*</b>Category</label>
                    <select class="form-control" name="category" id="category" required>
                        <option value="GENERAL" {{(old('category', $d->category) == 'GENERAL') ? 'selected' : ''}}>GENERAL</option>
                        <option value="ANTIBIOTICS" {{(old('category', $d->category) == 'ANTIBIOTICS') ? 'selected' : ''}}>ANTIBIOTICS</option>
                        <option value="BOTTLES" {{(old('category', $d->category) == 'BOTTLES') ? 'selected' : ''}}>BOTTLES</option>
                        <option value="FAMILY PLANNING" {{(old('category', $d->category) == 'FAMILY PLANNING') ? 'selected' : ''}}>FAMILY PLANNING</option>
                        <option value="MAINTENANCE" {{(old('category', $d->category) == 'MAINTENANCE') ? 'selected' : ''}}>MAINTENANCE</option>
                        <option value="OINTMENT" {{(old('category', $d->category) == 'OINTMENT') ? 'selected' : ''}}>OINTMENT</option>
                        <option value="YELLOW RX" {{(old('category', $d->category) == 'YELLOW RX') ? 'selected' : ''}}>YELLOW RX</option>
                        <option value="OTHERS" {{(old('category', $d->category) == 'OTHERS') ? 'selected' : ''}}>OTHERS</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quantity_type"><b class="text-danger">*</b>Quantity Type</label>
                            <select class="form-control" name="quantity_type" id="quantity_type" required>
                                <option value="BOX" {{(old('category', $d->category) == 'BOX') ? 'selected' : ''}}>PER BOX</option>
                                <option value="BOTTLE" {{(old('category', $d->category) == 'GENERAL') ? 'selected' : ''}}>PER PIECE/BOTTLES</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="config_piecePerBox"><b class="text-danger">*</b>Max pieces inside per Box</label>
                            <input type="number" class="form-control" name="config_piecePerBox" id="config_piecePerBox" min="1" value="{{old('config_piecePerBox', $d->config_piecePerBox)}}">
                        </div>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class="bg-light">Created at / By</td>
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}} / {{$d->user->name}}</td>
                            <td class="bg-light">Updated at / By</td>
                            <td class="text-center">{{($d->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($d->updated_at)).' / '.$d->getUpdatedBy->name : 'N/A'}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Update</button>
            </div>
        </div>
    </form>
</div>
@endsection