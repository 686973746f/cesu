<div class="modal fade" id="advanceSearch" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Advanced Search</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="{{route('syndromic_diagsearch')}}" method="GET">
                    <div class="card">
                        <div class="card-header">Search by Diagnosis</div>
                        <div class="card-body">
                            <div class="form-group">
                              <label for="diag_name"><b class="text-danger">*</b>Name of Diagnosis</label>
                              <input type="text" class="form-control" name="diag_name" id="diag_name" minlength="1" maxlength="100" style="text-transform: uppercase;" value="{{old('diag_name', request()->input('diag_name'))}}" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="diagSearchType" id="diagSearchType1" value="exact" {{(request()->input('diagSearchType') == 'exact') ? 'checked' : ((request()->input('diagSearchType') != 'wildcard') ? 'checked' : '')}}>
                                <label class="form-check-label" for="diagSearchType1">
                                    Exact Search
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="diagSearchType" id="diagSearchType2" value="wildcard" {{(request()->input('diagSearchType') == 'wildcard') ? 'checked' : ''}}>
                                <label class="form-check-label" for="diagSearchType2"> 
                                    Wildcard Search
                                </label>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="sdate">Start Date</label>
                                        <input type="date" class="form-control" name="sdate" id="sdate" min="2023-01-01" max="{{date('Y-m-d')}}" value="{{old('sdate', request()->input('sdate'))}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="edate">End Date</label>
                                        <input type="date" class="form-control" name="edate" id="edate" min="2023-01-01" max="{{date('Y-m-d')}}" value="{{old('edate', request()->input('edate'))}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success btn-block">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#sdate').change(function (e) {
            e.preventDefault();
            if ($(this).val()) {
                $('#edate').prop('required', true);
                $('#edate').prop('min', $(this).val());
            }
            else {
                $('#edate').prop('required', false);
            }
        }).trigger('change');
    });
</script>