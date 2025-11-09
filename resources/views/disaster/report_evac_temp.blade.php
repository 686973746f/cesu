 @extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>Evacuation Center Summary Report</b></div>
        <div class="card-body">
            <h5><b>Name of Incident:</b> {{$d->disaster->name}}</h5>
            <h5><b>Evacuation Center:</b> {{$d->name}}</h5>
            <h5><b>Updates as of:</b> {{date('M. d, Y h:i A')}}</h5>
            <hr>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th colspan="5">EVACUEES</th>
                    </tr>
                    <tr>
                        <th>No. of Families</th>
                        <th>No. of Individuals</th>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td>{{$d->familiesinside->count()}}</td>
                        <td>{{$d->getTotalIndividualsAttribute()}}</td>
                        <td>{{$d->countIndividualsByGender('M')}}</td>
                        <td>{{$d->countIndividualsByGender('F')}}</td>
                        <td><b>{{($d->countIndividualsByGender('M') + $d->countIndividualsByGender('F'))}}</b></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Age Group</th>
                        <th>Male</th>
                        <th>Female</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>0 - 5</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('M', 0, 5)}}</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('F', 0, 5)}}</td>
                    </tr>
                    <tr>
                        <td>6 - 10</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('M', 6, 10)}}</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('F', 6, 10)}}</td>
                    </tr>
                    <tr>
                        <td>11 - 19</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('M', 11, 19)}}</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('F', 11, 19)}}</td>
                    </tr>
                    <tr>
                        <td>20 - 40</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('M', 20, 40)}}</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('F', 20, 40)}}</td>
                    </tr>
                    <tr>
                        <td>41 - 50</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('M', 41, 50)}}</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('F', 41, 50)}}</td>
                    </tr>
                    <tr>
                        <td>51 - 60</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('M', 51, 60)}}</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('F', 51, 60)}}</td>
                    </tr>
                    <tr>
                        <td>61 - 70</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('M', 61, 70)}}</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('F', 61, 70)}}</td>
                    </tr>
                    <tr>
                        <td>71 - 75</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('M', 71, 75)}}</td>
                        <td class="text-center">{{$d->countIndividualsByAgeGender('F', 71, 75)}}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <td>Pregnant</td>
                        <td class="text-center">{{$d->countconds('is_pregnant', 'Y')}}</td>
                    </tr>
                    <tr>
                        <td>Infant</td>
                        <td class="text-center">{{$d->countAge(0,1,'<=')}}</td>
                    </tr>
                    <tr>
                        <td>Lactating Mother</td>
                        <td class="text-center">{{$d->countconds('is_lactating', 'Y')}}</td>
                    </tr>
                    <tr>
                        <td>Senior Citizen</td>
                        <td class="text-center">{{$d->countAge(60,60,'>=')}}</td>
                    </tr>
                    <tr>
                        <td>PWDs</td>
                        <td class="text-center">{{$d->countconds('is_pwd', 'Y')}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection