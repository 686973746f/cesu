@extends('layouts.app')

@section('content')
<style>
    #ntable td {
        vertical-align: middle;
    }

    #fftable td {
        vertical-align: middle;
    }
</style>
<div class="container">
    @if(session('msg'))
    <div class="alert alert-{{session('msgtype')}} text-center" role="alert">
        {{session('msg')}}
    </div>
    @endif
    <form action="{{route('abtc_qr_quicksearch')}}" method="POST" autocomplete="off">
        @csrf
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Quick Search via QR Code / Registration No." name="qr" id="qr" required autofocus>
            <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">Quick Search</button>
            </div>
        </div>
    </form>
    <hr>
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
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="ntable">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Registration #</th>
                            <th>Name</th>
                            <th>Age/Gender</th>
                            <th>Brgy</th>
                            <th>Animal</th>
                            <th>Exposure Date</th>
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
                            <td class="text-center"><small>{{$n->patient->address_brgy_text}}</small></td>
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
    </div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Follow-up Patients {{(request()->input('d')) ? date('m/d/Y (D)', strtotime(request()->input('d'))) : date('m/d/Y (D)', strtotime(date('Y-m-d')))}}</b></div>
                <div>Total: {{$ff->count()}}</div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="fftable">
                    <thead class="thead-light text-center">
                        <tr>
                            <th></th>
                            <th>Registration #</th>
                            <th>Name</th>
                            <th>Age/Gender</th>
                            <th>Brgy</th>
                            <th>Animal</th>
                            <th>Exposure Date</th>
                            <th>Category</th>
                            <th>Body Part</th>
                            <th>Day</th>
                            <th>Date Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ff as $n)
                        <tr>
                            <td class="text-center">
                                @if(!is_null($n->getCurrentDose()))
                                    @if($n->ifCanProcessQuickMark() == true)
                                    <a href="{{route('abtc_encode_process', ['br_id' => $n->id, 'dose' => $n->getCurrentDose()])}}?fsc=1" class="btn btn-primary btn-sm" onclick="return confirm('Confirm process for {{$n->patient->getName()}} ({{$n->case_id}}). Click OK to Confirm.')">Mark as Done</a>
                                    @else
                                    <div>{{$n->ifCanProcessQuickMark()}}</div>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center"><a href="{{route('abtc_encode_edit', $n->id)}}">{{$n->case_id}}</a></td>
                            <td>{{$n->patient->getName()}}</td>
                            <td class="text-center">{{$n->patient->getAge()}} / {{$n->patient->sg()}}</td>
                            <td class="text-center"><small>{{$n->patient->address_brgy_text}}</small></td>
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
</div>

<script>
    $('#ntable').DataTable({
        fixedHeader: true,
        dom: 'frti',
        iDisplayLength: -1,
    });

    $('#fftable').DataTable({
        fixedHeader: true,
        dom: 'frti',
        iDisplayLength: -1,
    });
</script>
@endsection