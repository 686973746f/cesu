@extends('layouts.app')

@section('content')
<style>
    div.dataTables_paginate {text-align: center}
</style>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div><b>View Inventory</b> (BRANCH: {{auth()->user()->pharmacybranch->name}})</div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="mainTbl">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $ind => $l)
                        <tr>
                            <td class="text-center">{{$ind+1}}</td>
                            <td>{{$l->pharmacysupplymaster->name}}</td>
                            <td class="text-center">{{$l->pharmacysupplymaster->category}}</td>
                            <td class="text-center">{{$l->displayQty()}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $('#mainTbl').dataTable({
            dom: 'QBfritp',
            buttons: [
                {
                    extend: 'excel',
                    title: '',
                },
                'copy',
            ],
        });
    </script>
@endsection