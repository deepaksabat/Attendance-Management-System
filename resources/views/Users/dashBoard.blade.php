@extends('Users/UserLayout')
@section('content')
<?php $welcome_message=Session::get('welcome_message'); ?>
@if ($welcome_message)
<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{{ $welcome_message }}</strong>
</div>
@endif
@if ($leaveUpdate)
<div class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong><a style="text-decoration:none; cursor:pointer" href="{!! URL::to('user/my-leave') !!}">Your's {{ $leaveUpdate }} Leave Application Updated</a></strong>
</div>
@endif

<div>
    <ul class="breadcrumb">
        <li>
            <a href=" {!! URL::to('user') !!}">Home</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{!! URL::to('user') !!}">Dashboard</a>
        </li>
    </ul>
</div>
<div class="row-fluid sortable">
    <div class="box span4">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-list-alt"></i>  Punch In/Out</h2>

        </div>
        <div class="box-content">
            <?php
            if ($status == 'Punch Out')
                $punch_url = 'punch-out';
            else
                $punch_url = 'punch-in';
            ?>
                {!! link_to("user/$punch_url/","$status",array('class'=>'btn btn-large btn-success')) !!}
        </div>
    </div>
    <!--/span-->

    <div class="box span4">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-user"></i> Member Activity</h2>

        </div>
        <div class="box-content">
            <div class="box-content">


                <div class="form-group span12">
                    <label for="datepick2" class="span2 control-label">Date</label>
                    <div class="span6">
                        <input type="text" readonly id="datepicker" class="datepicker" name="first_date" value="<?php echo date('Y-m-d', time()); ?>">
                    </div>
                </div>
                <div class="form-group span12">
                    <label for="datepick4" class="span2 control-label">To</label>
                    <div class="span6">
                        <input type="text" readonly id="datepicker2" class="datepicker2" name="second_date" value="<?php echo date('Y-m-d', time()); ?>">
                    </div>
                </div>
                <div class="form-group span12">
                    <label for="datepick4" class="span2 control-label"></label>
                    <div class="span6">
                        <button onclick="window.open('{!! URL::to("user/report") !!}?s_date=' + datepicker.value + '&e_date=' + datepicker2.value)" type="button" class="btn btn-primary">
                            My Report</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div><!--/span-->
<div class="row-fluid">
    <div class="box span12 ">
        <div class="box-header well">
            <h2><i class="icon icon-notice"></i> Notice Board</h2>
        </div>
        <div class="box-content">
            <div>
                <?php
                if($allNotice){
                foreach($allNotice as $key=>$notice): ?>
                <div class="row-fluid sortable" id="row_{!! $notice->id !!}">
                    <div class="box span12">
                        <div class="box-header well" data-original-title>
                            <h2><i class="icon icon-notice"></i> <?php echo $notice->subject?></h2>

                        </div>
                        <div class="box-content" id="ajax_table">
                            <?php echo $notice->message?>
                        </div>
                    </div><!--/span-->

                </div>
                <?php endforeach;}?>
            </div>
        </div>
    </div>
</div>
<?php $punch_message_success=Session::get('punchMessageSuccess');; if ($punch_message_success) { ?>
<script type="text/javascript">
    $(document).ready(function() {
        $.pnotify({
            title: 'Message',
            text: '<?php echo $punch_message_success ?>',
            type: 'success',
            delay: 3000

        });
    });
</script>

<?php } ?>
<?php $punch_message_error=Session::get('punchMessageError'); if ($punch_message_error) { ?>
<script type="text/javascript">
    $(document).ready(function() {
        $.pnotify({
            title: 'Logout',
            text: '<?php echo $punch_message_error ?>',
            type: 'success',
            delay: 3000

        });
    });
</script>

<?php } ?>
@endsection

