@extends('Users.UserLayout')

@section('content')

    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('user') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="{!! URL::to('user/my-leave') !!}">My Leave</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-user"></i> Leave List</h2>

            </div>
            <div class="box-content">
                <table id="example" class="display" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Leave Date</th>
                        <th>Leave Catagory</th>
                        <th>Leave Reason</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($myLeave){
                    foreach ($myLeave as $leave): ?>
                    <tr>
                        <td><?php echo Auth::user()->username?></td>
                        <td class="center"><?php echo $leave->leave_date?></td>
                        <td class="center"><?php
                            if(isset($leave->LeaveCategories->category))
                                echo $leave->LeaveCategories->category;
                            else
                                echo 'Uncategorized';
                            ?></td>
                        <td class="center"><?php echo $leave->leave_cause?></td>
                        <td class="center">
                            <?php
                            if($leave->leave_status==0){ ?>
                            <span class="label label-warning">Pending</span>
                            <?php   }
                            elseif($leave->leave_status==1){
                            ?>
                            <span class="label label-success">Granted</span>
                            <?php } else{?>
                            <span class="label label-important">Rejected</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php endforeach;
                    } else{ ?>
                    <tr>
                        <td>No data are available</td>
                        <td>No data are available</td>
                        <td>No data are available</td>
                        <td>No data are available</td>
                        <td>No data are available</td>
                        <td>No data are available</td>
                    </tr>
                    <?php   }
                    ?>
                    </tbody>
                </table>
            </div>
        </div><!--/span-->

    </div>
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
