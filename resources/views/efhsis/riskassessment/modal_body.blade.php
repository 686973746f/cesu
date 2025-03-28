<div class="modal fade" id="ncModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Non-comm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div id="ncAccordian" role="tablist" aria-multiselectable="true">
                    <div class="card">
                        <div class="card-header" role="tab" id="ncSection1Header">
                            <a data-toggle="collapse" data-parent="#ncAccordian" href="#ncSection1" aria-expanded="true" aria-controls="ncSection1">New Risk Assessment Form</a>
                        </div>
                        <div id="ncSection1" class="collapse in" role="tabpanel" aria-labelledby="ncSection1Header">
                            <form action="{{route('raf_create')}}" method="GET">
                                <div class="card-body">
                                    @include('efhsis.riskassessment.modal_content')
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success btn-block">Next</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="ncSection2Header">
                            <a data-toggle="collapse" data-parent="#ncAccordian" href="#ncSection2" aria-expanded="true" aria-controls="ncSection2">View Masterlist</a>
                        </div>
                        <div id="ncSection2" class="collapse in" role="tabpanel" aria-labelledby="ncSection2Header">
                            <div class="card-body">
                                Coming soon.
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" role="tab" id="ncSection3Header">
                            <a data-toggle="collapse" data-parent="#ncAccordian" href="#ncSection3" aria-expanded="true" aria-controls="ncSection3">View Report</a>
                        </div>
                        <div id="ncSection3" class="collapse in" role="tabpanel" aria-labelledby="ncSection3Header">
                            <form action="{{route('raf_reportv1')}}" method="GET">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nc_year"><b class="text-danger">*</b>Year</label>
                                        <input type="number" class="form-control" name="year" id="nc_year" min="{{(date('Y')-5)}}" max="{{date('Y')}}" value="{{date('Y')}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nc_month"><b class="text-danger">*</b>Month</label>
                                        <select class="form-control" name="month" id="nc_month" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="WHOLE_YEAR">Show Whole Year</option>
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nc_brgy"><b class="text-danger">*</b>Barangay</label>
                                        <select class="form-control" name="brgy" id="nc_brgy" required>
                                            <option value="" disabled selected>Choose...</option>
                                            <option value="ALL_BRGY" id="nc_select_allbrgy">Show All Barangays</option>
                                            @foreach (App\Models\EdcsBrgy::where('city_id', 388)->orderBy('name', 'ASC')->get() as $b)
                                                <option value="{{$b->id}}">{{$b->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#nc_month').change(function (e) { 
        e.preventDefault();
        if($(this).val() == 'WHOLE_YEAR') {
            $('#nc_select_allbrgy').addClass('d-none');
            $('#nc_brgy').val(null);
        }
        else {
            $('#nc_select_allbrgy').removeClass('d-none');
        }
    }).trigger('change');
</script>