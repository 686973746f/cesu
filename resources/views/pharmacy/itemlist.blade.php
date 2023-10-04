@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>List of Inventory</b> (Branch: {{auth()->user()->pharmacybranch->name}} | Total: {{$list->total()}})</div>
                <div>
                    @if(!(auth()->user()->isAdminPharmacy()))
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inititem">Initialize Item</button>
                    @endif
                    @if(auth()->user()->isAdminPharmacy())
                    <a href="{{route('pharmacy_masteritem_list', ['trigger_additem' => 1])}}" class="btn btn-success">Add Master Item</a>
                    @endif
                </div>
                <!-- <div><button type="button" class="btn btn-success" data-toggle="modal" data-target="#addProduct">Add Product</button></div> -->
            </div>
        </div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="q" value="{{request()->input('q')}}" placeholder="Search By Item Name | SKU Code" style="text-transform: uppercase;" autocomplete="off" required>
                            <div class="input-group-append">
                              <button class="btn btn-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead class="thead-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>SKU Code</th>
                        <th>Current Stock</th>
                        <th>Date Added / By</th>
                        <th>Date Updated / By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $ind => $i)
                    <tr>
                        <td class="text-center">{{$list->firstItem() + $ind}}</td>
                        <td><b><a href="{{route('pharmacy_itemlist_viewitem', $i->id)}}">{{$i->pharmacysupplymaster->name}}</a></b></td>
                        <td class="text-center">{{$i->pharmacysupplymaster->sku_code}}</td>
                        <td class="text-center">{{$i->displayQty()}}</td>
                        <td class="text-center"><small>{{date('m/d/Y h:i A', strtotime($i->created_at))}} / {{$i->user->name}}</small></td>
                        <td class="text-center"><small>{{(!is_null($i->updated_by)) ? date('m/d/Y h:i A', strtotime($i->updated_at)).' / '.$i->getUpdatedBy->name : 'N/A'}}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination justify-content-center mt-3">
                {{$list->appends(request()->input())->links()}}
            </div>
        </div>
    </div>
</div>

<form action="">
    <div class="modal fade" id="inititem" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for=""></label>
                      <select class="form-control" name="" id="">
                        <option></option>
                        <option></option>
                        <option></option>
                      </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-block">Initialize</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection