@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('forms.import.init')}}" method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="card-header">Import CIF Data from Excel</div>
                <div class="card-body">
                    @csrf
                    <div class="form-group">
                      <label for="thefile"><span class="text-danger font-weight-bold">*</span>Browse to Excel File</label>
                      <input type="file" class="form-control-file" name="thefile" id="thefile" required>
                      <small id="fileHelpId" class="form-text text-muted">Help text</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="importDate"><span class="text-danger font-weight-bold">*</span>Specify Date Where Patients will be Scheduled</label>
                                <input type="date" class="form-control" name="importDate" id="importDate" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="importType"><span class="text-danger font-weight-bold">*</span>Specify Test Type</label>
                                <select class="form-control" name="importType" id="importType" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="OPS">RT-PCR (OPS)</option>
                                    <option value="NPS">RT-PCR (NPS)</option>
                                    <option value="OPS AND NPS">RT-PCR (OPS and NPS)</option>
                                    <option value="ANTIGEN">Antigen Test</option>
                                    <option value="ANTIBODY">Antibody Test</option>
                                    <option value="OTHERS">Others</option>
                                </select>
                              </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </form>
    </div>
@endsection