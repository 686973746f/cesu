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
                                <td>{{$list
                                    ->where('records.address_brgy', $item->brgyName)
                                    ->where('caseClassification', 'Confirmed')
                                    ->count()}}
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
                <div id="activeCasesBreakdownChart" style="height: 500px;"></div>
                <hr>
                <div id="ageChart" style="height: 500px;"></div>
                <hr>
                <div id="genderChart" style="height: 500px;"></div>
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