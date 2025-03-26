@extends('layouts.app')

@section('content')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<div class="container">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            <div id='calendar'></div>
        </div>
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: {!! $list !!},
      });
      calendar.render();
    });

  </script>
@endsection