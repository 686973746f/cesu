@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Linelist V2</b></div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <form action="{{route('llv2.add', $d->id)}}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Scan QR of Patient CIF here" name="qr" id="qr" required autofocus>
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
                            <td>{{$list->records->getName()}}</td>
                            <td class="text-center">{{date('m/d/Y h:i A', strtotime($list->dateAndTimeCollected))}}</td>
                            <th>
                                <form action="{{route('llv2.process', ['masterid' => $d->id, 'subid' => $list->id])}}" method="POST">
                                    <button type="button" class="btn btn-primary"></button>
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