@extends('layouts.app')

@section('content')
<div class="container">
    <form action="" method="GET">
        <div class="input-group mb-3">
            <input type="date" class="form-control" name="d" id="d" value="{{(request()->input('d')) ? request()->input('d') : date('Y-m-d')}}" required>
            <div class="input-group-append">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </div>
        </div>
    </form>
    <div class="card mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>New Patients for {{(request()->input('d')) ? date('m/d/Y (D)', strtotime(request()->input('d'))) : date('m/d/Y (D)', strtotime(date('Y-m-d')))}}</b></div>
                <div>Total: {{$new->count()}}</div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="ntable">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Registration #</th>
                        <th>Name</th>
                        <th>Age/Gender</th>
                        <th>Brgy</th>
                        <th>Animal</th>
                        <th>Date Bitten</th>
                        <th>Category</th>
                        <th>Body Part</th>
                        <th>Date Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($new as $n)
                    <tr>
                        <td class="text-center"><a href="{{route('abtc_encode_edit', $n->id)}}">{{$n->case_id}}</a></td>
                        <td>{{$n->patient->getName()}}</td>
                        <td class="text-center">{{$n->patient->getAge()}} / {{$n->patient->sg()}}</td>
                        <td class="text-center">{{$n->patient->address_brgy_text}}</td>
                        <td class="text-center">{{$n->animal_type}}</td>
                        <td class="text-center">{{date('m/d/Y (D)', strtotime($n->bite_date))}}</td>
                        <td class="text-center">{{$n->category_level}}</td>
                        <td class="text-center">{{(!is_null($n->body_site)) ? mb_strtoupper($n->body_site) : 'N/A'}}</td>
                        <td class="text-center"><small>{{date('m/d/Y H:i:s', strtotime($n->created_at))}}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Follow-up Patients {{(request()->input('d')) ? date('m/d/Y (D)', strtotime(request()->input('d'))) : date('m/d/Y (D)', strtotime(date('Y-m-d')))}}</b></div>
                <div>Total: {{$ff->count()}}</div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="fftable">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Registration #</th>
                        <th>Name</th>
                        <th>Age/Gender</th>
                        <th>Brgy</th>
                        <th>Animal</th>
                        <th>Date Bitten</th>
                        <th>Category</th>
                        <th>Body Part</th>
                        <th>Day</th>
                        <th>Date Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ff as $n)
                    <tr>
                        <td class="text-center"><a href="{{route('abtc_encode_edit', $n->id)}}">{{$n->case_id}}</a></td>
                        <td>{{$n->patient->getName()}}</td>
                        <td class="text-center">{{$n->patient->getAge()}} / {{$n->patient->sg()}}</td>
                        <td class="text-center">{{$n->patient->address_brgy_text}}</td>
                        <td class="text-center">{{$n->animal_type}}</td>
                        <td class="text-center">{{date('m/d/Y (D)', strtotime($n->bite_date))}}</td>
                        <td class="text-center">{{$n->category_level}}</td>
                        <td class="text-center">{{(!is_null($n->body_site)) ? mb_strtoupper($n->body_site) : 'N/A'}}</td>
                        <td class="text-center">{{$n->getlatestday()}}</td>
                        <td class="text-center"><small>{{date('m/d/Y H:i:s', strtotime($n->created_at))}}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('#ntable').DataTable({
        fixedHeader: true,
        dom: 'frti',
    });

    $('#fftable').DataTable({
        fixedHeader: true,
        dom: 'frti',
    });
</script>
@endsection