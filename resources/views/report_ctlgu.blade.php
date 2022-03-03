@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>No. of Suspect/Probable case of the day</th>
                            <th>No. of Suspect/Probable case of the day traced within 24 hours</th>
                            <th>% of Suspect/ Probable case of the day traced within 24 hours</th>
                            <th>No. of Suspect/ Probable case traced and isolated within 24 hours</th>
                            <th>% of Suspect/ Probable case isolated within 24 hours</th>
                            <th>No. of Confirmed/ Active Cases of the day</th>
                            <th>No. of Confirmed/ Active Cases of the day traced within 24 hours</th>
                            <th>% of Confirmed/ Active Cases of the day traced within 24 hours</th>
                            <th>No. of Pending Confirmed/ Active Cases still to be traced</th>
                            <th>No. of Pending Confirmed/ Active Cases traced</th>
                            <th>% of pending Confirmed/ Active Cases still to be traced traced within 24 hours</th>
                            <th>No. of Confirmed/ Active Cases traced and quarantined/isolated within 24 hours</th>
                            <th>% of Confirmed/ Active Cases isolated/quarantined within 24 hours</th>
                            <th>No. of CCs listed from the Confirmed/ Active Cases</th>
                            <th>No. of CCs listed Traced and Assessed within 24 hours</th>
                            <th>% of CCs listed Traced and Assesed within 24 hours</th>
                            <th>Case: Close Contact Ratio</th>
                            <th>No. of CCs placed under home quarantine within 24 hours</th>
                            <th>% of CCs placed under home quarantine within 24 hours</th>
                            <th>Total no. of active asymptomatic or mild with no comorbidities, confirmed cases</th>
                            <th>Total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Home Quarantine</th>
                            <th>% of total no. of active asymptomatic, mild with no comorbidity, confirmed cases under Home Quarantine</th>
                            <th>Total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Isolation/Quarantine Facility</th>
                            <th>% of total no. of active asymptomatic, mild with no comorbidities, confirmed cases under Isolation/Quarantine Facility</th>
                            <th>Total number of Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases</th>
                            <th>Total Number of Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases in Hospital</th>
                            <th>% of total number Mild with Comorbidity, Moderate, Severe and Critical Confirmed Cases in Hospital</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{date('m/d/Y')}}</td>
                            <td>{{$item1}}</td>
                            <td>{{$item2}}</td>
                            <td>{{$item3}}</td>
                            <td>{{$item4}}</td>
                            <td>{{$item5}}</td>
                            <td>{{$item6}}</td>
                            <td>{{$item7}}</td>
                            <td>{{$item8}}</td>
                            <td>{{$item9}}</td>
                            <td>{{$item10}}</td>
                            <td>{{$item11}}</td>
                            <td>{{$item12}}</td>
                            <td>{{$item13}}</td>
                            <td>{{$item14}}</td>
                            <td>{{$item15}}</td>
                            <td>{{$item16}}</td>
                            <td>{{$item17}}</td>
                            <td>{{$item18}}</td>
                            <td>{{$item19}}</td>
                            <td>{{$item20}}</td>
                            <td>{{$item21}}</td>
                            <td>{{$item22}}</td>
                            <td>{{$item23}}</td>
                            <td>{{$item24}}</td>
                            <td>{{$item25}}</td>
                            <td>{{$item26}}</td>
                            <td>{{$item27}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection