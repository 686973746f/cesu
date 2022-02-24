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
                            <th colspan="2">{{$d->format('d')}}</th>
                            @endforeach
                        </tr>
                        <tr>
                            <th></th>
                            @foreach($period as $d)
                            <th>Suspected</th>
                            <th>Probable</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brgy as $b)
                        <tr>
                            <td scope="row">{{$b->brgyName}}</td>
                            @foreach($period as $d)
                            @php
                            ini_set('max_execution_time', 600);
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
                            @endphp
                            <td class="text-center">{{$sus_count}}</td>
                            <td class="text-center">{{$pro_count}}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection