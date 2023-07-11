@extends('layouts.app')

@section('content')

<style>
    #map { height: 1000px; }
</style>



<div class="container-fluid">
    <div class="card">
        <div class="card-header">Map</div>
        <div class="card-body">
            
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var map = L.map('map').setView([14.3895, 120.8777], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    
    }).addTo(map);

</script>
@endsection