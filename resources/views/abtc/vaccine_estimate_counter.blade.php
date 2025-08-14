@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Card</b></div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Day 0</th>
                        <th>Day 3</th>
                        <th>Day 7</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $l)
                    <tr class="text-center">
                        <td>
                            <div>{{$l['date']}}</div>
                            <div>{{$l['d0_vials']}}</div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
    
@endsection