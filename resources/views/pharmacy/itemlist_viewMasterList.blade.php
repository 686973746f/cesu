@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>List of Master Items</b> (Total: {{$list->total()}})</div>
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Name / Master ID</th>
                                <th>SKU Code (Master)</th>
                                <th>SKU Code (DOH)</th>
                                <th>Category</th>
                                <th>Quantity Type</th>
                                <th>Date Created / By</th>
                                <th>Date Updated / By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $ind => $i)
                            <tr>
                                <td class="text-center">{{$list->firstItem() + $ind}}</td>
                                <td><a href="{{route('pharmacy_view_masteritem', $i->id)}}">{{$i->name}}</a></td>
                                <td class="text-center">{{$i->sku_code}}</td>
                                <td class="text-center">{{($i->sku_code_doh) ? $i->sku_code_doh : 'N/A'}}</td>
                                <td class="text-center">{{$i->category}}</td>
                                <td class="text-center">{{$i->quantity_type}}</td>
                                <td class="text-center"><small>{{date('m/d/Y h:i A', strtotime($i->created_at))}} / {{$i->user->name}}</small></td>
                                <td class="text-center"><small>{{($i->getUpdatedBy()) ? date('m/d/Y h:i A', strtotime($i->updated_at)).' / '.$i->getUpdatedBy->name : ''}} </small></td>
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