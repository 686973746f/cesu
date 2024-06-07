@extends('layouts.app')

@section('content')
    
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <h5><b>View Work Ticket #{{$d->id}}</b></h5>
        </nav>
        

        <div class="card">
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <div class="row">
                    <div class="col-6">
                        <label for=""><b>Task Name</b></label>
                        <p>{{$d->name}}</p>
                    </div>
                    <div class="col-6">
                        <label for=""><b>Date Created</b></label>
                        <p>{{date('m/d/Y h:i A', strtotime($d->created_at))}}</p>
                    </div>
                </div>
                <hr>
                @if($d->encodedcount_enable == 'Y')
                <div class="form-group">
                    <label for="encodedcount">Paki-lagay kung ilan ang na-encode mo</label>
                    <input type="number" class="form-control" name="encodedcount" id="encodedcount" min="1" max="150" required>
                  </div>
                @endif
                <div class="form-group">
                  <label for="remarks">Remarks (Optional)</label>
                  <input type="text" class="form-control" name="remarks" id="remarks">
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-6">
                        @if($d->status == 'PENDING')
                        <form action="{{route('task_cancel', ['id' => $d->id, 'type' => 'work'])}}" method="POST">
                            @csrf
                            <button type="submit" class="btn text-white" style="background-color: orange" onclick="return confirm('Are you sure you want to cancel?')">Cancel</button>
                        </form>
                        @endif
                    </div>
                    <div class="col-6 text-right">
                        <form action="{{route('worktask_closeticket', $d->id)}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Confirm closing the OPD Ticket #{{$d->id}}. Pakisigurado po muna na tapos na ang Work Ticket bago i-close sa system.')">Mark as Done</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection