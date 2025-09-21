@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Change Month/Year (GET Form) --}}
    <form action="{{ route('attendancesheet_create', $employee->id) }}" method="GET">
        <label for="month">Month:</label>
        <select name="month" id="month">
            @for ($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                </option>
            @endfor
        </select>

        <label for="year">Year:</label>
        <select name="year" id="year">
            @for ($y = now()->year - 5; $y <= now()->year + 2; $y++)
                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                    {{ $y }}
                </option>
            @endfor
        </select>

        <button type="submit">Change</button>
    </form>
    <form action="{{route('attendancesheet_store', $employee->id)}}" method="POST">
        @csrf
        <input type="hidden" name="month" value="{{ $month }}">
        <input type="hidden" name="year" value="{{ $year }}">

        <div class="card">
            <div class="card-header"><b>DTR - {{$employee->getName()}}</b></div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="thead-light text-center">
                        <tr>
                            <th>Date</th>
                            <th>Present</th>
                            <th>Halfday</th>
                            <th>Time In AM</th>
                            <th>Time Out AM</th>
                            <th>Time In PM</th>
                            <th>Time Out PM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dates as $date)
                            @php
                                $saved = $records[$date->toDateString()] ?? null;
                            @endphp
                            <tr>
                                <td>{{ $date->format('m/d/Y (D)') }}</td>
                                <td>
                                    <input type="checkbox"
                                        class="present-toggle"
                                        data-row="{{ $date->toDateString() }}"
                                        name="attendance[{{ $date->toDateString() }}][is_present]"
                                        value="1"
                                        {{ $saved && $saved->is_present ? 'checked' : '' }}
                                        {{ $date->dayOfWeek == Carbon\Carbon::SATURDAY || $date->dayOfWeek == Carbon\Carbon::SUNDAY ? 'disabled' : ''}}>
                                </td>
                                <td>
                                    <input type="checkbox"
                                        class="halfday-toggle"
                                        data-row="{{ $date->toDateString() }}"
                                        name="attendance[{{ $date->toDateString() }}][is_halfday]"
                                        value="1"
                                        {{ $saved && $saved->is_halfday ? 'checked' : '' }}>
                                </td>
                                <td>
                                    <input type="time"
                                        class="time-field row-{{ $date->toDateString() }}"
                                        name="attendance[{{ $date->toDateString() }}][timein_am]"
                                        value="{{ $saved->timein_am ?? ($date->dayOfWeek == Carbon\Carbon::MONDAY) ? '07:00' : '08:00' }}">
                                </td>
                                <td>
                                    <input type="time"
                                        class="time-field row-{{ $date->toDateString() }}"
                                        name="attendance[{{ $date->toDateString() }}][timeout_am]"
                                        value="{{ $saved->timeout_am ?? '12:00' }}">
                                </td>
                                <td>
                                    <input type="time"
                                        class="time-field row-{{ $date->toDateString() }}"
                                        name="attendance[{{ $date->toDateString() }}][timein_pm]"
                                        value="{{ $saved->timein_pm ?? '13:00' }}">
                                </td>
                                <td>
                                    <input type="time"
                                        class="time-field row-{{ $date->toDateString() }}"
                                        name="attendance[{{ $date->toDateString() }}][timeout_pm]"
                                        value="{{ $saved->timeout_pm ?? '17:00' }}">
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-block">Save</button>
            </div>
        </div>
    </form>
</div>

<script>
$(function() {
    function toggleRow(row, presentChecked, halfdayChecked) {
        let timeFields = $(".row-" + row);
        let halfdayBox = $(".halfday-toggle[data-row='" + row + "']");

        if (!presentChecked) {
            // Disable everything if not present
            timeFields.prop("disabled", true);
            halfdayBox.prop("disabled", true).prop("checked", false);
        } else {
            // Enable if present
            timeFields.prop("disabled", false);
            halfdayBox.prop("disabled", false);

            if (halfdayChecked) {
                // Example rule: disable PM if halfday checked
                timeFields.each(function() {
                    if ($(this).attr("name").includes("timein_pm") || 
                        $(this).attr("name").includes("timeout_pm")) {
                        $(this).prop("disabled", true).val("");
                    }
                });
            }
        }
    }

    // Initial run on page load
    $(".present-toggle").each(function() {
        let row = $(this).data("row");
        let presentChecked = $(this).is(":checked");
        let halfdayChecked = $(".halfday-toggle[data-row='" + row + "']").is(":checked");
        toggleRow(row, presentChecked, halfdayChecked);
    });

    // Handle change events
    $(".present-toggle").on("change", function() {
        let row = $(this).data("row");
        let presentChecked = $(this).is(":checked");
        toggleRow(row, presentChecked, false);
    });

    $(".halfday-toggle").on("change", function() {
        let row = $(this).data("row");
        let presentChecked = $(".present-toggle[data-row='" + row + "']").is(":checked");
        let halfdayChecked = $(this).is(":checked");
        toggleRow(row, presentChecked, halfdayChecked);
    });
});
</script>

@endsection