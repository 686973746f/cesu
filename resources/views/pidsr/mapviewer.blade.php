@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('assets/beautifymarker/leaflet-beautify-marker-icon.css')}}">
<script src="{{asset('assets/beautifymarker/leaflet-beautify-marker-icon.js')}}"></script>
<style>
    #map { height: 700px; }
</style>
<style>
    #loading {
        position: fixed;
        display: block;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        text-align: center;
        background-color: #fff;
        z-index: 99;
    }
</style>
<div id="loading">
    <div class="text-center">
        <i class="fas fa-circle-notch fa-spin fa-5x my-3"></i>
        <h3>Loading...</h3>
    </div>
</div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Case Map Viewer</b> (Case: {{$case}} - Year: {{request()->input('year')}})</div>
                    <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#filterMod">Filter</button></div>
                </div>
            </div>
            <div class="card-body">
                <div id="map"></div>
                <hr>
                <div class="alert alert-info" role="alert">
                    @if($filter_string)
                    <div>{{$filter_string}}</div>
                    @endif
                    <div>Total results found: <b>{{$list_case->count()}}</b></div>
                    <div>Male: {{$list_case->where('Sex', 'M')->count()}} - Female: {{$list_case->where('Sex', 'F')->count()}}</div>
                    <div>With Geo-tag: {{$list_case->whereNotNull('sys_coordinate_x')->count()}} - Without Geo-tag: {{$list_case->whereNull('sys_coordinate_x')->count()}}</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="mainTbl">
                        <thead class="thead-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Age/Sex</th>
                                <th>Address</th>
                                <th>Outcome</th>
                                <th>Morbidity Week</th>
                                <th>DRU</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list_case as $ind => $d)
                            <tr>
                                <td class="text-center">{{$ind+1}}</td>
                                <td>{{$d->getName()}}</td>
                                <td class="text-center">{{$d->displayAgeStringToReport()}}/{{$d->Sex}}</td>
                                <td class="text-center">
                                @if(!is_null($d->sys_coordinate_x))
                                <button class="btn btn-link" onclick="flyToMap({{$d->sys_coordinate_x}}, {{$d->sys_coordinate_y}})">
                                    <div>{{$d->getStreetPurok()}}</div>
                                    <div>{{$d->Barangay}}</div>
                                </button>
                                @else
                                <div>{{$d->getStreetPurok()}}</div>
                                <div>{{$d->Barangay}}</div>
                                @endif
                                </td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center">{{date('m/d/Y h:i A', strtotime($d->created_at))}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <form action="" method="GET">
        <div class="modal fade" id="filterMod" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Filter Cases</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Year</label>
                            <input type="number" class="form-control" name="year" id="year" min="2010" max="{{date('Y')}}" value="{{date('Y')}}" required>
                        </div>
                        <div class="form-group">
                          <label for="type">Filter By</label>
                          <select class="form-control" name="type" id="type" required>
                            <option value="mw">Morbidity Week</option>
                            <option value="date">Date</option>
                          </select>
                        </div>
                        <div id="ifDateDiv" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="startDate"><b class="text-danger">*</b>Start Date</label>
                                      <input type="date" class="form-control" name="startDate" id="startDate" min="2010-01-01" max="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="endDate"><b class="text-danger">*</b>End Date</label>
                                        <input type="date" class="form-control" name="endDate" id="endDate" min="2010-01-01" max="{{date('Y-m-d')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="ifMwDiv" class="d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                      <label for="mwStart"><b class="text-danger">*</b>MW Start</label>
                                      <input type="number" class="form-control" name="mwStart" id="mwStart" min="1" max="52">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mwEnd"><b class="text-danger">*</b>MW End</label>
                                        <input type="number" class="form-control" name="mwEnd" id="mwEnd" min="1" max="52">
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <script>
        $(document).ready(function () {
            $('#loading').fadeOut();
        });

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

        $('#type').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'mw') {
                $('#ifDateDiv').addClass('d-none');
                $('#ifMwDiv').removeClass('d-none');

                $('#startDate').prop('required', false);
                $('#endDate').prop('required', false);
                $('#startDate').prop('disabled', true);
                $('#endDate').prop('disabled', true);
                
                $('#mwStart').prop('required', true);
                $('#mwEnd').prop('required', true);
                $('#mwStart').prop('disabled', false);
                $('#mwEnd').prop('disabled', false);
                
            }
            else {
                $('#ifDateDiv').removeClass('d-none');
                $('#ifMwDiv').addClass('d-none');

                $('#startDate').prop('required', true);
                $('#endDate').prop('required', true);
                $('#startDate').prop('disabled', false);
                $('#endDate').prop('disabled', false);
                
                $('#mwStart').prop('required', false);
                $('#mwEnd').prop('required', false);
                $('#mwStart').prop('disabled', true);
                $('#mwEnd').prop('disabled', true);
            }
        }).trigger('change');

        $('#mwStart').on('input', function() {
            var mwStartValue = $(this).val();
            $('#mwEnd').attr('min', mwStartValue);
        });

        $('#mainTbl').dataTable();

        L.Icon.Default.imagePath="{{asset('assets')}}/"
    
        var map = L.map('map').setView([14.321659, 120.905], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
            minZoom: 12,
            maxZoom: 21,
        }).addTo(map);

        options_red = {
            icon: 'user',
            iconShape: 'marker',
            borderColor: 'red',
            backgroundColor: 'red',
            textColor: 'white',
        };

        options_blue = {
            icon: 'user',
            iconShape: 'marker',
            borderColor: 'red',
            backgroundColor: 'red',
            textColor: 'white',
        };

        options_green = {
            icon: 'user',
            iconShape: 'marker',
            borderColor: 'green',
            backgroundColor: 'green',
            textColor: 'white',
        };

        options_black = {
            icon: 'user',
            iconShape: 'marker',
            borderColor: 'black',
            backgroundColor: 'black',
            textColor: 'white',
        };

        var currentWeek = {{date('W')}};

        @foreach($list_case as $ind => $lc)
        @if(!is_null($lc->sys_coordinate_x))
        @if($case == 'DENGUE')
            @if($lc->MorbidityWeek <= (date('W') - 3))
            var markerOptions = options_green;
            @elseif($lc->Outcome == 'D')
            var markerOptions = options_black;
            @else
            var markerOptions = options_red;
            @endif
        @else
        var markerOptions = options_red;
        @endif
        
        L.marker([{{$lc->sys_coordinate_x}}, {{$lc->sys_coordinate_y}}], {
            icon: L.BeautifyIcon.icon(markerOptions),
            draggable: false,
        }).addTo(map).bindPopup("popup").bindPopup("<a href='{{route('pidsr_casechecker_edit', [$case, $lc->EPIID])}}'><b>{{$lc->getName()}}</b></a><br>{{$lc->displayAgeStringToReport()}}/{{$lc->Sex}}<br>{{$lc->getStreetPurok()}}<br>BRGY. {{$lc->Barangay}}<br>Date of Entry: {{date('M. d, Y', strtotime($lc->DateOfEntry))}}<br>MW: {{$lc->MorbidityWeek}}<br>Geotag: {{$lc->sys_coordinate_x}}, {{$lc->sys_coordinate_y}}<br><br>Google Maps:<br><a href='https://www.google.com/maps?q={{ $lc->sys_coordinate_x }},{{$lc->sys_coordinate_y }}'>https://www.google.com/maps?q={{ $lc->sys_coordinate_x }},{{$lc->sys_coordinate_y }}</a>");

        @if($case == 'DENGUE')
        // Add a 300-meter circle around the marker
        @if($lc->ifInsideClusteringDistance())
        var bilogColor = 'red';
        @else
        var bilogColor = 'blue';
        @endif

        var circle = L.circle([{{$lc->sys_coordinate_x}}, {{$lc->sys_coordinate_y}}], {
            color: bilogColor,        // Circle border color
            //fillColor: '#f03',    // Circle fill color
            weight: 0.8,
            fillOpacity: 0,     // Fill opacity
            radius: 300           // Radius in meters (300 meters)
        }).addTo(map);
        @else($case == 'RABIES')
        var circle = L.circle([{{$lc->sys_coordinate_x}}, {{$lc->sys_coordinate_y}}], {
            color: 'blue',
            fillOpacity: 0,
            radius: 3000,
        }).addTo(map);
        @endif
        
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
                        fillOpacity: 0,
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