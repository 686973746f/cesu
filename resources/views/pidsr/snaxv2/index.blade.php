@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-3">
        <div class="card-header"><b>Page 1</b></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">

                </div>
                <div class="col-md-4">
                    <h6>Republic of the Philippines</h6>
                    <h6><b>GENERAL TRIAS CITY HEALTH OFFICE</b></h6>
                    <h6><b>CITY EPIDEMIOLOGY AND SURVEILLANCE UNIT (CESU)</b></h6>
                    <h6>Hospital Rd., Brgy. Pinagtipunan, City of General Trias, Cavite</h6>
                    <h6>Telephone No.: (046) 509-5289 / (046) 437-9195</h6>
                    <h6>Email: <a href="">cesu.gentrias@gmail.com</a></h6>
                </div>
                <div class="col-md-4">
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr style="background-color: rgba(255, 0, 0, 0.27)">
                                <td>Surveillance Monitoring Dashboard</td>
                            </tr>
                            <tr>
                                <td><h3><b>{{$flavor_title}}</b></h3></td>
                            </tr>
                            <tr>
                                <td style="background-color: rgba(200, 0, 0, 0.74)" class="text-white"><b>MW {{$sel_mweek}} ()</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr>
            <ul>
                <h6><b>Summary:</b></h6>
                <li>There were </li>
                <li>This year's </li>
                <li>Of the total cases</li>
                <li>The Barangay</li>
                <li>Age ranged</li>
            </ul>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h6><b>Distribution of {{$sel_disease}} Cases by Morbidity Week</b></h6>
                    <h6>GENERAL TRIAS, MW{{$sel_mweek}}, {{$sel_year}}</h6>
                    <h6>N = {{$current_grand_total}}</h6>
                    <canvas id="myChart" width="400" height="400"></canvas>
                </div>
                <div class="col-md-6">
                    <div class="card" style="background-color: rgba(255, 0, 0, 0.27)">
                        <div class="card-body">
                            <table class="table">
                                <thead class="text-center">
                                    <tr>
                                        <th>Cases & Deaths</th>
                                        <th>Year</th>
                                        <th>All Cases</th>
                                        <th>Deaths</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td rowspan="2"><h6>Morbidity Week</h6>
                                            <h6>From <b>1</b> to <b class="text-danger">{{$sel_mweek}}</b></h6>
                                        </td>
                                        <td class="text-muted text-center">{{$sel_year-1}}</td>
                                        <td class="text-muted text-center"></td>
                                        <td class="text-muted text-center"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"><b>{{$sel_year}}</b></td>
                                        <td class="text-center"><b>{{$current_grand_total}}</b></td>
                                        <td class="text-center"><b></b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="text-center" style="background-color: red">Classification of Cases</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Confirmed</td>
                                        <td>{{$current_confirmed_grand_total}}</td>
                                        <td>{{($current_grand_total != 0) ? ($current_confirmed_grand_total / $current_grand_total) : 0}}%</td>
                                    </tr>
                                    <tr>
                                        <td>Probable</td>
                                        <td>{{$current_probable_grand_total}}</td>
                                        <td>{{($current_grand_total != 0) ? ($current_probable_grand_total / $current_grand_total) : 0}}%</td>
                                    </tr>
                                    <tr>
                                        <td>Suspect</td>
                                        <td>{{$current_suspected_grand_total}}</td>
                                        <td>{{($current_grand_total != 0) ? ($current_suspected_grand_total / $current_grand_total) : 0}}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><b>Page 2/3</b></div>
        <div class="card-body">

        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header"><b>Page 3/3</b></div>
        <div class="card-body">

        </div>
    </div>
</div>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var barData = {!! json_encode($currentmw_array) !!};
    var lineData = {!! json_encode($epidemicmw_array) !!};
    var dottedLineData = {!! json_encode($alertmw_array) !!};

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['MW1', 'MW2', 'MW3', 'MW4', 'MW5', 'MW6', 'MW7', 'MW8', 'MW9', 'MW10', 'MW11', 'MW12', 'MW13', 'MW14', 'MW15', 'MW16', 'MW17', 'MW18', 'MW19', 'MW20', 'MW21', 'MW22', 'MW23', 'MW24', 'MW25', 'MW26', 'MW27', 'MW28', 'MW29', 'MW30', 'MW31', 'MW32', 'MW33', 'MW34', 'MW35', 'MW36', 'MW37', 'MW38', 'MW39', 'MW40', 'MW41', 'MW42', 'MW43', 'MW44', 'MW45', 'MW46', 'MW47', 'MW48', 'MW49', 'MW50', 'MW51', 'MW52'], // Replace with your actual labels
            datasets: [{
                label: 'Current Year - {{$sel_year}}',
                data: barData,
                backgroundColor: 'rgba(255, 236, 0, 1)', // Customize bar color
                borderColor: 'rgba(0, 0, 0, 1)', // Customize border color
                borderWidth: 1
            },
            {
                label: 'Epidemic Threshold',
                data: lineData,
                fill: false,
                borderColor: 'rgba(255, 0, 0, 1)', // Customize line color
                borderWidth: 2,
                type: 'line',
                lineTension: 0 // Remove line tension for straight lines
            },
            {
                label: 'Alert Threshold',
                data: dottedLineData,
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)', // Customize dotted line color
                borderWidth: 1,
                type: 'line',
                borderDash: [5, 5] // Make the line dotted
            }]
        },
        options: {
            plugins: {
                datalabels: {
                display: false
                }
            },
        }
    });
</script>
@endsection

<!--

    <!DOCTYPE html>
<html>
<head>
    <title>Chart.js Example</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="myChart" width="400" height="400"></canvas>

    <script>
        
        
        

        
    </script>
</body>
</html>

!-->