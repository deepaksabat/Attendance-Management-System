@extends('Company.CompanyLayout')
@section('content')

<?php
        $totalHours = 0;
        $totalMinutes = 0;
        $totalSeconds = 0;
        $reports = array();
foreach($attendanceReport as $report){
    if (!isset($reports[$report->user_id])){
        $totalHours = 0;
    $totalMinutes = 0;
    $totalSeconds = 0;
        }
    $reports[$report->user_id]['id'] = $report->id;
    $reports[$report->user_id]['user_id'] = $report->user_id;
    $reports[$report->user_id]['username'] = $report->User->username;
    $reports[$report->user_id]['time'] = explode(":", $report->timediff);;
    $reports[$report->user_id]['workingHours'] = ($totalHours = $totalHours +  $reports[$report->user_id]['time'][0]);
    $reports[$report->user_id]['workingMinutes'] = ($totalMinutes = $totalMinutes +  $reports[$report->user_id]['time'][1]);
    $reports[$report->user_id]['workingSeconds'] = ($totalSeconds = $totalSeconds +  $reports[$report->user_id]['time'][2]);
        }
?>
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href=''{!! URL::to("company/report-summery") !!}'>Summery Report</a>
            </li>

        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-user"></i> Summery Report <?php echo $startDate.' to '.$endDate ?></h2>

            </div>
            <div class="box-content">
                <table id="example" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <td>
                            Name
                        </td>
                        <td>
                            Time
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($reports as $report){
                    ?>
                    <tr>
                        <td>
                            <?php echo $report['username']?>
                        </td>
                        <td>
                            <?php
                                $hours = $report['workingHours'];
                                $minutes = $report['workingMinutes'];
                                $seconds = $report['workingSeconds'];
                                if($report['workingMinutes']/60){
                                $hours = intval($hours + $report['workingMinutes']/60);
                                $minutes=intval($report['workingMinutes']%60);
                                }
                                if($report['workingSeconds']/60){
                                $minutes = intval($minutes + $report['workingSeconds']/60);
                                $seconds=intval($report['workingSeconds']%60);
                                }
                                if(intval($hours) < 10)
                                $hours = '0'.intval($hours);
                                if(intval($minutes) < 10)
                                $minutes = '0'.intval($minutes);
                                if(intval($seconds) < 10)
                                $seconds = '0'.intval($seconds);
                                echo $hours.':'.$minutes.':'.$seconds;
                            ?>

                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div><!--/span-->
    </div>
    @endsection
@section('jsBottom')
    {!! HTML::script('js/jquery.dataTables.js') !!}
    {!! HTML::script('js/dataTables.tableTools.js') !!}
    {!! HTML::style('css/jquery.dataTables.css') !!}
    {!! HTML::style('css/dataTables.tableTools.css') !!}
    <script type="text/javascript" language="javascript" class="init">
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'T<"clear">lfrtip'
            } );
        } );
    </script>
@endsection