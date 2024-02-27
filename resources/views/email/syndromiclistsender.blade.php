<div>
    <p>Good Day. OPD List.</p>
    <p></p>
    <ul>
        @foreach($list as $d)
        @if($d->getListOfSuspDiseases() != 'N/A')
        <li>
            <div><b>{{$d->syndromic_patient->getName()}}</b></div>
            <div>Age/Sex: {{$d->syndromic_patient->getAgeInt()}}/{{$d->syndromic_patient->sg()}}</div>
            <div>Contact Number:{{$d->syndromic_patient->getContactNumber()}}</div>
            <div>Address: {{$d->syndromic_patient->getFullAddress()}}</div>
            <div>Symptoms: {{$d->listSymptoms()}}</div>
            <div>List of Suspected Disease/s: {{$d->getListOfSuspDiseases()}}</div>
            <div>DRU: {{$d->dru_name}}</div>
        </li>
        @endif
        @endforeach
    </ul>
</div>