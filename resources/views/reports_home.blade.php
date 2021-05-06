@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Report</div>
            <div class="card-body">
                <table class="table" id="dt_table">
                    <thead>
                        <tr>
                            <th colspan="3" class="font-weight-bold text-primary">Total Number of PROBABLE Cases: {{$list->where('caseClassification', 'Probable')->count()}}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Brgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->where('caseClassification', 'Probable') as $key => $item)
                        <tr>
                            <td scope="row">{{$key+1}}</td>
                            <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                            <td>{{$item->records->address_brgy}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <table class="table" id="dt_table">
                    <thead>
                        <tr>
                            <th colspan="3" class="font-weight-bold text-primary">Total Number of SUSPECTED Cases: {{$list->where('caseClassification', 'Suspect')->count()}}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Brgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->where('caseClassification', 'Suspect') as $key => $item)
                        <tr>
                            <td scope="row">{{$key+1}}</td>
                            <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                            <td>{{$item->records->address_brgy}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <table class="table" id="dt_table">
                    <thead>
                        <tr>
                            <th colspan="3" class="font-weight-bold text-primary">Total Number of CONFIRMED Cases: {{$list->where('caseClassification', 'Confirmed')->count()}}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Brgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->where('caseClassification', 'Confirmed') as $key => $item)
                        <tr>
                            <td scope="row">{{$key+1}}</td>
                            <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                            <td>{{$item->records->address_brgy}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <hr>
                <table class="table" id="dt_table">
                    <thead>
                        <tr>
                            <th colspan="3" class="font-weight-bold text-primary">Total Number of NEGATIVE Cases: {{$list->where('caseClassification', 'Non-COVID-19 Case')->count()}}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Brgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list->where('caseClassification', 'Non-COVID-19 Case') as $key => $item)
                        <tr>
                            <td scope="row">{{$key+1}}</td>
                            <td>{{$item->records->lname.", ".$item->records->fname." ".$item->records->mname}}</td>
                            <td>{{$item->records->address_brgy}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('table').each(function(){
                $(this).DataTable();
            });
        });
    </script>
@endsection