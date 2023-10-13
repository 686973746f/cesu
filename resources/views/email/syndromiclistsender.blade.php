<div>
    <p>Good Day. OPD List.</p>
    <p></p>
    <ul>
        @foreach($list as $d)
        @if($d->getListOfSuspDiseases() != 'N/A')
        <li>
            <div>{{$d->syndromic_patient->getName()}}</div>
            <div>{{$d->syndromic_patient->getContactNumber()}}</div>
            <div>{{$d->syndromic_patient->getFullAddress()}}</div>
            <div>Symptoms: {{$d->listSymptoms()}}</div>
            <div>List of Suspected Disease/s: {{$d->getListOfSuspDiseases()}}</div>
        </li>
        @endif
        @endforeach
    </ul>
</div>