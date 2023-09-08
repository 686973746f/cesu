@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="" method="GET">
            <div class="card">
                <div class="card-header"><b>Issuance of Meds to Patient</b></div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
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
                    <hr>
                    <div class="form-group">
                        <label for="">Input or Scan QR of Meds to Issue</label>
                        <input type="text" class="form-control" name="meds" id="meds" required autocomplete="off" autofocus>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection