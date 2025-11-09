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
                        <td scope="row"></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td scope="row"></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-striped table-bordered">
                <tbody>
                    <tr>
                        <td>Pregnant</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td>Infant</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td>Lactating Mother</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td>Senior Citizen</td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td>PWDs</td>
                        <td class="text-center"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection