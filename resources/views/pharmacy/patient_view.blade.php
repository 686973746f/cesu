@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>View Patient</b></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Name of Patient</td>
                            <td>{{$d->getName()}}</td>
                            <td>Patient ID</td>
                            <td>#{{$d->id}}</td>
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
                                        <th>QTY Issued</th>
                                        <th>Processed by</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scard as $ind => $s)
                                    <tr class="text-center">
                                        <td class="text-center">{{$ind+1}}</td>
                                        <td>{{date('m/d/Y h:i A', strtotime($s->created_at))}}</td>
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