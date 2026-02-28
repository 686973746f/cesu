@if($mode != 'EDIT' && !empty(auth()->user()->getBhsSwitchList()))
<div class="modal fade" id="bhswarning_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm BHS</h5>
            </div>
            <div class="modal-body text-center">
                <div><h4>Encoding: <b>Child Care</b></h4></div>
                <div><h4>On BHS: <b class="text-success">{{auth()->user()->tclbhs->facility_name}}</b></h4></div>
                <div><h5>Please confirm if you are encoding on the correct BHS.</h5></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="bhswarningmodal_close" class="btn btn-secondary" data-dismiss="modal">Please wait</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        function startCloseCountdown() {
            var seconds = 5;
            var $btn = $('#bhswarningmodal_close');

            $btn.prop('disabled', true);

            var interval = setInterval(function () {
                if(seconds === 0) {
                    $btn.text('Proceed');
                } else {
                    $btn.text('Proceed (' + seconds + ')');
                }
                seconds--;

                if (seconds < 0) {
                    clearInterval(interval);

                    // enable again if you want
                    $btn.prop('disabled', false);

                    // auto close the modal
                    //$btn.click();
                }

            }, 1000);
        }

        $('#bhswarning_modal').modal({backdrop: 'static', keyboard: false});
        $('#bhswarning_modal').modal('show');
        startCloseCountdown();
    });
@endif
</script>