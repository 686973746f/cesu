@extends('layouts.app')

@section('content')
<table class="table table-bordered table-striped">
    <thead class="thead-light">
        <tr>
            <th rowspan="2" class="text-center">Barangay ({{date('Y')}})</th>
            @for($i=1;$i<=$month;$i++)
            @php
            if($i == 1) {
                $mstring = 'January';
            }
            else if($i == 2) {
                $mstring = 'February';
            }
            else if($i == 3) {
                $mstring = 'March';
            }
            else if($i == 4) {
                $mstring = 'April';
            }
            else if($i == 5) {
                $mstring = 'May';
            }
            else if($i == 6) {
                $mstring = 'June';
            }
            else if($i == 7) {
                $mstring = 'July';
            }
            else if($i == 8) {
                $mstring = 'August';
            }
            else if($i == 9) {
                $mstring = 'September';
            }
            else if($i == 10) {
                $mstring = 'October';
            }
            else if($i == 11) {
                $mstring = 'November';
            }
            else if($i == 12) {
                $mstring = 'December';
            }
            @endphp
            <th colspan="2" class="text-center">{{$mstring}}</th>
            @endfor
        </tr>
        <tr>
            @for($i=1;$i<=$month;$i++)
            <th class="text-center">M1</th>
            <th class="text-center">M2</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($blist as $key => $b)
        <tr>
            <td>{{$b->BGY_DESC}}</td>
            @for($i=0;$i<$month;$i++)
            <td class="text-center"></td>
            <td class="text-center">{{$m2l[$key][$i]}}</td>
            @endfor
        </tr>
        @endforeach
    </tbody>
</table>
@endsection