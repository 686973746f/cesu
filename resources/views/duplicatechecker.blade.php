@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table table-bordered table-hover table-striped" id="list1">
            <thead class="thead-light text-center">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records1->sortBy('Grp1') as $record)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td><a href="{{route('records.index')}}?q={{$record->Grp1}}&fdc=1">{{$record->Grp1}}</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $('#list1').dataTable();
    </script>
@endsection