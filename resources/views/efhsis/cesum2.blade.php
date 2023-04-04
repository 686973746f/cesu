@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="text-center">
                    <tr>
                        <th colspan="40">{{$lcode}}</th>
                    </tr>
                    <tr>
                        <th colspan="40">{{$length}}</th>
                    </tr>
                    <tr>
                        <th rowspan="2">Barangay</th>
                        <th colspan="2">0-6 Days</th>
                        <th colspan="2">7-28 Days</th>
                        <th colspan="2">29 Days-11 Mos.</th>
                        <th colspan="2">1-4</th>
                        <th colspan="2">5-9</th>
                        <th colspan="2">10-14</th>
                        <th colspan="2">15-19</th>
                        <th colspan="2">20-24</th>
                        <th colspan="2">25-29</th>
                        <th colspan="2">30-34</th>
                        <th colspan="2">35-39</th>
                        <th colspan="2">40-44</th>
                        <th colspan="2">45-49</th>
                        <th colspan="2">50-54</th>
                        <th colspan="2">55-59</th>
                        <th colspan="2">60-64</th>
                        <th colspan="2">65-69</th>
                        <th colspan="2">70 and Above</th>
                        <th colspan="2">Total</th>
                    </tr>
                    <tr>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                        <th>M</th>
                        <th>F</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arr as $a)
                    <tr>
                        <td>{{$a['barangay']}}</td>
                        <td class="text-center">{{$a['item1_m']}}</td>
                        <td class="text-center">{{$a['item1_f']}}</td>
                        <td class="text-center">{{$a['item2_m']}}</td>
                        <td class="text-center">{{$a['item2_f']}}</td>
                        <td class="text-center">{{$a['item3_m']}}</td>
                        <td class="text-center">{{$a['item3_f']}}</td>
                        <td class="text-center">{{$a['item4_m']}}</td>
                        <td class="text-center">{{$a['item4_f']}}</td>
                        <td class="text-center">{{$a['item5_m']}}</td>
                        <td class="text-center">{{$a['item5_f']}}</td>
                        <td class="text-center">{{$a['item6_m']}}</td>
                        <td class="text-center">{{$a['item6_f']}}</td>
                        <td class="text-center">{{$a['item7_m']}}</td>
                        <td class="text-center">{{$a['item7_f']}}</td>
                        <td class="text-center">{{$a['item8_m']}}</td>
                        <td class="text-center">{{$a['item8_f']}}</td>
                        <td class="text-center">{{$a['item9_m']}}</td>
                        <td class="text-center">{{$a['item9_f']}}</td>
                        <td class="text-center">{{$a['item10_m']}}</td>
                        <td class="text-center">{{$a['item10_f']}}</td>
                        <td class="text-center">{{$a['item11_m']}}</td>
                        <td class="text-center">{{$a['item11_f']}}</td>
                        <td class="text-center">{{$a['item12_m']}}</td>
                        <td class="text-center">{{$a['item12_f']}}</td>
                        <td class="text-center">{{$a['item13_m']}}</td>
                        <td class="text-center">{{$a['item13_f']}}</td>
                        <td class="text-center">{{$a['item14_m']}}</td>
                        <td class="text-center">{{$a['item14_f']}}</td>
                        <td class="text-center">{{$a['item15_m']}}</td>
                        <td class="text-center">{{$a['item15_f']}}</td>
                        <td class="text-center">{{$a['item16_m']}}</td>
                        <td class="text-center">{{$a['item16_f']}}</td>
                        <td class="text-center">{{$a['item17_m']}}</td>
                        <td class="text-center">{{$a['item17_f']}}</td>
                        <td class="text-center">{{$a['item18_m']}}</td>
                        <td class="text-center">{{$a['item18_f']}}</td>
                        <td class="text-center">{{$a['total_m']}}</td>
                        <td class="text-center">{{$a['total_f']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection