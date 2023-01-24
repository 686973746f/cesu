@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card mb-3">
        <div class="card-header"><b>ABTC Records per Brgy (Whole Year {{$sy}})</b></div>
        <div class="card-body">
            <form action="" method="GET">
                <div class="input-group">
                    <select class="custom-select" id="sy" name="sy" required>
                        <option value="" disabled selected>Select Year to Filter...</option>
                        @foreach(range(date('Y'), 2020) as $y)
                            <option value="{{$y}}" {{(old('sy', request()->input('fyear')) == $y) ? 'selected': ''}}>{{$y}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-outline-success" type="submit">Submit</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="whole_brgy">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Barangay</th>
                            <th>Total</th>
                            <th style="background-color: black"></th>
                            <th>Male</th>
                            <th>Female</th>
                            <th style="background-color: black"></th>
                            <th>Cat 2</th>
                            <th>Cat 3</th>
                            <th style="background-color: black"></th>
                            <th>Dog</th>
                            <th>Cat</th>
                            <th>Others</th>
                            <th style="background-color: black"></th>
                            <th>INC</th>
                            <th>Complete</th>
                            <th>Died</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($brgyarray as $b)
                        <tr>
                            <td scope="row"><b>{{$b['name']}}</b></td>
                            <td class="text-center">{{$b['tt']}}</td>
                            <th style="background-color: black"></th>
                            <td class="text-center">{{$b['bmale']}}</td>
                            <td class="text-center">{{$b['bfemale']}}</td>
                            <td style="background-color: black"></td>
                            <td class="text-center">{{$b['bcat2']}}</td>
                            <td class="text-center">{{$b['bcat3']}}</td>
                            <td style="background-color: black"></td>
                            <td class="text-center">{{$b['bdogs']}}</td>
                            <td class="text-center">{{$b['bcats']}}</td>
                            <td class="text-center">{{$b['bothers']}}</td>
                            <td style="background-color: black"></td>
                            <td class="text-center">{{$b['binc']}}</td>
                            <td class="text-center">{{$b['bcomp']}}</td>
                            <td class="text-center">{{$b['bdied']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if(!(request()->input('sy')))
    <div class="card">
        <div class="card-header"><b>Top Barangays (Last 7 days)</b></div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="top_brgy">
                <thead class="thead-light text-center">
                    <tr>
                        <th>Barangay</th>
                        <th>Total</th>
                        <th style="background-color: black"></th>
                        <th>Male</th>
                        <th>Female</th>
                        <th style="background-color: black"></th>
                        <th>Cat 2</th>
                        <th>Cat 3</th>
                        <th style="background-color: black"></th>
                        <th>Dog</th>
                        <th>Cat</th>
                        <th>Others</th>
                        <th style="background-color: black"></th>
                        <th>INC</th>
                        <th>Complete</th>
                        <th>Died</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topbrgyarray as $b)
                    <tr>
                        <td scope="row"><b>{{$b['name']}}</b></td>
                        <td class="text-center">{{$b['tt']}}</td>
                        <th style="background-color: black"></th>
                        <td class="text-center">{{$b['bmale']}}</td>
                        <td class="text-center">{{$b['bfemale']}}</td>
                        <td style="background-color: black"></td>
                        <td class="text-center">{{$b['bcat2']}}</td>
                        <td class="text-center">{{$b['bcat3']}}</td>
                        <td style="background-color: black"></td>
                        <td class="text-center">{{$b['bdogs']}}</td>
                        <td class="text-center">{{$b['bcats']}}</td>
                        <td class="text-center">{{$b['bothers']}}</td>
                        <td style="background-color: black"></td>
                        <td class="text-center">{{$b['binc']}}</td>
                        <td class="text-center">{{$b['bcomp']}}</td>
                        <td class="text-center">{{$b['bdied']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<script>
    $('#whole_brgy').dataTable({
        iDisplayLength: -1,
        'dom': 't',
    });

    $('#top_brgy').dataTable({
        iDisplayLength: -1,
        'dom': 't',
        'aaSorting': ['1', 'desc'],
    });
</script>
@endsection