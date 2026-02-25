<table class="table table-bordered table-striped" id="mainTbl">
    <thead class="thead-light text-center">
        <tr>
            <th>No.</th>
            <th>Encoded at / by</th>
            <th>Patient ({{ request()->input('year') ?? date('Y')}})</th>
            <th>Age</th>
            <th>Age Group</th>
            <th>Date Registered</th>
            <th>Client Type</th>
            <th>Source</th>
            <th>Current Method</th>
            <th>Next Visit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $index => $record)
        <tr>
            <td scope="row" class="text-center">{{ $records->count() - $loop->index }}</td>
            <td class="text-center">{{ Carbon\Carbon::parse($record->created_at)->format('m/d/Y h:i A') }}</td>
            <td><a href="{{route('etcl_familyplanning_view', $record->id)}}">{{ $record->patient->getName() }}</a></td>
            <td class="text-center">{{ $record->age_years }}</td>
            <td class="text-center">{{ $record->age_group }}</td>
            <td class="text-center">{{ Carbon\Carbon::parse($record->registration_date)->format('M d, Y') }}</td>
            <td class="text-center">{{ $record->getClientType($record->client_type) }}</td>
            <td class="text-center">{{ $record->source }}</td>
            <td class="text-center">{{ $record->getMethod($record->current_method) }}</td>
            <td class="text-center">{{ $record->latestVisit ? Carbon\Carbon::parse($record->latestVisit->visit_date_estimated)->format('M d, Y') : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>