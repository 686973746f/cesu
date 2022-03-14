@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Accomplishment Report (2021)</div>
    <div class="card-body">
        <p>2021 Total Confirmed Swabbed by CHO: {{number_format($count1)}}</p>
        <p>2021 Confirmed Average: {{number_format($count2)}}</p>
        <p>2021 Number of Recoveries: {{number_format($count3)}}</p>
        <p>2021 Number of Deaths: {{number_format($count4)}}</p>
    </div>
</div>
@endsection