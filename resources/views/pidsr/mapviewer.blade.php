@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('assets/beautifymarker/leaflet-beautify-marker-icon.css')}}">
<script src="{{asset('assets/beautifymarker/leaflet-beautify-marker-icon.js')}}"></script>
<style>
    #map { height: 700px; }
</style>
    <div class="container">
        <div class="card">
            <div class="card-header"><b>Case Map Viewer</b> (Case: {{$case}} - Year: {{request()->input('year')}})</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Age/Sex</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($list_case as $ind => $d)
                                <tr>
                                    <td class="text-center">{{$ind+1}}</td>
                                    <td>{{$d->getName()}}</td>
                                    <td class="text-center">{{$d->displayAgeStringToReport()}}/{{$d->Sex}}</td>
                                    <td class="text-center">
                                        <button class="btn btn-link" onclick="flyToMap({{$d->sys_coordinate_x}}, {{$d->sys_coordinate_y}})">
                                            <div>test</div>
                                        </button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function getColor(brgy, disease, year) {
            $.ajax({
                url: "{{route('pidsr_case_mapviewerGetColor')}}",
                method: 'GET',
                data: {
                    brgy: brgy,
                    disease: disease,
                    year: year,
                },
                success: function(response) {
                    callback(response.color);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        L.Icon.Default.imagePath="{{asset('assets')}}/"
    
        var map = L.map('map').setView([14.321659, 120.905], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            minZoom: 12,
            maxZoom: 21,
        }).addTo(map);

        options = {
            icon: 'user',
            iconShape: 'marker',
            borderColor: 'red',
            backgroundColor: 'red',
            textColor: 'white',
        };

        @foreach($list_case as $ind => $lc)
        @if(!is_null($lc->sys_coordinate_x))
        L.marker([{{$lc->sys_coordinate_x}}, {{$lc->sys_coordinate_y}}], {
            icon: L.BeautifyIcon.icon(options),
            draggable: false,
        }).addTo(map).bindPopup("popup").bindPopup("<a href='{{route('pidsr_casechecker_edit', [$case, $lc->EPIID])}}'><b>{{$lc->getName()}}</b></a><br>{{$lc->displayAgeStringToReport()}}/{{$lc->Sex}}<br>{{$lc->getStreetPurok()}}<br>BRGY. {{$lc->Barangay}}");
        @endif
        @endforeach

        var geojsonFeature = "{{asset('json/gentrigeo.json')}}";

        fetch(geojsonFeature)
        .then(function(response) {
        return response.json();
        })
        .then(function(data,) {
            // Create a Leaflet GeoJSON layer and add it to the map
            L.geoJSON(data, {
                style: function(feature) {
                    var sname = feature.properties.ADM4_EN.toUpperCase();
                    return {
                        fillColor: getColor(sname, 'Pert', 2024),
                        weight: 1,
                        opacity: 1,
                        color: 'black',
                        fillOpacity: 0.5,
                    };
                },
                onEachFeature: function(feature, layer) {
                    // Access the name property of each feature and bind it as a tooltip
                    var name = feature.properties.ADM4_EN;
                    layer.bindTooltip(name);
                }
            }).addTo(map);
        });

        function flyToMap(x, y) {
            map.flyTo([x,y], 18);
        }
    </script>
@endsection