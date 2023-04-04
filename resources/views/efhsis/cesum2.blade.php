@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="text-right">
            <button class="btn btn-success mb-3" onclick="exportTableToExcel('maintbl')">Download as Excel</button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="maintbl">
                <thead class="text-center thead-light">
                    <tr>
                        <th colspan="39">{{$lcode}}</th>
                    </tr>
                    <tr>
                        <th colspan="39">{{$length}}</th>
                    </tr>
                    <tr>
                        <th rowspan="2">Barangay</th>
                        <th colspan="2">0-6 Days</th>
                        <th colspan="2">7-28 Days</th>
                        <th colspan="2">29 Days-11 Mos.</th>
                        <th colspan="2">'1-4</th>
                        <th colspan="2">'5-9</th>
                        <th colspan="2">'10-14</th>
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
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                        <th style="color: blue;">M</th>
                        <th style="color: #FF1493;">F</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arr as $a)
                    <tr>
                        <td><b>{{$a['barangay']}}</b></td>
                        <td class="text-center" style="color: blue;">{{$a['item1_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item1_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item2_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item2_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item3_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item3_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item4_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item4_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item5_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item5_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item6_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item6_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item7_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item7_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item8_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item8_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item9_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item9_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item10_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item10_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item11_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item11_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item12_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item12_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item13_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item13_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item14_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item14_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item15_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item15_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item16_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item16_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item17_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item17_f']}}</td>
                        <td class="text-center" style="color: blue;">{{$a['item18_m']}}</td>
                        <td class="text-center" style="color: #FF1493;">{{$a['item18_f']}}</td>
                        <td class="text-center" style="color: blue;"><b>{{$a['total_m']}}</b></td>
                        <td class="text-center" style="color: #FF1493;"><b>{{$a['total_f']}}</b></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function exportTableToExcel(tableID) {
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify the file name
            var filename = 'table_data.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob([tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob( blob, filename);
            }else{
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }
    </script>
@endsection