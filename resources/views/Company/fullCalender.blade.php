@extends('Company.CompanyLayout')
@section('content')
    <?php
    $currentReport = current($attendanceReport);
    ?>
    <div id="content" class="span12" style="margin-left: 0px">
    <div id='calendar'>
    </div>
    </div>
@endsection

@section('jsBottom')
    {!! HTML::style('css/fullcalendar.css') !!}
    <link href="{!! URL::to('css/fullcalendar.print.css') !!}" rel='stylesheet' media='print' />
    {!! HTML::script('js/moment.min.js') !!}
    {!! HTML::script('js/fullcalendar.min.js') !!}
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultDate: "<?php echo $currentReport['login_date']?>",
                editable: false,
                eventLimit: true, // allow "more" link when too many events
                events: [
    <?php
    foreach($attendanceReport as $attendanceReport){ ?>
                    {
                        id: "<?php echo $attendanceReport['id']?>",
                        title: "<?php echo $attendanceReport['status']?>",
                        start: "<?php echo $attendanceReport['login_time']?>",
                        end: "<?php echo $attendanceReport['logout_time']?>"
                    },
                <?php
                     }
    ?>
                ]
            });

        });
    </script>
    <style>
    #calendar {
            font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
            font-size: 14px;
            max-width: 900px;
            margin: 0 auto;
            margin-left: 0px;
            max-width: 1050px !important;
        }
    </style>
    @endsection
