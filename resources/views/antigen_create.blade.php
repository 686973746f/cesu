@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('antigen_store')}}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">Add</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="antigenKitName">Antigen Kit Name</label>
                                <input type="text" class="form-control" name="antigenKitName" id="antigenKitName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="antigenKitShortName">Antigen Kit Short Name</label>
                                <input type="text" class="form-control" name="antigenKitShortName" id="antigenKitShortName" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
@endsection