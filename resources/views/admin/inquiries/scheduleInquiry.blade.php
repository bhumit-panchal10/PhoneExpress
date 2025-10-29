@extends('layouts.app')
@section('title', 'Add Schedule')
@section('content')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    {{-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet"> --}}

    <style>
        .fc-timeGridWeek-view .fc-timegrid-event {
            min-width: 100px !important;
            min-height: 20px !important;
        }



        .fc .fc-event {
            font-size: 11px;
            padding: 2px 4px;
            border-radius: 4px;
            overflow: hidden;
        }

        .fc-timegrid-event {
            padding: 2px 4px !important;
            font-size: 11px !important;
            line-height: 1.2 !important;
            min-height: 18px !important;
        }

        .fc-daygrid-event {
            padding: 2px 4px !important;
            /* ✅ Add this */
            font-size: 11px !important;
            /* ✅ Add this */
            line-height: 1.2 !important;
            /* ✅ Optional for more control */
            white-space: normal !important;
            text-overflow: ellipsis;
        }

        .fc-timegrid-event-title,
        .fc-event-title {
            white-space: normal !important;
        }
    </style>



    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @include('common.alert')
                {{-- @include('patient.show', ['id' => $id]) --}}

                <!-- FullCalendar for Appointment Display -->

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Add Schedule || Customer Name:{{ $Inquiries->customer_name ?? '' }}
                        </h5>
                        <p></p>
                    </div>

                    <div class="card-body">
                        <form id="filter-form" method="POST" action="{{ route('inquiry.schedule_store') }}">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-2">
                                    <input type="hidden" name="inquiry_id" value="{{ $Inquiries->inquiry_id ?? '' }}">
                                    <label for="date" class="form-label">Date</label>

                                    <input type="date" name="date" id="date" class="form-control"
                                        min="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="time" class="form-label">Time</label>
                                    <input type="text" name="time" class="time form-control"
                                        placeholder="Select Time">
                                </div>

                                <div class="col-md-4 d-flex gap-2">
                                    <input type="submit" class="btn btn-primary" id="searchAppointments">

                                    </button>
                                    {{-- <button type="button" class="btn btn-secondary" id="resetFilters">
                                        <a class="text-white" href="">Reset</a>
                                    </button> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-danger">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div id="calendar"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <!-- Appointment Modal -->
    <div class="modal fade" id="ScheduleModal" tabindex="-1" aria-labelledby="ScheduleModalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="ScheduleModalForm" method="POST" action="{{ route('inquiry.schedule_update') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ScheduleModalLabel">Add Reschedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>


                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="inquiry_id" name="inquiry_id" value="">

                        <div class="form-group">
                            <label>Schedule Date</label>
                            <input type="date" class="form-control" name="schedule_date" id="schedule_date">
                        </div>
                        <div class="form-group" id="follow_up_dateBox">
                            Time <span class="text-danger">*</span>
                            <input type="text" id="followup_datetime" name="schdule_time" class="time form-control"
                                palaceholder="Select Time">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Reschedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

    <!-- jQuery UI for Autocomplete -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script>
        $(document).ready(function() {

            // ✅ Initialize Flatpickr once for all `.time` inputs
            flatpickr(".time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K", // 12-hour format
                time_24hr: false,
                minuteIncrement: 30,
                defaultHour: 9,
                defaultMinute: 0,
                minTime: "09:00",
                maxTime: "23:00",
                onChange: function(selectedDates, dateStr) {
                    console.log("Selected Time:", dateStr);
                }
            });

            // ✅ FullCalendar Setup
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                selectable: true,
                eventDisplay: 'block',
                slotEventOverlap: false,
                slotMinTime: "08:00:00",
                firstDay: 1,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },

                // ✅ Load events from backend
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: "{{ route('inquiry.schedule_show') }}",
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            console.log("Fetched Events:", data);
                            successCallback(data);
                        },
                        error: function() {
                            failureCallback();
                            alert('Failed to fetch appointments.');
                        }
                    });
                },

                // ✅ When you click an empty date
                dateClick: function(info) {
                    console.log("Clicked date:", info.dateStr);

                    // ✅ Set date in modal
                    $('#schedule_date').val(info.dateStr);

                    // ✅ Fetch time from backend via AJAX
                    $.ajax({
                        url: "{{ route('inquiry.getScheduleTime') }}",
                        method: "GET",
                        data: {
                            date: info.dateStr
                        },
                        success: function(response) {
                            console.log("Fetched time:", response);

                            if (response.success && response.time) {
                                // ✅ Set fetched time
                                $('#followup_datetime').val(response.time);
                            } else {
                                // ✅ If no schedule found, use default
                                $('#followup_datetime').val('09:00 AM');
                            }

                            // ✅ Show modal after data loaded
                            $('#ScheduleModal').modal('show');
                        },
                        error: function() {
                            alert("Failed to fetch schedule time.");
                        }
                    });
                },


                // ✅ When you click an event (to reschedule)
                eventClick: function(info) {
                    let start = new Date(info.event.start);

                    let formattedDate = start.toISOString().split('T')[0];

                    let hours = start.getHours();
                    let minutes = start.getMinutes();
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12 || 12;
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    const formattedTime = `${hours}:${minutes} ${ampm}`;

                    $('#schedule_date').val(formattedDate);
                    $('#followup_datetime').val(formattedTime);
                    $('#inquiry_id').val(info.event.id);
                    $('#ScheduleModal').modal('show');
                },


                // ✅ Display event titles nicely
                eventDidMount: function(info) {
                    const titleElement = info.el.querySelector('.fc-event-title');
                    if (titleElement) {
                        titleElement.innerHTML = `
                    <div style="white-space: normal; text-align: center;">
                        <strong style="font-size: 12px;">${info.event.title}</strong>
                    </div>
                `;
                        info.el.setAttribute('title', info.event.title);
                    }
                }
            });

            // ✅ Render the calendar
            calendar.render();

            // ✅ Optional: Re-init flatpickr when modal opens (only if it breaks)
            $('#ScheduleModal').on('shown.bs.modal', function() {
                flatpickr(".time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "h:i K",
                    time_24hr: false,
                    minuteIncrement: 15,
                    minTime: "09:00",
                    maxTime: "23:00"
                });
            });

        });
    </script>


@endsection
