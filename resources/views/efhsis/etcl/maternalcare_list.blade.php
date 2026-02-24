<table class="table table-bordered table-striped" id="mainTbl">
    <thead class="thead-light text-center">
        <tr>
            <th>No.</th>
            <th>Encoded at / by</th>
            <th>Patient ({{ request()->input('year') ?? date('Y')}})</th>
            <th>Date Registered</th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $index => $record)
        <tr>
            <td scope="row" class="text-center">{{ $records->count() - $loop->index }}</td>
            <td class="text-center">{{ Carbon\Carbon::parse($record->created_at)->format('m/d/Y h:i A') }}</td>
            <td><a href="{{route('etcl_maternal_view', $record->id)}}">{{ $record->patient->getName() }}</a></td>
            <td class="text-center">{{ Carbon\Carbon::parse($record->registration_date)->format('M d, Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>