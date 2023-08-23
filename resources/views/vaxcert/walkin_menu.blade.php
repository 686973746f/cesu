@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><b>GenTri LGU VaxCert Concern Menu</b></div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#modelId" id="clickbtn">Walang problema sa aking VaxCert at kukuha lamang ako ng kopya</button>
                    <hr>
                    <a href="{{route('vaxcert_walkin_file')}}" class="btn btn-primary btn-lg btn-block">May problema sa aking VaxCert <i>(Kulang ang Dose, No Record Found, etc.)</i></a>
                    <hr>
                    <p class="mb-0 text-center"><b class="text-danger">Paalala:</b> Sa ngayon, ang ika-limang dose o ang ikatlong booster (na Bivalent) ay hindi pa talaga nalabas sa VaxCertPH.</p>
                    <hr>
                    <button type="button" class="btn btn-secondary btn-lg btn-block" data-toggle="modal" data-target="#lostcard">Nawawala ang aking Vaccination Card</button>
                </div>
            </div>
        </div>
    </div> 
</div>

<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Redirecting</h5>
            </div>
            <div class="modal-body text-center">
                <p>Ikaw ay ireredirekta na papuntang VaxCertPH Website makalipas ng tatlong (3) segundo...</p>
                <p>Kung hindi ka na-redirect, pwede mo itong pindutin ☛ <a href="https://vaxcert.doh.gov.ph/#/request">Magpatuloy sa VaxCertPH Website</a></p>
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

<script>
    $("#clickbtn").click(function() {
      // Set a timeout for 3 seconds
      setTimeout(function() {
        // Redirect to Google.com
        window.location.href = "https://vaxcert.doh.gov.ph/#/request";
      }, 3000); // 3000 milliseconds = 3 seconds
    });
</script>
@endsection