@extends('layouts.app')

@section('content')
    <form action="{{ route('injury_add_store', $f->sys_code1) }}" method="POST">
        @csrf
        <div class="container">
            <div class="card">
                <div class="card-header"><b>New Injury</b></div>
                <div class="card-body">

                </div>
            </div>
        </div>
    </form>
@endsection