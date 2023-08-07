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
                    <a href="{{route('vaxcert_walkin_file')}}" class="btn btn-primary btn-lg btn-block">May problema sa aking VaxCert <i>(Nawawalang Dose, No Record Found, etc.)</i></a>
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
                <p>Kung hindi ka na-redirect, pwede mo itong pindutin ☛ <a href="https://vaxcert.doh.gov.ph/#/">Magpatuloy sa VaxCertPH Website</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    $("#clickbtn").click(function() {
      // Set a timeout for 3 seconds
      setTimeout(function() {
        // Redirect to Google.com
        window.location.href = "https://vaxcert.doh.gov.ph/#/";
      }, 3000); // 3000 milliseconds = 3 seconds
    });
</script>
@endsection