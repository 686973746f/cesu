@extends('layouts.app')

@section('content')
<form action="{{route('adminpanel.account.update', ['id' => $data->id])}}" method="POST">
    <div class="card">
        <div class="card-header">Edit User Information</div>
        <div class="card-body">
            
        </div>
    </div>
</form>
@endsection