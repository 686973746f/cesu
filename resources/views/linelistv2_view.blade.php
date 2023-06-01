@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Linelist V2 - {{$d->getType()}}</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <form action="{{route('llv2.add', $d->id)}}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Scan QR/Type ID of Patient CIF here" name="qr" id="qr" autocomplete="off" required autofocus>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success" type="submit" name="submit" value="add">Add</button>
                        </div>
                    </div>
                </form>
                <hr>
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Name</th>
                            <th>Swab Collection Date & Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($e as $ind => $list)
                        <tr>
                            <td class="text-center"><b>{{($ind + 1)}}</b></td>
                            <td>{{$list->records->getName()}} #{{$list->records_id}}</td>
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($list->dateAndTimeCollected))}}</td>
                            <th class="text-center">
                                <form action="{{route('llv2.process', ['masterid' => $d->id, 'subid' => $list->id])}}" method="POST">
                                    @csrf
                                    @if($ind != 0)
                                    <button type="submit" name="submit" class="btn btn-primary" value="moveup"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
                                    @else
                                    <button type="button" class="btn btn-primary disabled"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
                                    @endif
                                    @if(!($loop->last))
                                    <button type="submit" name="submit" class="btn btn-primary" value="movedown"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                                    @else
                                    <button type="button" name="submit" class="btn btn-primary disabled"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                                    @endif
                                    <button type="submit" name="submit" class="btn btn-danger" value="delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                </form>
                            </th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($e->count() != 0)
            <form action="{{route('llv2.close', $d->id)}}" method="POST">
                @csrf
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success">Finish (Close the Linelist)</button>
                </div>
            </form>
            @endif
        </div>
    </div>
@endsection