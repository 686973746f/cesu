@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="text-right mb-3">
            <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkstock">Check Item Stock</button>-->
            <a href="{{route('pharmacy_itemlist')}}" class="btn btn-primary">View Inventory ({{auth()->user()->pharmacybranch->name}})</a>
            <a href="{{route('pharmacy_view_patient_list')}}" class="btn btn-primary">View Patient List</a>
            <!--<a href="{{route('pharmacy_viewreport')}}" class="btn btn-primary">Report</a>-->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#report">Report</button>
            @if(auth()->user()->isAdminPharmacy())
            <hr>
            <a href="{{route('pharmacy_masteritem_list')}}" class="btn btn-warning">View Master Item</a>
            <a href="{{route('pharmacy_list_branch')}}" class="btn btn-warning">View Branches/Entities</a>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div><b>Pharmacy Inventory System</b></div>
                    <div><b>Branch:</b> {{auth()->user()->pharmacybranch->name}}</div>
                </div>
            </div>
            <div class="card-body">
                @if(session('msg'))
                <div class="alert alert-{{session('msgtype')}}" role="alert">
                    {{session('msg')}}
                </div>
                @endif
                <form action="{{route('pharmacy_modify_qr')}}" method="GET">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search Patient ID | SKU Code | Meds QR" name="code" id="code" required autofocus>
                        <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-search mr-2" aria-hidden="true"></i>Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="accordianId" role="tablist" aria-multiselectable="true">
                        <form action="{{route('pharmacy_getdispensary')}}" method="POST">
                            @csrf
                            <div class="card">
                                <div class="card-header text-center" role="tab" id="section1HeaderId">
                                    <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId">
                                        Medicine Dispensary
                                    </a>
                                </div>
                                <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                    <div class="card-body">
                                        @if(auth()->user()->isAdminPharmacy())
                                        <div class="form-group">
                                          <label for="select_branch"><b class="text-danger">*</b>Select Branch</label>
                                          <select class="form-control" name="select_branch" id="select_branch" required>
                                            <option value="ALL">ALL BRANCHES</option>
                                            @foreach(App\Models\PharmacyBranch::get() as $b)
                                            <option value="{{$b->id}}" {{(old('select_branch', auth()->user()->pharmacy_branch_id) == $b->id) ? 'selected' : ''}}>{{$b->name}}</option>
                                            @endforeach
                                          </select>
                                        </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="start_date"><b class="text-danger">*</b>Start Date</label>
                                                    <input type="date" class="form-control" name="start_date" id="start_date" min="2023-01-01" value="{{date('Y-m-d')}}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="end_date"><b class="text-danger">*</b>End Date</label>
                                                    <input type="date" class="form-control" name="end_date" id="end_date" min="2023-01-01" value="{{date('Y-m-d')}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="card">
                            <div class="card-header text-center" role="tab" id="section2HeaderId">
                                <a data-toggle="collapse" data-parent="#accordianId" href="#section2ContentId">
                                    Report Dashboard
                                </a>
                            </div>
                            <div id="section2ContentId" class="collapse in" role="tabpanel" aria-labelledby="section2HeaderId">
                                <div class="card-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection