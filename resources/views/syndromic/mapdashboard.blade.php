@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">Map</div>
        <div class="card-body">
            <div id="map" style="height: 400px;"></div>
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var map = L.map('map').setView([14.3895, 120.8777], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add markers, polygons, etc. as needed

</script>
@endsection