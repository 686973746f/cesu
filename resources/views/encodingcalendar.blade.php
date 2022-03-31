@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light text-center table-striped table-bordered">
                        <tr>
                            <th>February</th>
                            @foreach($period as $d)
                            <th>{{$d->format('d')}}</th>
                            @endforeach
                            <th colspan="3">TOTAL</th>
                        </tr>
                        <tr>
                            <th></th>
                            @foreach($period as $d)
                            <th class="text-danger">Confirmed</th>
                            @endforeach
                            <th class="text-danger">Confirmed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $sus_total = 0;
                        $pro_total = 0;
                        $con_total = 0;
                        @endphp
                        @foreach($brgy as $b)
                        <tr>
                            <td scope="row">{{$b->brgyName}}</td>
                            @foreach($period as $d)
                            @php
                            ini_set('max_execution_time', 600);
                            /*
                            $sus_count = App\Models\Forms::with('records')
                            ->whereHas('records', function ($q) use ($b) {
                                $q->where('records.address_province', 'CAVITE')
                                ->where('records.address_city', 'GENERAL TRIAS')
                                ->where('records.address_brgy', $b->brgyName);
                            })
                            ->whereDate('morbidityMonth', $d->format('Y-m-d'))
                            ->where('status', 'approved')
                            ->where('caseClassification', 'Suspect')
                            ->where('outcomeCondition', 'Active')
                            ->count();
    
                            $pro_count = App\Models\Forms::with('records')
                            ->whereHas('records', function ($q) use ($b) {
                                $q->where('records.address_province', 'CAVITE')
                                ->where('records.address_city', 'GENERAL TRIAS')
                                ->where('records.address_brgy', $b->brgyName);
                            })
                            ->whereDate('morbidityMonth', $d->format('Y-m-d'))
                            ->where('status', 'approved')
                            ->where('caseClassification', 'Probable')
                            ->where('outcomeCondition', 'Active')
                            ->count();
                            */

                            $con_count = App\Models\Forms::with('records')
                            ->whereHas('records', function ($q) use ($b) {
                                $q->where('records.address_province', 'CAVITE')
                                ->where('records.address_city', 'GENERAL TRIAS')
                                ->where('records.address_brgy', $b->brgyName);
                            })
                            ->whereDate('morbidityMonth', $d->format('Y-m-d'))
                            ->where('status', 'approved')
                            ->where('caseClassification', 'Confirmed')
                            ->count();
                            $con_total += $con_count;

                            @endphp
                            <td class="text-center text-danger">{{$con_count}}</td>
                            @endforeach
                            <td class="text-center text-danger">{{$con_total}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection