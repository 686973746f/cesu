@extends('layouts.app')

@section('content')

<style>
    #map { height: 800px; }
</style>



<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>Map</b></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>Barangay</th>
                                <th>Current Day</th>
                                <th>Current Month</th>
                                <th>Current Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $l)
                            <tr>
                                <td>{{$l['brgy']}}</td>
                                <td class="text-center">@if($l['case_now'] != 0)<a href="{{route('syndromic_disease_list')}}?brgy_id={{$l['brgy_id']}}&type=daily">{{$l['case_now']}}</a>@else 0 @endif</td>
                                <td class="text-center">@if($l['case_month'] != 0)<a href="{{route('syndromic_disease_list')}}?brgy_id={{$l['brgy_id']}}&type=monthly">{{$l['case_month']}}</a>@else 0 @endif</td>
                                <td class="text-center">@if($l['case_year'] != 0)<a href="{{route('syndromic_disease_list')}}?brgy_id={{$l['brgy_id']}}&type=yearly">{{$l['case_year']}}</a>@else 0 @endif</td>
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
        style: function(feature) {
            return {
                fillColor: null,
                weight: 1,
                opacity: 1,
                color: 'black',
                fillOpacity: 0.0
            };
        },
        onEachFeature: function(feature, layer) {
          // Access the name property of each feature and bind it as a tooltip
          var name = feature.properties.ADM4_EN;
          layer.bindTooltip(name);
        }
      }).addTo(map);
    });
</script>
@endsection