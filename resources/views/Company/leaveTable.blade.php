<table class="table table-striped table-bordered bootstrap-datatable datatable" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Username</th>
        <th>Leave Date</th>
        <th>Leave Catagory</th>
        <th>Leave Reason</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody id="ajaxUpdate">
    <?php if($allLeave){
    foreach ($allLeave as $leave):
    ?>
    <tr id="row<?php echo $leave->id ?>">
        <td><?php echo $leave->User->username?></td>
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
            <?php } elseif($leave->leave_status==2){?>
            <span class="label label-important">Rejected</span>
            <?php } ?>
        </td>
        <td class="center">
            <?php
            if($leave->leave_status == 0){ ?>
            <a class="btn btn-success" id="grant_<?php echo $leave->id ?>" >
                <i class="icon-zoom-in icon-white"></i>Grant</a>
            <a class="btn btn-danger" id="reject_<?php echo $leave->id ?>" >
                <i class="icon-trash icon-white"></i>Reject</a>
            <a class="btn btn-danger" id="delete_<?php echo $leave->id ?>">
                <i class="icon-trash icon-white"></i>
                Delete
            </a>
            <?php   }
            elseif($leave->leave_status==1){
            ?>
            <a class="btn btn-danger" id="reject_<?php echo $leave->id ?>"  >
                <i class="icon-trash icon-white"></i> Reject</a>
            <a class="btn btn-danger" id="delete_<?php echo $leave->id ?>">
                <i class="icon-trash icon-white"></i>
                Delete
            </a>
            <?php } elseif($leave->leave_status==2){?>
            <a class="btn btn-success" id="grant_<?php echo $leave->id ?>" >
                <i class="icon-zoom-in icon-white"></i>Grant</a>
            <a class="btn btn-danger" id="delete_<?php echo $leave->id ?>">
                <i class="icon-trash icon-white"></i>
                Delete
            </a>
            <?php } ?>
        </td>
    </tr>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#delete_<?php echo $leave->id ?>").click(function(event) {
                event.preventDefault();
                var values = 'delete';
                var chk = confirm("Are you sure to delete this?");
                if (chk)
                {
                    $.ajax({
                        url: '{!! URL::to("company/change-leave-status/$leave->id") !!}',
                        type: "GET",
                        data: {status: values},
                        cache: false,
                        success: function(data) {
                            $("#row<?php echo $leave->id ?>").hide();
                            $.pnotify({
                                title: 'Message',
                                text: 'Status Deleted.',
                                type: 'success',
                                delay: 3000
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#grant_<?php echo $leave->id ?>").click(function(event) {
                event.preventDefault();
                var values = 'grant';
                var categoryID = "<?php echo $leave->leave_category_id ?>";
                var userID = "<?php echo $leave->user_id ?>";
                var categoryBudget = "<?php echo $leave->LeaveCategories->category_num ?>";
                $.ajax({
                    url: '{!! URL::to("company/change-leave-status/$leave->id") !!}',
                    type: "GET",
                    data: {status: values, categoryID:categoryID, userID:userID, categoryBudget:categoryBudget},
                    success: function(data) {
                        if(data == 'false'){
                            $.pnotify({
                                title: 'Error',
                                text: 'This User Already Take Maximum Leave On This Category',
                                type: 'error',
                                delay: 3000
                            });
                        }else {
                            location.reload();
                        }
                    }
                });

            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#reject_<?php echo $leave->id ?>").click(function(event) {
                event.preventDefault();
                var values = 'reject';
                $.ajax({
                    url: '{!! URL::to("company/change-leave-status/$leave->id") !!}',
                    type: "GET",
                    data: {status: values},
                    success: function(data) {
                        location.reload();
                    }
                });

            });
        });
    </script>
    <?php
    endforeach;
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
<ul class="pagination">
    {!! str_replace('/?', '?', $allLeave->render()) !!}
</ul>