@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>Welcome: {{strtoupper(auth()->user()->name)}}</div>
                        <div>Week: {{$currentWeek}}</div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="text-center alert alert-{{session('statustype')}}" role="alert">
                            {{session('status')}}
                        </div>
                    @endif

                    <a href="{{route('records.index')}}" class="btn btn-primary btn-lg btn-block"><i class="fa fa-user mr-2" aria-hidden="true"></i>Patient Information</a>
                    <button class="btn btn-primary btn-lg btn-block" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-file mr-2" aria-hidden="true"></i>Case Investigation Forms
                    </button>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <a href="{{route('forms.index')}}" class="btn btn-primary btn-lg btn-block">View/Create CIFs</a>
                            @if(auth()->user()->isCesuAccount())
                            <a href="{{route('paswab.view')}}" class="btn btn-primary btn-lg btn-block">Pa-swab List <span class="badge badge-light ml-1">{{number_format($paswabctr)}}</span></a>
                            @endif
                            <a href="{{route('bulkupdate.index')}}" class="btn btn-primary btn-lg btn-block">Bulk Update CIF Status</a>
                        </div>
                    </div>
                    
                    @if(auth()->user()->isCesuAccount())
                    <a href="{{route('selfreport.view')}}" class="btn btn-primary btn-lg btn-block mt-2">Self-Report</a>
                    @endif
                    @if(auth()->user()->canUseLinelist())
                    <a href="{{route('linelist.index')}}" class="btn btn-primary btn-lg btn-block mt-2"><i class="fas fa-archive mr-2"></i>Line List</a>
                    @endif
                    <hr>
                    <a href="{{route('report.index')}}" class="btn btn-primary btn-lg btn-block" id="reportsbtn"><i class="fas fa-chart-bar mr-2"></i>Reports<i class="fas fa-circle-notch fa-spin ml-2" id="reportLoading"></i></a>
                    <div id="reportNotice" class="text-center">
                        <small>Note: Loading report might take a while to finish. Please be patient and do not refresh the page immediately.</small>
                    </div>
                    <hr>
                    <a href="{{route('options.index')}}" class="btn btn-secondary btn-lg btn-block"><i class="fa fa-cog mr-2" aria-hidden="true"></i>Options</i></a>
                    @if(auth()->user()->isAdmin == 1)
                        <hr>
                        <a href="{{route('adminpanel.index')}}" class="btn btn-primary btn-lg btn-block"><i class="fas fa-user-cog mr-2"></i>Admin Panel</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#reportNotice').hide();
    $('#reportLoading').hide();

    $('#reportsbtn').click(function (e) { 
        $(this).addClass('disabled');
        $('#reportNotice').show();
        $('#reportLoading').show();
    });
</script>
@endsection
