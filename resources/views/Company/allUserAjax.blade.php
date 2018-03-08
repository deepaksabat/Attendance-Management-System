<?php foreach ($allUser as $user): ?>
<tr>

    <td>  <?php echo $user->username ?></td>
    <td class="center">
        <?php if ($user->status == 1) { ?>
        <span class="label label-success">Active</span>

        <?php } else { ?>
        <span class="label label-warning">Inactive</span>
        <?php } ?>
    </td>
    <td class="center">
        <button onclick="window.open('<?php //echo $this->Url->build(array('controller' => 'admin', 'action' => 'individualReport')) ?>?s_date=' + from.value + '&e_date=' + to.value + '&user_id=' +<?php echo $user->id ?>)" type="button" class="btn btn-success">

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
                        $.pnotify({
                            title: 'Message',
                            text: 'IP address added successfully',
                            type: 'success',
                            delay: 3000
                        });
                        $("#update").html(data.info);
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
            var values = $('#status_<?php echo $user->id ?>').val();
            var csrf = $('#csrf_<?php echo $user->id ?>').val();
            $.ajax({
                url: '{!! URL::to("company/status-change/$user->id") !!}',
                type: "POST",
                data: {status: values, _token: csrf},
                success: function(data) {
                    $.pnotify({
                        title: 'Message',
                        text: 'Status Changed Successfully',
                        type: 'success',
                        delay: 3000
                    });
                    $("#update").html(data);
                }
            });

        });
    });
</script><script type="text/javascript">
    $(document).ready(function() {
        $("#removeIp{{ $user->id }}").click(function(event) {
            var csrf = $('#csrf_<?php echo $user->id ?>').val();
            $.ajax({
                url: '{!! URL::to("company/remove-ip/$user->id") !!}',
                type: "POST",
                data: {_token: csrf},
                success: function(data) {
                    $.pnotify({
                        title: 'Message',
                        text: 'IP Removed Successfully',
                        type: 'success',
                        delay: 3000
                    });
                    $("#update").html(data);
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