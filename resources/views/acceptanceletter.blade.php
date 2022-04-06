@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><strong>Acceptance Letter</strong></div>
                <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createal">Add</button></div>
            </div>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <p>{{Str::plural('Error', $errors->count())}} while creating Acceptance Letter:</p>
                <hr>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </div>
            @endif
            @if(session('msg'))
            <div class="alert alert-{{session('msgType')}}" role="alert">
                {{session('msg')}}
            </div>
            @endif
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date Processed / By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $item)
                    <tr>
                        <td scope="row"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form action="{{route('acceptance.store')}}" method="POST">
        @csrf
        <div class="modal fade" id="createal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Acceptance Letter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text"class="form-control" name="lname" id="lname" value="{{old('lname')}}" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text"class="form-control" name="fname" id="fname" value="{{old('fname')}}" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="mname">Middle Name</label>
                            <input type="text"class="form-control" name="mname" id="mname" value="{{old('mname')}}" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label for="suffix">Suffix <small><i>(e.g Jr, Sr, III, IV)</i></small></label>
                            <input type="text"class="form-control" name="suffix" id="suffix" value="{{old('suffix')}}" maxlength="50">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="travelto">Will Travel To</label>
                            <input type="text"class="form-control" name="travelto" id="travelto" value="{{old('travelto')}}" maxlength="50">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection