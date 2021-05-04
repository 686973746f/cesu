@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Report</div>
            <div class="card-body">
                <table class="table" id="dt_table">
                    <thead>
                        <tr>
                            <th colspan="3" class="font-weight-bold text-primary">Total Number of PROBABLE Cases: {{$list1->count()}}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Brgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list1 as $key1 => $item1)
                        <tr>
                            <td scope="row">{{$key1+1}}</td>
                            <td>{{$item1->records->lname.", ".$item1->records->fname." ".$item1->records->mname}}</td>
                            <td>{{$item1->records->address_brgy}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <table class="table" id="dt_table1">
                    <thead>
                        <tr>
                            <th colspan="3" class="font-weight-bold text-primary">Total Number of SUSPECTED Cases: {{$list2->count()}}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Brgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list2 as $key2 => $item2)
                        <tr>
                            <td scope="row">{{$key2+1}}</td>
                            <td>{{$item2->records->lname.", ".$item2->records->fname." ".$item2->records->mname}}</td>
                            <td>{{$item2->records->address_brgy}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <table class="table" id="dt_table1">
                    <thead>
                        <tr>
                            <th colspan="3" class="font-weight-bold text-primary">Total Number of CONFIRMED Cases: {{$list3->count()}}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Brgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list3 as $key3 => $item3)
                        <tr>
                            <td scope="row">{{$key3+1}}</td>
                            <td>{{$item3->records->lname.", ".$item3->records->fname." ".$item3->records->mname}}</td>
                            <td>{{$item3->records->address_brgy}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <table class="table" id="dt_table1">
                    <thead>
                        <tr>
                            <th colspan="3" class="font-weight-bold text-primary">Total Number of NON-COVID Cases: {{$list4->count()}}</th>
                        </tr>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Brgy</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list4 as $key4 => $item4)
                        <tr>
                            <td scope="row">{{$key4+1}}</td>
                            <td>{{$item4->records->lname.", ".$item4->records->fname." ".$item4->records->mname}}</td>
                            <td>{{$item4->records->address_brgy}}</td>
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