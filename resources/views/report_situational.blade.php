@extends('layouts.app')

@section('content')
@php
if(request()->input('sDate') && request()->input('eDate')) {
    
}
else {
    $sDate = date('Y-m-01');
    $eDate = date('Y-m-d');
}
@endphp
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">COVID-19 Situational Report</div>
            <div class="card-body">
                <form action="">
                    <div id="accordianId" role="tablist" aria-multiselectable="true">
                        <div class="card">
                            <div class="card-header" role="tab" id="section1HeaderId">
                                <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">
                                    Filter results by Date
                                </a>
                            </div>
                            <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                <div class="card-body">
                                    Section 1 content
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div id="chart" style="height: 500px;"></div>
                <hr>
                <table class="table table-bordered" id="brgy_breakdown">
                    <thead>
                        <tr class="font-weight-bold text-primary bg-light text-center">
                            <th colspan="5">Barangay Breakdown of Reported Cases</th>
                        </tr>
                        <tr class="text-center bg-light">
                            <th>Barangay</th>
                            <th>Confirmed</th>
                            <th>Active</th>
                            <th>Death</th>
                            <th>Recovered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brgy_list as $key => $item)
                            @if($item->brgyName != "MEDICARE")
                            <tr class="text-center">
                                <td>{{$item->brgyName}}</td>
                                <td>
                                    <a href="/report/clustering/{{$item->city_id}}/{{$item->id}}">
                                    {{$list
                                    ->where('records.address_brgy', $item->brgyName)
                                    ->where('caseClassification', 'Confirmed')
                                    ->count()}}
                                    </a>
                                </td>
                                <td>{{$list
                                    ->where('records.address_brgy', $item->brgyName)
                                    ->where('outcomeCondition', 'Active')
                                    ->count()}}
                                </td>
                                <td class="text-danger">{{$list
                                    ->where('records.address_brgy', $item->brgyName)
                                    ->where('outcomeCondition', 'Died')
                                    ->count()}}
                                </td>
                                <td class="text-success">{{$list
                                    ->where('records.address_brgy', $item->brgyName)
                                    ->where('outcomeCondition', 'Recovered')
                                    ->count()}}
                                </td>
                            </tr>
                            @endif
                        @endforeach
                        <tfoot>
                            <tr class="font-weight-bold text-center bg-light">
                                <td>TOTAL</td>
                                <td>{{$list
                                    ->where('caseClassification', 'Confirmed')
                                    ->count()}}
                                </td>
                                <td>{{$list
                                    ->where('outcomeCondition', 'Active')
                                    ->count()}}
                                </td>
                                <td class="text-danger">{{$list
                                    ->where('outcomeCondition', 'Died')
                                    ->count()}}
                                </td>
                                <td class="text-success">{{$list
                                    ->where('outcomeCondition', 'Recovered')
                                    ->count()}}
                                </td>
                            </tr>
                        </tfoot>
                    </tbody>
                </table>
                <hr>
                <table class="table table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th>Infection Rate</th>
                            <th>Recovery Rate</th>
                            <th>Case Fatality Rate</th>
                            <th>Positivity Rate</th>
                            <th>Home Quarantine</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr>
                            <td rowspan="2" style="vertical-align: middle;">1 per 1,000 pop</td>
                            <td>{{$recoveryCount}} / {{$formsConfirmedTotal}}</td>
                            <td>{{$fatalityCount}} / {{$formsConfirmedTotal}}</td>
                            <td>{{$positiveCount}} / {{$formstotal}}</td>
                            <td>{{$hqCount}} / {{$formsActiveConfirmedTotal}}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td>{{$recRate}}%</td>
                            <td>{{$fatRate}}%</td>
                            <td>{{$posRate}}%</td>
                            <td>{{$hqRate}}%</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div id="activeCasesBreakdownChart" style="height: 500px;"></div>
                <hr>
                <div id="ageChart" style="height: 500px;"></div>
                <hr>
                <div id="genderChart" style="height: 500px;"></div>
                <hr>
                <table class="table table-bordered text-center">
                    <thead class="bg-light">
                        <tr>
                            <th colspan="2" class="font-weight-bold text-info">CITY OF GENERAL TRIAS LIGTAS COVID-19 FACILITY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-weight-bold">ADMITTED IN THE ISOLATION FACILITY LIGTAS COVID GENTRI (OVAL)</td>
                            <td>123</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">ON STRICT HOME QUARANTINE</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">ADMITTED IN THE HOSPITAL / OTHER ISOLATION FACILITY</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <h1 class="text-center my-5">Stay Safe & Healthy!</h1>
                <script>
                    Chart.helpers.merge((Chart.defaults.global.plugins.datalabels), {
                        color: 'white'
                    });

                    const chart = new Chartisan({
                        el: '#chart',
                        url: "{{route('charts.situational_daily_confirmed_active_chart')}}?sDate={{$sDate}}&eDate={{$eDate}}",
                        hooks: new ChartisanHooks()
                        .colors()
                        .title('Distribution of Confirmed Active COVID-19 Cases per day, n=201. City of General Trias as of {{date("F d, Y")}}')
                        .legend(false)
                        .beginAtZero()
                        .responsive()
                    });

                    const chart3 = new Chartisan({
                        el: '#activeCasesBreakdownChart',
                        url: "{{route('charts.situational_active_cases_breakdown_chart')}}",
                        hooks: new ChartisanHooks()
                        .datasets('pie')
                        .pieColors(['blue', 'dark yellow', 'orange', 'red'])
                        .title('Active Cases Breakdown')
                        .responsive()
                    });

                    const chart2 = new Chartisan({
                        el: '#ageChart',
                        url: "{{route('charts.situational_age_distribution_chart')}}",
                        hooks: new ChartisanHooks()
                        .colors()
                        .title('Age Distribution of Active Cases')
                        .legend(false)
                        .beginAtZero()
                        .responsive()
                    });

                    const chart1 = new Chartisan({
                        el: '#genderChart',
                        url: "{{route('charts.situational_gender_distribution_chart')}}",
                        hooks: new ChartisanHooks()
                        .datasets('pie')
                        .pieColors()
                        .title('Sex Distribution of Active Cases')
                        .responsive()
                    });

                    $('#brgy_breakdown').DataTable({
                        responsive: true,
                        dom: 'tr',
                        paging: false,
                    });
                </script>
            </div>
        </div>
    </div>
@endsection