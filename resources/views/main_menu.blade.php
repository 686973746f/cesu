@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Main Menu</b></div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
                {{session('msg')}}
            </div>
            @endif
            @if(auth()->user()->canAccessCovid())
            <a href="{{route('covid_home')}}" class="btn btn-block btn-primary btn-lg">COVID-19</a>
            @endif
            @if(auth()->user()->canAccessAbtc())
            <a href="{{route('abtc_home')}}" class="btn btn-block btn-primary btn-lg">Rabies Control Program / ABTC</a>
            @endif
            @if(auth()->user()->canAccessVaxcert())
            <a href="{{route('vaxcert_home')}}" class="btn btn-block btn-primary btn-lg">VaxCert Concerns</a>
            @endif
            @if(auth()->user()->canAccessSyndromic())
            <a href="{{(auth()->user()->isStaffSyndromic()) ? route('syndromic_home', ['opd_view' => 1]) : route('syndromic_home')}}" class="btn btn-block btn-primary btn-lg">Community Base Disease Surveillance System (CBDSS) / Individual Treatment Records (ITR)</a>
            @endif
            @if(auth()->user()->canAccessPidsr())
            <a href="{{route('pidsr.home')}}" class="btn btn-block btn-primary btn-lg">Integrated Philippine Integrated Disease Surveillance and Response (PIDSR)</a>
            @endif
            @if(auth()->user()->canAccessFhsis())
            <a href="{{route('fhsis_home')}}" class="btn btn-block btn-primary btn-lg">Integrated Electronic Field Health Service Information System (eFHSIS)</a>
            @endif
            @if(auth()->user()->canAccessPharmacy())
            <a href="{{route('pharmacy_home')}}" class="btn btn-block btn-primary btn-lg">Pharmacy Inventory System</a>
            @endif
            @if(auth()->user()->canAccessPharmacy())
            <a href="{{route('fwri_home')}}" class="btn btn-block btn-primary btn-lg">Fireworks-Related Injury (FWRI)</a>
            @endif
            @if(auth()->user()->isGlobalAdmin())
            <hr>
            <a href="" class="btn btn-block btn-warning btn-lg">Settings</a>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="privacymodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Privacy Notice</h5>
            </div>
            <div class="modal-body">
                <p>Your role as system users in the CESU General Trias Web System is vital in ensuring the security and privacy of personal data. We want to remind you that our data processing practices are strictly aligned with <b>Republic Act No. 10173</b>, also known as the <a href="https://privacy.gov.ph/data-privacy-act/"><b>Data Privacy Act of 2012</b></a>, and <b>Republic Act No. 11332</b>, also known as the <a href="https://lawphil.net/statutes/repacts/ra2019/ra_11332_2019.html"><b>Mandatory Reporting of Notifiable Diseases and Health Events of Public Health Concern Act</b></a></p>
                <p>As system users, it is crucial to be aware of your responsibilities in handling personal information. This law sets out specific guidelines for the collection, processing, and protection of data. It emphasizes the importance of consent, accuracy, confidentiality, and security in your data-related tasks.</p>
                <p>Remember that data subjects have rights under this law, including the right to access their data, the right to have data corrected, and the right to object to data processing. You play a significant role in upholding these rights.</p>
                <p>Your commitment to data privacy is crucial to maintaining the trust and confidence of our clients. We appreciate your dedication to safeguarding personal data and ensuring compliance with the law.</p>
                <p>Thank you for your cooperation and diligence in preserving the privacy and security of the data we handle.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>

<!--
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
-->

<script>
    $('#privacymodal').modal({backdrop: 'static', keyboard: false});
    $('#privacymodal').modal('show');
</script>
@endsection