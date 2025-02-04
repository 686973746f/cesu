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
                </div>
            </div>
        </div>
    </div>
</div>