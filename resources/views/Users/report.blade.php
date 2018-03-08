@extends('Users.UserLayout')
@section('content')

<div>
    <ul class="breadcrumb">
        <li>
            <a href="{!! URL::to('user') !!}">Home</a> <span class="divider">/</span>
        </li>
        <li>
            <a href=''{!! URL::to("user/table-report") !!}'>Table Report</a>
        </li>

    </ul>
</div>
<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-user"></i> <?php echo $userInfo->username?>'s Attendance List from <?php echo $startDate.' to '.$endDate ?></h2>

        </div>
        <div class="box-content">
            <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>
                        Date
                    </td>
                    <td>
                        In Time
                    </td>
                    <td>
                        Out Time
                    </td>
                    <td>
                        Working Hour
                    </td>
                    <td>
                        Status
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php
                  foreach($allDate as $date):

                      $arr = array_filter($attendanceReport, function($ar) use ($date) {
                          return ($ar['login_date'] == $date);
                      });
                      $arr = array_merge_recursive($arr);

                      $holiday = array_filter($allHoliday, function($ar) use ($date) {
                          return ($ar['holiday'] == $date);
                      });
                      $holiday = array_merge_recursive($holiday);

                      $leave = array_filter($allLeave, function($ar) use ($date) {
                          return ($ar['leave_date'] == $date);
                      });
                      $leave = array_merge_recursive($leave);
                      $i = 0;
                          foreach($arr as $arr){
                              $i++;
                ?>
                <tr>
                    <td>
                        <?php if($i == 1) echo $date;
                        else
                            echo "<span style='display: none'>$date</span>";
                        ?>
                    </td>
                    <td>
                        <?php if ($arr)
                            echo $arr['login_time'];
                            else echo 'absent';
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($arr && $arr['logout_time'] != '0000-00-00 00:00:00')  echo $arr['logout_time'];
                        else
                            echo 'Not Yet Punch Out';
                        ?>
                    </td>
                    <td>
                        <?php if ($arr && $arr['logout_time'] != '0000-00-00 00:00:00'){
                            echo $arr['timediff'];
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($arr && $i == 1){
                            if(is_array($holiday) && !empty($holiday))
                                echo 'Holiday';
                            elseif(is_array($leave) && !empty($leave))
                                echo 'Leave';
                            else
                                echo $arr['status'];
                        }

                        ?>
                    </td>
                </tr>
                <?php
                } ?>

                <?php $i =0;
                if(empty($arr)){
                ?>
                <tr>
                    <td>
                        <?php echo $date?>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php
                        if(is_array($holiday) && !empty($holiday))
                            echo 'Holiday';
                        elseif(is_array($leave) && !empty($leave))
                            echo 'Leave';
                            else
                            echo 'Absent';
                        ?></td>
                </tr>

                <?php } endforeach;?>

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