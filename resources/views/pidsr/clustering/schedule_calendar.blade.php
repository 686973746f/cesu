@extends('layouts.app')

@section('content')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div><b>Fogging/Misting Schedule Calendar</b></div>
                <div>
                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#optionsModal">
                      Options
                    </button>

                    <form action="" method="GET">
                        <div class="modal fade" id="optionsModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Options</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                          <label for="team"><b>*</b>Filter Team</label>
                                          <select class="form-control" name="team" id="team" required>
                                            <option value="CHO">CHO</option>
                                            <option value="CENRO">CENRO</option>
                                            <option value="GSO">GSO</option>
                                            <option value="DOH REGIONAL">DOH REGIONAL</option>
                                          </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
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
        eventDidMount: function(info) {
            // Ensure multi-line text fits inside event
            info.el.style.whiteSpace = 'normal';
        }
      });
      calendar.render();
    });

  </script>
@endsection