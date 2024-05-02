@extends('layouts.app')

@section('content')
<style>
    #map { height: 1000px; }
</style>
    <div class="container">
        <div class="card">
            <div class="card-header">Case Map Viewer</div>
            <div class="card-body">
                <div class="row">
                    <div class="row-6">

                    </div>
                    <div class="row-6">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        L.Icon.Default.imagePath="{{asset('assets')}}/"
    
        var map = L.map('map').setView([14.321659, 120.907585], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
        minZoom: 12,
        maxZoom: 13,
        }).addTo(map);
    </script>
@endsection