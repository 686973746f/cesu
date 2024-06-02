@extends('layouts.app')

@section('content')
    
    <div class="container">
        <nav class="navbar navbar-light bg-light">
            <div><h5><b>Task Generator</b></h5></div>
            <div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTask">
                Create New Task
              </button>
            </div>
        </nav>
        <div class="card">
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                    {{session('msg')}}
                </div>
                @endif
            </div>
        </div>
    </div>

    <form action="{{route('taskgenerator.store')}}" method="POST">
        @csrf
        <div class="modal fade" id="createTask" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Task</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection