@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>ABTC Branch Inventory</b></div>
                        <div><a href="{{route('abtcinv_home')}}" class="btn btn-secondary">Back</a></div>
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

                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>No.</th>
                            <th>Item</th>
                            <th>Total Available Stock</th>
                            <th>Enabled</th>
                            <th>Created by/at</th>
                            <th>Updated by/at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $d)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td>
                                <b><a href="{{route('abtcinv_branchinv_view', $d->id)}}">{{$d->master->name}}</a></b>
                            </td>
                            <td class="text-center">{{$d->getTotalQuantityAvailable()}}</td>
                            <td class="text-center">{{$d->enabled}}</td>
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

    <script>
        $('#mainTbl').dataTable();
    </script>
@endsection