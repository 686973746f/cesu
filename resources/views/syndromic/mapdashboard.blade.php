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
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Barangay</th>
                                <th>Current Day</th>
                                <th>Current Month</th>
                                <th>Current Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brgy as $b)
                            <tr>
                                <td>{{$b->brgyName}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var map = L.map('map').setView([14.321659, 120.907585], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    minZoom: 12,
    maxZoom: 13,
    }).addTo(map);

    var geojsonFeature = "{{asset('json/gentrigeo.json')}}";

    fetch(geojsonFeature)
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {
      // Create a Leaflet GeoJSON layer and add it to the map
      L.geoJSON(data, {
        onEachFeature: function(feature, layer) {
          // Access the name property of each feature and bind it as a tooltip
          var name = feature.properties.ADM4_EN;
          layer.bindTooltip(name);
        }
      }).addTo(map);
    });

</script>
@endsection