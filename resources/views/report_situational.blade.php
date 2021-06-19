@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">COVID-19 Situational Report</div>
            <div class="card-body">
                <div id="chart" style="height: 500px;"></div>
                <hr>
            </div>
        </div>
    </div>
    
    <script>
        const chart = new Chartisan({
            el: '#chart',
            url: "@chart('situational_daily_confirmed_active_chart')",
            error: {
                color: '#ff00ff',
                size: [30, 30],
                text: 'Yarr! There was an error...',
                textColor: '#ffff00',
                type: 'general',
                debug: true,
            },
            hooks: new ChartisanHooks()
            .colors()
            .responsive()
            .beginAtZero()
        });
        </script>
@endsection