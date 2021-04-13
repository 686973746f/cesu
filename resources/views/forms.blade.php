@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                CIF List
                </div>
                <div>
                    @if($records > 0)
                        <a href="{{route('forms.create')}}" class="btn btn-success">New CIF</a>
                    @else
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Create patient record first to process CIF.">
                        <button class="btn btn-success" style="pointer-events: none;" type="button" disabled>New CIF</button>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('status'))
                <div class="alert alert-{{session('statustype')}}" role="alert">
                    {{session('status')}}
                </div>
            @endif
            
            <form action="{{route('forms.index')}}" method="GET">
                <div class="input-group mb-3">
                    <select class="form-control" name="view" id="">
                        <option value="1" {{(request()->get('view') == '1') ? 'selected' : ''}}>Show All</option>
                        <option value="2" {{(request()->get('view') == '2') ? 'selected' : ''}}>Show All Except Records that has less than 5 Days Exposure History from this day</option>
                        <option value="3" {{(request()->get('view') == '3') ? 'selected' : ''}}>Show All Except Records that has not been exported to Excel yet</option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-outline-info" type="submit"><i class="fas fa-filter mr-2"></i>Filter</button>
                    </div>
                </div>
            </form>

            <form action="{{route('forms.export')}}" method="POST">
                @csrf
                <table class="table table-bordered" id="table_id">
                    <thead>
                        <tr>
                            <th colspan="10" class="text-right"><button type="submit" class="btn btn-primary" id="submit">Export to Excel</button></th>
                        </tr>
                        <tr class="text-center">
                            <th></th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Type of Client</th>
                            <th>Has Exposure History</th>
                            <th>Date of Last Contact</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Printed into Excel</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($forms as $form)
                        <?php

                            if($form->pType == 1) {
                                $ptype = "COVID-19 Case";
                            }
                            else if($form->pType == 2) {
                                $ptype = "For RT-PCR Testing";
                            }
                            else if($form->pType == 3) {
                                $ptype = "Close Contact";
                            }
                            else if($form->pType == 4) {
                                $ptype = "Others";
                            }

                            if($form->expoitem1 == 1) {
                                $emsg = "YES";
                            }
                            else if($form->expoitem1 == 2) {
                                $emsg = "NO";
                            }
                            else {
                                $emsg = "UNKNOWN";
                            }

                            if(is_null($form->expoDateLastCont)) {
                                $edate = "N/A";
                            } 
                            else {
                                $edate = date('m/d/Y', strtotime($form->expoDateLastCont));
                            }
                        ?>
                        <tr>
                            <th class="text-center" style="vertical-align: middle;">
                                <input type="checkbox" class="checks" name="listToPrint[]" id="" value="{{$form->id}}">
                            </th>
                            <td style="vertical-align: middle;">{{$form->records->lname}}, {{$form->records->fname}} {{$form->records->mname}}</td>
                            <td style="vertical-align: middle;" class="text-center">{{$form->records->gender}}</td>
                            <td style="vertical-align: middle;" class="text-center">{{$ptype}}</td>
                            <td style="vertical-align: middle;" class="text-center">{{$emsg}}</td>
                            <td style="vertical-align: middle;" class="text-center">{{$edate}}</td>  
                            <td style="vertical-align: middle;">{{$form->user->name}}</td>
                            <td style="vertical-align: middle;" class="text-center">{{date("m/d/Y H:i:s", strtotime($form->created_at))}}</td>
                            <td style="vertical-align: middle;" class="text-center">{{($form->isExported == 1) ? 'YES' : 'NO'}}</td>
                            <td style="vertical-align: middle;" class="text-center">
                                <a href="forms/{{$form->id}}/edit" class="btn btn-primary">Edit</a>
                            </td>
                        </tr>
                        @empty
                        
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $(document).ready(function () {
        $('#table_id').DataTable();
    });

    $('#submit').prop('disabled', true);

    $('input:checkbox').click(function() {
        if ($(this).is(':checked')) {
            $('#submit').prop("disabled", false);
        } else {
        if ($('.checks').filter(':checked').length < 1){
            $('#submit').attr('disabled',true);}
        }
    });
</script>
@endsection