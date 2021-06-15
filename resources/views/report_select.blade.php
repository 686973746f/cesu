@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Reports</div>
        <div class="card-body">
            <a href="{{route('report.daily')}}" class="btn btn-primary btn-block">Daily Report</a>
            <a href="" class="btn btn-primary btn-block">Barangay Report</a>
            <a href="" class="btn btn-primary btn-block">Company Report</a>

            <!-- Chart's container -->
            <div id="chart" style="height: 500px;"></div>
            <!-- Charting library -->
            
            <!-- Your application script -->
            <script>
            const chart = new Chartisan({
                el: '#chart',
                url: "@chart('sample_chart')",
                hooks: new ChartisanHooks()
                .colors()
                .responsive()
                .beginAtZero()
            });
            </script>
        </div>
    </div>
</div>
@endsection