@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center">
                <img src="{{asset('assets/images/CHO_LETTERHEAD_WITH_CESU.png')}}" class="mb-3 img-fluid" style="width: 50rem;">
            </div>
            <div class="card">
                <div class="card-header"><b>GenTri LGU VaxCert (COVID-19 Vaccination Certificate) Concern Menu</b></div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#modelId" id="clickbtn"><b>Walang problema sa aking VaxCert</b> at kukuha lamang ako ng kopya</button>
                    <hr>
                    <button type="button" class="btn btn-danger btn-lg btn-block" data-toggle="modal" data-target="#problemvax"><b>May problema sa aking VaxCert</b> <i>(Kulang ang Dose, No Record Found, etc.)</i></button>
                    <p class="mb-0 text-center"><b class="text-danger">Paalala:</b> Sa ngayon, ang ika-limang dose o ang ikatlong booster (na Bivalent) ay hindi pa talaga nalabas sa VaxCertPH.</p>
                    <hr>
                    <button type="button" class="btn btn-secondary btn-lg btn-block" data-toggle="modal" data-target="#lostcard">Nawawala ang aking Vaccination Card</button>
                </div>
            </div>
            <p class="text-center mt-3">GenTrias LGU VaxCert Concern Ticketing System - Developed and Maintained by <u>Christian James Historillo</u> for CESU Gen. Trias, Cavite ©{{date('Y')}}</p>
        </div>
    </div> 
</div>

<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Redirecting</b></h5>
            </div>
            <div class="modal-body text-center">
                <p>Ikaw ay ireredirekta na papuntang VaxCertPH Website makalipas ng tatlong (3) segundo...</p>
                <p>Kung hindi ka na-redirect, pwede mo itong pindutin ☛ <a href="https://drive.google.com/file/d/1qzTjvaUHcTVCu75K44mGGKHC3UfsvA8D/view?usp=sharing">Magpatuloy sa VaxCertPH Website</a></p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lostcard" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Nawawala ang iyong Vaccination Card</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul>
                    Kung nawawala ang iyong Vaccination Card at dito ka sa General Trias binakunahan, ihanda ang mga sumusunod:
                    <li>Valid ID o Birth Certificate</li>
                    <li>Affidavit of Loss (na pirmado galing sa Notaryo)</li>
                </ul>
                <h6>Makipag-ugnayan sa malapit na GenTri COVID-19 Bakunahan Station sa lugar mo. Ang schedule ay pino-post sa <a href="https://www.facebook.com/GenTriOfficial/">GenTrias Official FB Page</a></h6>
                <h6>Ang mga staff sa Bakunahan Station ang gagawa ng iyong bagong card matapos i-check ang mga record at requirements mo.</h6>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="problemvax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>May Problema ang aking VaxCert</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="problemdiv1">
                    <h5 class="text-center">Dito ba sa General Trias, Cavite binakunahan ang record na may problema sa iyong VaxCert?</h5>
                    <a href="{{route('vaxcert_walkin_file')}}" class="btn btn-success btn-lg btn-block">Oo/Yes</a>
                    <button type="button" id="problembtn_no" class="btn btn-danger btn-lg btn-block">Hindi/No</button>
                </div>
                <div id="problemdiv2" class="d-none">
                    <p style="font-size: 20px;"><b>Kung hindi sa General Trias binakunahan ang concern</b> <i>(Halimbawa: ang nawawalang 2nd dose, o lahat ng dose ay sa ibang LGU nagpabakuna)</i>, makipag-ugnayan sa LGU kung saan ka binakuhan <i>(via call/text/email/viber/facebook page, or mag-walkin mismo sa kanilang opisina)</i> patungkol sa inyong concern dahil sila ang may kontrol at permission sa inyong records.</p>
                    <hr>
                    <p style="font-size: 20px;">Ang mga Staff/Encoders ng GenTrias LGU VaxCert Concerns ay otorisado lamang mag-ayos ng mga records na dito sa GenTrias LGU lamang binakunahan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if($is_maintenance == 1)
<div class="modal fade" id="serverDown" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger"><b>DOH VaxCert Website is currently Unavailable</b></h5>
            </div>
            <div class="modal-body">
                <h5>Since September 2024 pa po naka-down ang website ng DOH na ginagamit po namin upang makapag-print at makapag-ayos ng mga problema sa VaxCert. Walang update ang DOH at DICT kung kailan babalik ang mga ito.</h5>
                <h5>Ang tanging paraan sa ngayon upang makapag-generate ng inyong VaxCert ay gamit ng <b>eGovPH App</b> via <a href="https://play.google.com/store/apps/details?id=egov.app&hl=en&pli=1">Play Store (Android)</a> or <a href="https://apps.apple.com/ph/app/egovph/id6447682225">App Store (iOS/iPhone)</a>. Mag-register gamit ng inyong mobile number at personal details at i-click ang "Health" Section at doon makikita ang inyong VaxCertPH.</h5>
                <hr>
                <h5>For urgent concerns regarding VaxCertPH, maaari niyo pong subukang tumawag sa Central Office ng DOH or DICT.</h5>
                <h5>Salamat po at pasensya na po sa abala.</h5>
            </div>
        </div>
    </div>
</div>

<script>
/*
$('#serverDown').modal({backdrop: 'static', keyboard: false});
$('#serverDown').modal('show');
*/
</script>
@endif

<script>
    $("#clickbtn").click(function() {
      // Set a timeout for 3 seconds
      setTimeout(function() {
        // Redirect to Google.com
        //window.location.href = "https://vaxcert.doh.gov.ph/#/request";
        window.location.href = "https://drive.google.com/file/d/1qzTjvaUHcTVCu75K44mGGKHC3UfsvA8D/view?usp=sharing";
        
      }, 3000); // 3000 milliseconds = 3 seconds
    });

    $('#problembtn_no').click(function (e) { 
        e.preventDefault();
        $('#problemdiv1').addClass('d-none');
        $('#problemdiv2').removeClass('d-none');
    });
</script>
@endsection