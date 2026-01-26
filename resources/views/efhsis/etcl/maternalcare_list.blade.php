<table class="table">
    <thead>
        <tr>
            <th>No.</th>
            <th>Date Registered</th>
            <th>Patient</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $index => $record)
        <tr>
            <td scope="row">{{ $index + 1 }}</td>
            <td>{{ $record->date_registered }}</td>
            <td>{{ $record->patient->getName() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>