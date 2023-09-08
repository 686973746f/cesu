@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>View Patient</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Name of Patient</td>
                            <td class="text-center">{{$d->getName()}}</td>
                            <td>Patient ID</td>
                            <td class="text-center">#{{$d->id}}</td>
                        </tr>
                        <tr>
                            <td>Patient QR</td>
                            <td class="text-center">
                                <div>{!! QrCode::size(70)->generate($d->qr) !!}</div>
                                <div>{{$d->qr}}</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div><b>Stock Card / Transactions</b></div>
                            <div></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="card_tbl">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Name of Meds</th>
                                        <th>QTY Issued</th>
                                        <th>Processed by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scard as $ind => $s)
                                    <tr class="text-center">
                                        <td class="text-center">{{$ind+1}}</td>
                                        <td>{{date('m/d/Y h:i A', strtotime($s->created_at))}}</td>
                                        <td>{{$s->pharmacysub->pharmacysupplymaster->name}}</td>
                                        <td>{{($s->type == 'ISSUED') ? $s->getQtyAndType() : ''}}</td>
                                        <td>{{$s->user->name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection