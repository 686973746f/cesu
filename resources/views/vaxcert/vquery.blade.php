@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Vaccinee Query</b></div>
            <div class="card-body">
                @if($d->count() != 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Category</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($d as $a)
                            <td>{{$a->category}}</td>
                            <td></td>
                            <td></td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                @endif
            </div>
        </div>
    </div>
@endsection