@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header font-weight-bold text-center">Reports</div>
        <div class="card-body">
            @if(auth()->user()->isBrgyAccount())
            <div class="text-center">
                <h3 class="font-weight-bold text-primary">BRGY. {{auth()->user()->brgy->brgyName}}</h3>
            </div>
            <div class="alert alert-info text-center" role="alert">
                <strong>Note:</strong> Counting results from BRGY. {{auth()->user()->brgy->brgyName}} Data ONLY.
            </div>
            @endif
            @if(auth()->user()->ifTopAdmin())
            <div id="accordianId1" role="tablist" aria-multiselectable="true">
                <div class="card">
                    <div class="card-header text-center" role="tab" id="section1HeaderId1">
                        <a data-toggle="collapse" data-parent="#accordianId1" href="#dateFilter" aria-expanded="true" aria-controls="dateFilter"><i class="fas fa-calendar-alt mr-2"></i>Filter by Date</a>
                    </div>
                    <div id="dateFilter" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId1">
                        <form action="{{route('report.index')}}" method="GET">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="filter_type">Filter Type</label>
                                    <select class="form-control" name="filter_type" id="filter_type" required>
                                      <option value="1">From Start Date to End Date (Specify Two Dates)</option>
                                      <option value="2">From Start of COVID to Specific Date (Specify End Date Only)</option>
                                    </select>
                                </div>
                                <div id="fType1" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="start_date">From Start Date</label>
                                              <input type="date" class="form-control" name="start_date" id="start_date" min="2020-01-01" max="{{date('Y-m-d')}}" value="{{request()->input('start_date')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date">To End Date</label>
                                                <input type="date" class="form-control" name="end_date" id="end_date" min="2020-01-01" max="{{date('Y-m-d')}}" value="{{request()->input('end_date')}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="fType2" class="d-none">
                                    <div class="form-group">
                                        <label for="t2_end_date">Specify End Date</label>
                                        <input type="date" class="form-control" name="t2_end_date" id="t2_end_date" min="2020-01-01" max="{{date('Y-m-d')}}" value="{{request()->input('t2_end_date')}}">
                                      </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            <hr>
            @if(request()->input('start_date') && request()->input('end_date'))
            <div class="alert alert-info text-center" role="alert">
                <strong>Note:</strong> Viewing Report Count from {{date('m/d/Y', strtotime(request()->input('start_date')))}} to {{date('m/d/Y', strtotime(request()->input('end_date')))}} only.
            </div>
            @endif
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <div class="text-center">
                                <h4 class="card-title font-weight-bold">{{number_format($activeCount)}} @if($toggleFilterReport == 0)<small>({{round(($activeCount/$totalCasesCount) * 100, 1)}}%)</small>@endif</h4>
                                <p class="card-text">Total Active Cases <button type="button" class="btn btn-link" data-toggle="modal" data-target="#tac_count"><i class="fa fa-info-circle text-white" aria-hidden="true"></i></button></p>
                                <hr>
                                <p><i class="fas fa-male mr-2"></i>Male: {{number_format($activeCount_male)}} <i class="fas fa-female mx-2"></i>Female: {{number_format($activeCount_female)}}</p>
                            </div>
                            <hr>
                            <p>Partial Vaccinated: {{number_format($totalActive_partialVaccinated)}}</p>
                            <p>Fully Vaccinated: {{number_format($totalActive_fullyVaccinated)}}</p>
                            <p>Boostered: {{number_format($totalActive_booster)}}</p>
                            <p>Unvaccinated: {{number_format($totalActive_unvaccinated)}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <div class="text-center">
                                <h4 class="card-title font-weight-bold">{{number_format($recoveredCount)}}
                                    @if($toggleFilterReport == 0)
                                    <small>({{round(($recoveredCount/$totalCasesCount) * 100, 1)}}%)</small>
                                    @else
                                    <small>({{($activeCount != 0) ? round(($recoveredCount/$activeCount) * 100, 1) : 0}}%)</small>
                                    @endif
                                </h4>
                                <p class="card-text">Total Recoveries</p>
                                <hr>
                                <p><i class="fas fa-male mr-2"></i>Male: {{number_format($recoveredCount_male)}} <i class="fas fa-female mx-2"></i>Female: {{number_format($recoveredCount_female)}}</p>
                            </div>
                            <hr>
                            <p>Partial Vaccinated: {{number_format($totalRecovered_partialVaccinated)}}</p>
                            <p>Fully Vaccinated: {{number_format($totalRecovered_fullyVaccinated)}}</p>
                            <p>Boostered: {{number_format($totalRecovered_booster)}}</p>
                            <p>Unvaccinated: {{number_format($totalRecovered_unvaccinated)}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-body">
                            <div class="text-center">
                                <h4 class="card-title font-weight-bold">{{number_format($deathCount)}}
                                    @if($toggleFilterReport == 0)
                                    <small>({{round(($deathCount/$totalCasesCount) * 100, 1)}}%)</small>
                                    @else
                                    <small>({{($activeCount != 0) ? round(($deathCount/$activeCount) * 100, 1) : 0}}%)</small>
                                    @endif
                                </h4>
                                <p class="card-text">Total Deaths</p>
                                <hr>
                                <p><i class="fas fa-male mr-2"></i>Male: {{number_format($deathCount_male)}} <i class="fas fa-female mx-2"></i>Female: {{number_format($deathCount_female)}}</p>
                            </div>
                            <hr>
                            <p>Partial Vaccinated: {{number_format($totalDeath_partialVaccinated)}}</p>
                            <p>Fully Vaccinated: {{number_format($totalDeath_fullyVaccinated)}}</p>
                            <p>Boostered: {{number_format($totalDeath_booster)}}</p>
                            <p>Unvaccinated: {{number_format($totalDeath_unvaccinated)}}</p>
                        </div>
                    </div>
                </div>
            </div>
            @if($toggleFilterReport == 0)
            <div class="row mb-3">
                <div class="col-md-2">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <div class="text-center">
                                <h4 class="card-title font-weight-bold">{{number_format($newActiveCount)}}</h4>
                                <p class="card-text">New Cases</p>
                            </div>
                            <hr>
                            <small>Partial Vaccinated: {{number_format($newActiveCount_partialVaccinated)}}</small>
                            <small>Fully Vaccinated: {{number_format($newActiveCount_fullyVaccinated)}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <div class="text-center">
                                <h4 class="card-title font-weight-bold">{{number_format($lateActiveCount)}}</h4>
                                <p class="card-text">Late Reported Cases</p>
                            </div>
                            <hr>
                            <small>Partial Vaccinated: {{number_format($lateActiveCount_partialVaccinated)}}</small>
                            <small>Fully Vaccinated: {{number_format($lateActiveCount_fullyVaccinated)}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="text-center">
                                <h4 class="card-title font-weight-bold">{{number_format($newRecoveredCount)}}</h4>
                                <p class="card-text">New Recoveries</p>
                            </div>
                            <hr>
                            <small>Partial Vaccinated: {{number_format($newRecoveredCount_partialVaccinated)}}</small>
                            <small>Fully Vaccinated: {{number_format($newRecoveredCount_fullyVaccinated)}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="text-center">
                                <h4 class="card-title font-weight-bold">{{number_format($lateRecoveredCount)}}</h4>
                                <p class="card-text">Late Reported Recoveries</p>
                            </div>
                            <hr>
                            <small>Partial Vaccinated: {{number_format($lateRecoveredCount_partialVaccinated)}}</small>
                            <small>Fully Vaccinated: {{number_format($lateRecoveredCount_fullyVaccinated)}}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-dark">
                        <div class="card-body text-center">
                            <h4 class="card-title font-weight-bold">{{number_format($newDeathCount)}}</h4>
                            <p class="card-text">New Deaths</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card text-white bg-danger mb-3">
                <div class="card-body text-center">
                    <h4 class="card-title font-weight-bold">{{number_format(($totalCasesCount))}}</h4>
                    <p class="card-text">Total Number of Cases</p>
                    <hr>
                    <p><i class="fas fa-male mr-2"></i>Male: {{number_format($activeCount_male + $recoveredCount_male + $deathCount_male)}} <i class="fas fa-female mx-2"></i>Female: {{number_format($activeCount_female + $recoveredCount_female + $deathCount_female)}}</p>
                    <hr>
                    <p>Partial Vaccinated: {{number_format($totalCases_partialVaccinated)}} | Fully Vaccinated: {{number_format($totalCases_fullyVaccinated)}}</p>
                </div>
            </div>
            @endif
            <hr>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold"><i class="fas fa-hotel mr-2"></i>{{number_format($facilityCount)}}</h4>
                            <p class="card-text">Admitted in the City of General Trias Ligtas COVID-19 Facility #1</p>
                            <p class="card-text">(Gen. Trias Sports Park, Brgy. Santiago)</p>
                        </div>
                    </div>
                    <div class="card text-white bg-secondary mt-3">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold"><i class="fas fa-hotel mr-2"></i>{{number_format($facilityTwoCount)}}</h4>
                            <p class="card-text">Admitted in the City of General Trias Ligtas COVID-19 Facility #2</p>
                            <p class="card-text">(Eagle Ridge, Brgy. Javalera)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold"><i class="fas fa-home mr-2"></i>{{number_format($hqCount)}}</h4>
                            <p class="card-text">On Strict Home Quarantine</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h4 class="card-title font-weight-bold"><i class="far fa-hospital mr-2"></i>{{number_format($hospitalCount)}}</h4>
                            <p class="card-text">Admitted in the Hospital/Other Isolation Facility</p>
                        </div>
                    </div>
                </div>
            </div>
            @if(auth()->user()->isCesuAccount() && $toggleFilterReport == 0)
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Infection Rate</th>
                            <th>Recovery Rate</th>
                            <th>Case Fatality Rate</th>
                            <th>Positivity Rate</th>
                            <th>Home Quarantine</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2" style="vertical-align: middle;">1 per 1,000 pop.</td>
                            <td>{{number_format($recoveredCount)}} / {{number_format($totalCasesCount)}}</td>
                            <td>{{number_format($deathCount)}} / {{number_format($totalCasesCount)}}</td>
                            <td>{{number_format($totalCasesCount)}} / {{number_format($allCasesCount)}}</td>
                            <td>{{number_format($hqCount)}} / {{number_format($activeCount)}}</td>
                        </tr>
                        <tr>
                            <td>{{round(($recoveredCount/$totalCasesCount) * 100, 1)}}%</td>
                            <td>{{round(($deathCount/$totalCasesCount) * 100, 1)}}%</td>
                            <td>{{round(($totalCasesCount/$allCasesCount) * 100, 1)}}%</td>
                            <td>{{($activeCount != 0) ? round(($hqCount/$activeCount) * 100, 1) : 0}}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif
            <hr>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center"><b>Cases Breakdown</b></div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Count</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">Asymptomatic</td>
                                        <td>{{number_format($active_asymptomatic_count)}}</td>
                                        <td>{{($activeCount != 0) ? round(($active_asymptomatic_count / $activeCount) * 100, 1) : 0}}%</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Mild</td>
                                        <td>{{number_format($active_mild_count)}}</td>
                                        <td>{{($activeCount != 0) ? round(($active_mild_count / $activeCount) * 100, 1) : 0}}%</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Moderate</td>
                                        <td>{{number_format($active_moderate_count)}}</td>
                                        <td>{{($activeCount != 0) ? round(($active_moderate_count / $activeCount) * 100, 1) : 0}}%</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Severe</td>
                                        <td>{{number_format($active_severe_count)}}</td>
                                        <td>{{($activeCount != 0) ? round(($active_severe_count / $activeCount) * 100, 1) : 0}}%</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Critical</td>
                                        <td>{{number_format($active_critical_count)}}</td>
                                        <td>{{($activeCount != 0) ? round(($active_critical_count / $activeCount) * 100, 1) : 0}}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center"><b>Age Distribution</b></div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Male</th>
                                        <th>Female</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">0 - 17</td>
                                        <td>{{number_format($active_agegroup1_count_male)}}</td>
                                        <td>{{number_format($active_agegroup1_count_female)}}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">18 - 25</td>
                                        <td>{{number_format($active_agegroup2_count_male)}}</td>
                                        <td>{{number_format($active_agegroup2_count_female)}}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">26 - 35</td>
                                        <td>{{number_format($active_agegroup3_count_male)}}</td>
                                        <td>{{number_format($active_agegroup3_count_female)}}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">36 - 45</td>
                                        <td>{{number_format($active_agegroup4_count_male)}}</td>
                                        <td>{{number_format($active_agegroup4_count_female)}}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">46 - 59</td>
                                        <td>{{number_format($active_agegroup5_count_male)}}</td>
                                        <td>{{number_format($active_agegroup5_count_female)}}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">60 AND UP</td>
                                        <td>{{number_format($active_agegroup6_count_male)}}</td>
                                        <td>{{number_format($active_agegroup6_count_female)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header text-center"><b>Nature of Work</b></div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($workdistribution_array as $wd)
                                    <tr>
                                        <td scope="row">{{$wd['title']}}</td>
                                        <td>{{$wd['count']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header text-center"><b>Vaccines</b></div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Partial Vaccinated</th>
                                        <th>Fully Vaccinated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vc_array as $vc)
                                    <tr>
                                        <td scope="row">{{$vc['vaccineName']}}</td>
                                        <td>{{$vc['partialCount']}}</td>
                                        <td>{{$vc['fullCount']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header text-center"><b>Reinfection</b></div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th></th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($toggleFilterReport == 0)
                                    <tr>
                                        <td scope="row">Active</td>
                                        <td>{{number_format($totalActiveReinfection)}}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td scope="row">Recovered</td>
                                        <td>{{number_format($totalRecoveredReinfection)}}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Died</td>
                                        <td>{{number_format($totalDeathReinfection)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="font-weight-bold text-center">
                                <tr class="bg-danger text-white">
                                    <th colspan="5">DATA PER BARANGAY</th>
                                </tr>
                                <tr class="thead-light">
                                    <th>Barangay</th>
                                    <th class="text-danger">Confirmed</th>
                                    @if($toggleFilterReport == 0)
                                    <th>Active</th>
                                    @endif
                                    <th>Deaths</th>
                                    @if($toggleFilterReport == 0)
                                    <th class="text-success">Recoveries</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalConfirmed = 0;
                                $totalActive = 0;
                                $totalDeaths = 0;
                                $totalRecoveries = 0;
                                @endphp
                                @foreach($brgylist as $brgy)
                                <tr>
                                    <td class="font-weight-bold">{{$brgy['name']}}</td>
                                    <td class="text-danger text-center">{{number_format($brgy['confirmed'])}}</td>
                                    @if($toggleFilterReport == 0)
                                    <td class="text-center">{{number_format($brgy['active'])}}</td>
                                    @endif
                                    <td class="text-center">{{number_format($brgy['deaths'])}}</td>
                                    @if($toggleFilterReport == 0)
                                    <td class="text-success text-center">{{number_format($brgy['recoveries'])}}</td>
                                    @endif
                                </tr>
                                @php
                                $totalConfirmed += $brgy['confirmed'];
                                $totalActive += $brgy['active'];
                                $totalDeaths += $brgy['deaths'];
                                $totalRecoveries += $brgy['recoveries'];
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light text-center font-weight-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td class="text-danger">{{number_format($totalConfirmed)}}</td>
                                    @if($toggleFilterReport == 0)
                                    <td>{{number_format($totalActive)}}</td>
                                    @endif
                                    <td>{{number_format($totalDeaths)}}</td>
                                    @if($toggleFilterReport == 0)
                                    <td class="text-success">{{number_format($totalRecoveries)}}</td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="font-weight-bold text-center">
                                <tr>
                                    <th colspan="3" class="bg-danger text-white">SUSPECTED/PROBABLE CASES PER BARANGAY</th>
                                </tr>
                                <tr class="thead-light">
                                    <th>Barangay</th>
                                    <th>Suspected</th>
                                    <th>Probable</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $totalSuspected = 0;
                                $totalProbable = 0;
                                @endphp
                                @foreach($brgylist as $brgy)
                                <tr>
                                    <td class="font-weight-bold">{{$brgy['name']}}</td>
                                    <td class="text-center">{{$brgy['suspected']}}</td>
                                    <td class="text-center">{{$brgy['probable']}}</td>
                                </tr>
                                @php
                                $totalSuspected += $brgy['suspected'];
                                $totalProbable += $brgy['probable'];
                                @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light text-center font-weight-bold">
                                <tr>
                                    <td>TOTAL</td>
                                    <td>{{number_format($totalSuspected)}}</td>
                                    <td>{{number_format($totalProbable)}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tac_count" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">How Total Active Cases are being Counted</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>

<script>
    $('#displayList').click(function (e) { 
        $(this).addClass('disabled');
        $('#displayListNotice').removeClass('d-none');
        $('#displayListLoading').removeClass('d-none');
    });

    $('#filter_type').change(function (e) { 
        e.preventDefault();
        if($(this).val() == '1') {
            $('#fType1').removeClass('d-none');
            $('#fType2').addClass('d-none');

            $('#start_date').prop('required', true);
            $('#end_date').prop('required', true);

            $('#t2_end_date').prop('required', false);
        }
        else {
            $('#fType1').addClass('d-none');
            $('#fType2').removeClass('d-none');

            $('#start_date').prop('required', false);
            $('#end_date').prop('required', false);

            $('#t2_end_date').prop('required', true);
        }
    }).trigger('change');
</script>
@endsection