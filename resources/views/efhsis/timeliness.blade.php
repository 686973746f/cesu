@extends('layouts.app')

@section('content')
<table class="table">
    <thead>
        <tr>
            <th>Barangay</th>
            @for($i=1;$i<=$month;$i++)
            <th colspan="2">{{$i}}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        <tr>
            <td scope="row"></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td scope="row"></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
@endsection