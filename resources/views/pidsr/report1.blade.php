@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header"><b>PIDSR Report 1</b></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2">Barangay</th>
                            <th colspan="9">Category 1 (Immediately Notifiable)</th>
                            <th colspan="17">Category 2 (Weekly Notifiable)</th>
                        </tr>
                        <tr>
                            <th>AFP</th>
                            <th>AEFI</th>
                            <th>ANTHRAX</th>
                            <th>HFMD</th>
                            <th>MEASLES</th>
                            <th>MENINGO</th>
                            <th>NT</th>
                            <th>PSP</th>
                            <th>RABIES</th>

                            <th>ABD</th>
                            <th>AES</th>
                            <th>AHF</th>
                            <th>HEPATITIS</th>
                            <th>AMES</th>
                            <th>MENINGITIS</th>
                            <th>ChikV</th>
                            <th>CHOLERA</th>
                            <th>DENGUE</th>
                            <th>DIPH</th>
                            <th>INFLUENZA</th>
                            <th>LEPTOSPIROSIS</th>
                            <th>MALARIA</th>
                            <th>NNT</th>
                            <th>PERT</th>
                            <th>ROTAVIRUS</th>
                            <th>TYPHOID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($arr as $a)
                        <tr>
                            <td><b>{{$a['barangay']}}</b></td>
                            <td class="text-center {{($a['afp'] != 0) ? 'font-weight-bold text-danger' : ''}}">{{$a['afp']}}</td>
                            <td class="text-center {{($a['aefi'] != 0) ? 'font-weight-bold text-danger' : ''}}">{{$a['aefi']}}</td>
                            <td class="text-center {{($a['anthrax'] != 0) ? 'font-weight-bold text-danger' : ''}}">{{$a['anthrax']}}</td>
                            <td class="text-center {{($a['hfmd'] != 0) ? 'font-weight-bold text-danger' : ''}}">{{$a['hfmd']}}</td>
                            <td class="text-center {{($a['measles'] != 0) ? 'font-weight-bold text-danger' : ''}}">{{$a['measles']}}</td>
                            <td class="text-center {{($a['meningo'] != 0) ? 'font-weight-bold text-danger' : ''}}">{{$a['meningo']}}</td>
                            <td class="text-center {{($a['nt'] != 0) ? 'font-weight-bold text-danger' : ''}}">{{$a['nt']}}</td>
                            <td class="text-center {{($a['psp'] != 0) ? 'font-weight-bold text-danger' : ''}}">{{$a['psp']}}</td>
                            <td class="text-center {{($a['rabies'] != 0) ? 'font-weight-bold text-danger' : ''}}">{{$a['rabies']}}</td>

                            <td class="text-center">{{$a['abd']}}</td>
                            <td class="text-center">{{$a['aes']}}</td>
                            <td class="text-center">{{$a['ahf']}}</td>
                            <td class="text-center">{{$a['hepatitis']}}</td>
                            <td class="text-center">{{$a['ames']}}</td>
                            <td class="text-center">{{$a['meningitis']}}</td>
                            <td class="text-center">{{$a['chikv']}}</td>
                            <td class="text-center">{{$a['cholera']}}</td>
                            <td class="text-center">{{$a['dengue']}}</td>
                            <td class="text-center">{{$a['diph']}}</td>
                            <td class="text-center">{{$a['influenza']}}</td>
                            <td class="text-center">{{$a['leptospirosis']}}</td>
                            <td class="text-center">{{$a['malaria']}}</td>
                            <td class="text-center">{{$a['nnt']}}</td>
                            <td class="text-center">{{$a['pert']}}</td>
                            <td class="text-center">{{$a['rotavirus']}}</td>
                            <td class="text-center">{{$a['typhoid']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection