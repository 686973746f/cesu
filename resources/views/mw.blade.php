@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Morbidity Week Count Viewer/Updater</div>
        <div class="card-body">
            @if(session('msg'))
            <div class="alert alert-{{session('msg')}}" role="alert">
                {{session('msgtype')}}
            </div>
            @endif
            <form action="{{route('mw.process')}}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <select class="custom-select" name="year" id="year" required>
                        <option value="" {{(request()->input('year')) ? '' : 'selected'}}>Select Year to Update</option>
                        @foreach(range(date('Y'), 2019) as $y)
                        <option value="{{$y}}" {{(request()->input('year') == $y) ? 'selected' : ''}}>{{$y}}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Manual Update</button>
                    </div>
                </div>
            </form>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">MW</th>
                            <th scope="col">2022</th>
                            <th scope="col">2021</th>
                            <th scope="col">2020</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i=1;$i<=53;$i++)
                        @php
                        if($i <= 9) {
                            $str = '0'.$i;
                        }
                        else {
                            $str = $i;
                        }
                        @endphp
                        <tr class="">
                            <td scope="row"><b>MW{{$i}}</b></td>
                            <td>{{$d['mw'.$i]}}</td>
                            <td>{{$e['mw'.$i]}}</td>
                            <td>{{$f['mw'.$i]}}</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection