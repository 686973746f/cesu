@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>Welcome: {{strtoupper(auth()->user()->name)}}</div>
                        <div>Morbidity Week: {{date('W')}}</div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <div><b>Monkeypox Menu</b></div>
                        <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#changemenu">Change</button></div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="text-center alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <button type="button" class="btn btn-secondary btn-lg btn-block" data-toggle="modal" data-target="#quicksearch"><i class="fas fa-search mr-2"></i>Patient Quick Search</button>
                    <hr>
                    <button class="btn btn-primary btn-lg btn-block" type="button" data-toggle="collapse" data-target="#reportColl">
                        Others
                    </button>
                    <div class="collapse" id="reportColl">
                        <div class="card card-body border-primary">
                            <a href="#" class="btn btn-primary btn-block">View Report</a>
                            <a href="#" class="btn btn-primary btn-block">Export Monkeypox Database</a>
                        </div>
                    </div> 
                </div>
                <div class="card-footer">
                    <p class="text-center">Note: If errors/issues has been found or if site not working properly, please contact CESU Staff Immediately.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="quicksearch" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Patient Quick Search</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="newList">Select Patient to Create or Search (If existing)</label>
                <select class="form-control" name="newList" id="newList"></select>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changemenu" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="{{route('home')}}" class="btn btn-primary btn-block">COVID-19</a>
            </div>
        </div>
    </div>
</div>

<script>
    $('#newList').select2({
        theme: "bootstrap",
        placeholder: 'Search by Name / Patient ID ...',
        ajax: {
            url: "{{route('mp.ajaxlist')}}",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id,
                            class: item.class,
                        }
                    })
                };
            },
            cache: true
        }
    });

    $('#newList').change(function (e) { 
        e.preventDefault()
        var d = $('#newList').select2('data')[0].class;
        var url = "{{route('mp.newcif', ['record_id' => ':id']) }}";

        url = url.replace(':id', $(this).val());
        window.location.href = url;
    });
</script>
@endsection