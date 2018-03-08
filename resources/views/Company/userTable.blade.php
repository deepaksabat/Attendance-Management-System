<table class="table table-striped table-bordered bootstrap-datatable datatable">
    <thead>
    <tr>
        <th colspan="4" style="background-color:#CCCCCC; color:#FF0000;">
            <span style="padding-top:5px"> Date Range&nbsp;&nbsp;</span><input type="text" readonly id="from" name="first_date" value="<?php echo date('Y-m-d', time());   ?>">&nbsp;&nbsp;<span style="color:black">To</span>&nbsp;&nbsp;
            <input type="text" readonly id="to" name="second_date" value="<?php echo date('Y-m-d', time());   ?>">
            <button style="  margin-left: 1%;margin-bottom: 1%;" onclick="window.open('{!! URL::to("company/summery-report") !!}?s_date=' + from.value + '&e_date=' + to.value)"  type="button" class="btn btn-default">

            Summery Report</button>
        </th>
    </tr>
    </thead>
    <thead>
    <tr>
        <th class="text-center-align">Username</th>
        <th class="text-center-align">Designation</th>
        <th class="text-center-align">Status</th>
        <th class="text-center-align">Actions</th>
    </tr>
    </thead>
    <tbody id="update">
    <?php foreach ($allUser as $user): ?>
    <tr id="row_<?php echo $user->id ?>">

        <td style="width: 15%" class="text-center-align">  <?php echo $user->username ?></td>
        <td style="width: 15%" class="text-center-align">
            @if($user->designation_id)
            <span class="label label-warning">
                {{ $user->Designation->name }}
            </span>
            @else
                <span class="label label-danger">
                    None
                </span>
            @endif
        </td>
        <td style="width: 10%" class="center text-center-align">
            <?php if ($user->status == 1) { ?>
            <span class="label label-success">Active</span>

            <?php } else { ?>
            <span class="label label-warning">Inactive</span>
            <?php } ?>
        </td>
        <td style="width: 60%">
            <button onclick="window.open('{!! URL::to("company/report") !!}?s_date=' + from.value + '&e_date=' + to.value + '&id=' +<?php echo $user->id ?>)" type="button" class="btn btn-success">

            <i class="icon-zoom-in icon-white"></i>Individual Report</button>

            <a class="btn btn-info" style="text-decoration: none" href='{!!URL::to("company/user-update/$user->id")  !!}'>
                <i class="icon-edit icon-white"></i>Update
            </a>
            <a class="btn btn-info" style="text-decoration: none" id="status_change_<?php echo $user->id ?>" href="#">
                <span class="label <?php echo $user->status == 1 ? 'label-warning':'label-success'?>"><?php echo $user->status == 1 ? "Inactive" : 'Active'; ?></span>
                <input type="hidden" name="status" id="status_<?php echo $user->id ?>" value="<?php echo $user->status == 1 ? 'inactive': 'active'?>"
            </a>
            <?php if ($user->ip_address) { ?>
            <a class="btn btn-info" style="text-decoration: none" id="removeIp{{ $user->id }}" href="#">
                <span class="label label-warning">Remove IP</span></a>
            <?php } else { ?>
            <a class="btn btn-info" data-toggle="modal" href="#myReport_<?php echo $user->id; ?>" style="text-decoration: none" href="<?php //echo base_url() ?>company/add_ip/<?php echo $user->user_id ?>">
                <span class="label label-success">Add IP</span></a>
            <?php } ?>
            <a class="btn btn-warning" href='{!! URL::to("company/all-user/$user->id/force") !!}'>
                <i class="icon-edit icon-white"></i>
                Force Punch in/out
            </a>
            <a class="btn btn-danger" id="delete_<?php echo $user->id ?>">
                <i class="icon-trash icon-white"></i>
                Delete
            </a>
        </td>
    </tr>

    <div id="myReport_<?php echo $user->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Add IP</h3>
        </div>
        {!! Form::open(array('role' => 'form', 'id' => "add_ip_$user->id", 'class' => 'form-horizontal')) !!}
        <div class="modal-body">
            <fieldset>
                <div class="control-group">
                    <label class="control-label" for="user_first_name">IP Address</label>
                    <div class="controls">
                        <input type="text" required class="input-xlarge ipType input" id="ip_address" name="ip_address" placeholder="ip address">
                        <input type="hidden" name="id" value="<?php echo $user->id?>">
                    </div>
                </div>
            </fieldset>
        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal" class="btn">Close</a>
            <button  type="submit"  class="btn btn-primary">Save</button>
        </div>
        </form>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#delete_<?php echo $user->id ?>").click(function(event) {
                event.preventDefault();
                var values = 'delete';
                var chk = confirm("Are you sure to delete this?");
                if (chk)
                {
                    $.ajax({
                        url: '{!! URL::to("company/delete-user/$user->id") !!}',
                        type: "GET",
                        data: {status: values},
                        cache: false,
                        success: function(data) {
                            $("#row_<?php echo $user->id ?>").hide();
                            $.pnotify({
                                title: 'Message',
                                text: 'User Deleted With His All Information.',
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
            $("#add_ip_<?php echo $user->id?>").submit(function(event) {
                event.preventDefault();
                var values = $("#add_ip_<?php echo $user->id?>").serialize();
                $.ajax({
                    url: "{!! URL::to('company/add-ip') !!}",
                    type: "POST",
                    dataType: 'JSON',
                    data: values,
                    cache: false,
                    success: function(data) {
                        if(data.type == 'success' ){
                            $('#myReport_<?php echo $user->id; ?>').modal('hide')
                            /*$.pnotify({
                                title: 'Message',
                                text: 'IP address added successfully',
                                type: 'success',
                                delay: 3000
                            });
                            $("#update").html(data.info);*/
                            location.reload();
                        }else{
                            $.pnotify({
                                title: 'ERROR',
                                text: data.info,
                                type: 'error',
                                delay: 3000
                            });
                        }
                    }
                });
            });
        });
    </script>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf_<?php echo $user->id ?>">
    <script type="text/javascript">
        $(document).ready(function() {
            $("#status_change_<?php echo $user->id ?>").click(function(event) {
                event.preventDefault();
                var values = $('#status_<?php echo $user->id ?>').val();
                var csrf = $('#csrf_<?php echo $user->id ?>').val();
                $.ajax({
                    url: '{!! URL::to("company/status-change/$user->id") !!}',
                    type: "POST",
                    data: {status: values, _token: csrf},
                    success: function(data) {
                        /*$.pnotify({
                            title: 'Message',
                            text: 'Status Changed Successfully',
                            type: 'success',
                            delay: 3000
                        });
                        $("#update").html(data);*/
                        location.reload();
                    }
                });

            });
        });
    </script><script type="text/javascript">
        $(document).ready(function() {
            $("#removeIp{{ $user->id }}").click(function(event) {
                event.preventDefault();
                var csrf = $('#csrf_<?php echo $user->id ?>').val();
                $.ajax({
                    url: '{!! URL::to("company/remove-ip/$user->id") !!}',
                    type: "POST",
                    data: {_token: csrf},
                    success: function(data) {
                        /*$.pnotify({
                            title: 'Message',
                            text: 'IP Removed Successfully',
                            type: 'success',
                            delay: 3000
                        });
                        $("#update").html(data);*/
                        location.reload();
                    }
                });

            });
        });
    </script>

    <script type="text/javascript">
        $(function() {
            $( "#from" ).datepicker({
                dateFormat:'yy-mm-dd',
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( "#to" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $( "#to" ).datepicker({
                dateFormat:'yy-mm-dd',
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( "#from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
        });
    </script>
    <?php endforeach; ?>
    </tbody>
</table>
<ul class="pagination">
    {!! str_replace('/?', '?', $allUser->render()) !!}
</ul>