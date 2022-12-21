@extends('layouts.app')

@section('content')
@if($c->exists)
<!--Edit Page-->
<form action="{{route('mp.updatecif', ['cif_id' => $c->id])}}" method="POST">
@else
<!--Create Page-->
<form action="{{route('mp.storecif', ['record_id' => $d->id])}}" method="POST">
@endif
    <div class="card">
        <div class="card-header">Dengue CIF</div>
        <div class="card-body">
            <div class="row">
                <div class="">
                    <div class="form-group">
                        <label for="DateOfEntry"><span class="text-danger font-weight-bold">*</span>Date of Entry</label>
                        <input type="date"class="form-control" name="DateOfEntry" id="DateOfEntry" value="{{old('DateOfEntry', $request)}}" max="{{date('Y-m-d')}}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary btn-block" id="submitBtn">{{($c->exists) ? 'Update' : 'Save'}} (CTRL + S)</button>
        </div>
    </div>
</form>
@endsection