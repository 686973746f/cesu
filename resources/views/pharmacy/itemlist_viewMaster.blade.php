@extends('layouts.app')

@section('content')
<div class="container">
    <form action="">
        @csrf
        <div class="card">
            <div class="card-header"><b>Manage Master Item</b></div>
            <div class="card-body">
                <div class="form-group">
                  <label for="name"><b class="text-danger">*</b>Name</label>
                  <input type="text" class="form-control" name="name" id="name" value="{{old('name', $d->name)}}" required>
                </div>
                <div class="row">
                    
                </div>
            </div>
        </div>
    </form>
</div>
@endsection