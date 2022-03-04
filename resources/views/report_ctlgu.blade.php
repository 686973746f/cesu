@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body" style="font-family: Arial, Helvetica, sans-serif">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th rowspan="3" style="vertical-align: middle;background-color:#f4b185;">Date</th>
                            <th colspan="5" rowspan="2" style="vertical-align: middle;background-color:#f6b26b;">Contact Tracing of Suspect/Probable Cases</th>
                            <th colspan="8" rowspan="2" style="vertical-align: middle;background-color:#ea9999;">Contact Tracing of Confirmed Cases</th>
                            <th colspan="6" rowspan="2" style="vertical-align: middle;background-color:#b7d7a9;">Contact Tracing for Close Contacts</th>
                            <th colspan="8" style="vertical-align: middle;background-color:#d4a6bc;">Isolation compliance of Confirmed Cases</th>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" colspan="5">Active Asymptomatic or Mild with no comorbidities</th>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" colspan="3">Active mild with comorbidity, moderate, severe and critical</th>
                        </tr>
                        <tr>
                            <th style="vertical-align: middle;background-color:#f6b26b;" rowspan="1">No. of Suspect/Probable case of the day</th>
                            <th style="vertical-align: middle;background-color:#f6b26b;" rowspan="1">No. of Suspect/Probable case of the day traced within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#f6b26b;" rowspan="1" class="text-primary">% of Suspect/ Probable case of the day traced within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#f6b26b;" rowspan="1">No. of Suspect/ Probable case traced and isolated within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#f6b26b;" rowspan="2" class="text-primary">% of Suspect/ Probable case isolated within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#ea9999;" rowspan="2">No. of Confirmed/ Active Cases of the day</th>
                            <th style="vertical-align: middle;background-color:#ea9999;" rowspan="2">No. of Confirmed/ Active Cases of the day traced within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#ea9999;" rowspan="2" class="text-primary">% of Confirmed/ Active Cases of the day traced within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#ea9999;" rowspan="2">No. of Pending Confirmed/ Active Cases still to be traced</th>
                            <th style="vertical-align: middle;background-color:#ea9999;" rowspan="2">No. of Pending Confirmed/ Active Cases traced</th>
                            <th style="vertical-align: middle;background-color:#ea9999;" rowspan="2" class="text-primary">% of pending Confirmed/ Active Cases still to be traced traced within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#ea9999;" rowspan="2">No. of Confirmed/ Active Cases traced and quarantined/isolated within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#ea9999;" rowspan="2" class="text-primary">% of Confirmed/ Active Cases isolated/quarantined within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#b7d7a9;" rowspan="2">No. of CCs listed from the Confirmed/ Active Cases</th>
                            <th style="vertical-align: middle;background-color:#b7d7a9;" rowspan="2">No. of CCs listed Traced and Assessed within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#b7d7a9;" rowspan="2" class="text-primary">% of CCs listed Traced and Assesed within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#b7d7a9;" rowspan="2">Case: Close Contact Ratio</th>
                            <th style="vertical-align: middle;background-color:#b7d7a9;" rowspan="2">No. of CCs placed under home quarantine within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#b7d7a9;" rowspan="2" class="text-primary">% of CCs placed under home quarantine within 24 hours</th>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" rowspan="1">Total no. of active asymptomatic or mild with no comorbidities, confirmed cases</th>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" rowspan="1">Total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Home Quarantine</th>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" rowspan="1" class="text-primary">% of total no. of active asymptomatic, mild with no comorbidity, confirmed cases under Home Quarantine</th>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" rowspan="1">Total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Isolation/Quarantine Facility</th>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" rowspan="1" class="text-primary">% of total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Isolation/Quarantine Facility</th>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" rowspan="1">Total number of Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases</th>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" rowspan="1">Total Number of Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases in Hospital</th>
                            <th style="vertical-align: middle;background-color:#d4a6bc;" rowspan="1" class="text-primary">% of total number Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases in Hospital</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{date('m/d/Y')}}</td>
                            <td>{{$item1}}</td>
                            <td>{{$item2}}</td>
                            <td>{{$item3}}%</td>
                            <td>{{$item4}}</td>
                            <td>{{$item5}}%</td>
                            <td>{{$item6}}</td>
                            <td>{{$item7}}</td>
                            <td>{{$item8}}%</td>
                            <td>{{$item9}}</td>
                            <td>{{$item10}}</td>
                            <td>{{$item11}}%</td>
                            <td>{{$item12}}</td>
                            <td>{{$item13}}%</td>
                            <td>{{$item14}}</td>
                            <td>{{$item15}}</td>
                            <td>{{$item16}}%</td>
                            <td>{{$item17}}</td>
                            <td>{{$item18}}</td>
                            <td>{{$item19}}%</td>
                            <td>{{$item20}}</td>
                            <td>{{$item21}}</td>
                            <td>{{$item22}}%</td>
                            <td>{{$item23}}</td>
                            <td>{{$item24}}%</td>
                            <td>{{$item25}}</td>
                            <td>{{$item26}}</td>
                            <td>{{$item27}}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection