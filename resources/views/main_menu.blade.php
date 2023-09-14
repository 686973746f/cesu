@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Main Menu</b></div>
        <div class="card-body">
            @if(auth()->user()->canAccessCovid())
            <a href="{{route('covid_home')}}" class="btn btn-block btn-primary">COVID-19</a>
            @endif
            @if(auth()->user()->canAccessAbtc())
            <a href="{{route('abtc_home')}}" class="btn btn-block btn-primary">Animal Bite (ABTC)</a>
            @endif
            @if(auth()->user()->canAccessVaxcert())
            <a href="{{route('vaxcert_home')}}" class="btn btn-block btn-primary">VaxCert Concerns</a>
            @endif
            @if(auth()->user()->canAccessSyndromic())
            <a href="{{route('syndromic_home')}}" class="btn btn-block btn-primary">Community Base Disease Surveillance System (CBDSS) / Individual Treatment Records (ITR)</a>
            <hr>
            @endif
            @if(auth()->user()->canAccessPidsr())
            <a href="{{route('pidsr.home')}}" class="btn btn-block btn-primary">PIDSR (Integrated)</a>
            @endif
            @if(auth()->user()->canAccessFhsis())
            <a href="{{route('fhsis_home')}}" class="btn btn-block btn-primary">eFHSIS (Integrated)</a>
            @endif
            @if(auth()->user()->canAccessPharmacy())
            <hr>
            <a href="{{route('pharmacy_home')}}" class="btn btn-block btn-primary">Pharmacy Inventory System</a>
            @endif
        </div>
    </div>
</div>

<div class="container">
    <canvas id="myChart"></canvas>
</div>

<script>
    // Fetch your data here and format it as needed for the chart
    var data = {
        labels: ["Label 1", "Label 2", "Label 3"],
        datasets: [{
            label: "Chart Title",
            data: [10, 20, 30], // Replace with your data
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    };

    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                datalabels: { // Enable the datalabels plugin
                    color: 'black', // Label text color
                    anchor: 'end', // Label text anchor position (e.g., 'end', 'start', 'center')
                    align: 'top', // Label text alignment (e.g., 'top', 'bottom', 'center')
                    font: {
                        weight: 'bold' // Label text font weight
                    },
                    formatter: function(value) {
                        // Customize label format as needed
                        return value;
                    }
                }
            }
        }
    });
</script>

@endsection