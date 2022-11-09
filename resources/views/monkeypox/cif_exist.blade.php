@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-warning">
        <div class="card-header text-center bg-warning text-danger font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i>Monkeypox CIF already exists for <a href="{{route('records.edit', $d->records->id)}}">{{$d->records->getName()}}</a> <small>(Patient ID: #{{$d->records->id}} | Monkeypox CIF ID: #{{$d->id}})</small></div>
        <div class="card-body">
            <button onclick="fillForm()">Fill PDF</button>
        </div>
        <div class="card-footer">
            <a href="{{route('mp.editcif', ['mk' => $d->id])}}" class="btn btn-primary btn-block">View / Edit</a>
        </div>
    </div>
</div>

<script>

    async function fillForm() {
        // Fetch the PDF with form fields
        const formUrl = '{{asset("MONKEYPOX_CIF.pdf")}}'
        const formPdfBytes = await fetch(formUrl).then(res => res.arrayBuffer())

        // Load a PDF with form fields
        const pdfDoc = await PDFDocument.load(formPdfBytes)

        // Get the form containing all the fields
        const form = pdfDoc.getForm()

        const dru_name = form.getTextField('untitled1')
        const dru_address1 = form.getTextField('untitled2')
        const dru_address2 = form.getTextField('untitled3')

        dru_name.setText('{{$d->dru_name}}')
        dru_address1.setText('{{$d->dru_street}}, {{$d->dru_muncity}}')
        dru_address2.setText('{{$d->dru_province}}, REGION {{$d->dru_region}}')
        
        // Serialize the PDFDocument to bytes (a Uint8Array)
        const pdfBytes = await pdfDoc.save()

        // Trigger the browser to download the PDF document
        download(pdfBytes, "{{$d->records->lname}}_{{$d->records->fname}}_MONKEYPOX_CIF", "application/pdf");
    }
</script>
@endsection