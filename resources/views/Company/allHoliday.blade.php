@extends('Company.CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>

            <li>
                <a href="{!! URL::to('company/all-holiday') !!}">All Holiday</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="span12">
            <div class="span2">
                <a href="{!! URL::to('company/create-holiday') !!}" class="btn btn-large btn-success" style="width: 100%"><i class="icon-plus icon-white" ></i> Create Holiday</a>
            </div>
        </div>
    </div>
    <?php $row = 0; ?>

    <?php foreach ($allHoliday as $holidays): ?>
    <?php
    $row = $row + 1;
    if ($row == 1) {
        echo '<div class="row-fluid sortable">';
    }
    $date = date_create( $holidays->holiday);
    ?>
    <div class="box span4" id="div_<?php echo $holidays->id ?>">
        <div class="box-header well">
            <h2><i class="icon-th"></i> <?php echo date_format($date, 'Y-m-d'); ?></h2>
            <div class="box-icon">
                <!--<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>-->
                <a href="#" data-rel="tooltip" title="Click here for remove date" class="btn  btn-round" id="close_<?php echo $holidays->id ?>"><i class="icon-remove"></i></a>
            </div>
        </div>


        <div class="box-content">
            <div class="box-content">
                <?php echo date_format($date, 'Y-m-d'); ?>
            </div>
        </div>    </div>


    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf_<?php echo $holidays->id ?>">
    <script type="text/javascript">
        $(document).ready(function() {
            $("#close_<?php echo $holidays->id ?>").click(function(event) {
                event.preventDefault();
                var chk = confirm("Are you sure to delete this?");
                if (chk)
                {
                    $("#close_<?php echo $holidays->id ?>").hide();
                    var csrf = $('#csrf_<?php echo $holidays->id ?>').val();
                    $.ajax({
                        url: '{!! URL::to("company/delete-holiday/$holidays->id") !!}',
                        type: "POST",
                        data: {_token: csrf},
                        cache: false,
                        success: function(data) {
                            $("#div_<?php echo $holidays->id ?>").hide();
                            if(data == 'true'){
                                $.pnotify({
                                    title: 'Message',
                                    text: 'Holiday Deleted Successfully',
                                    type: 'success',
                                    delay: 3000

                                });
                            }else{
                                $.pnotify({
                                    title: 'ERROR',
                                    text: data,
                                    type: 'error',
                                    delay: 3000

                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
    <?php
    if ($row == 3) {
        $row = 0;
        echo '</div>';
    }
    ?>
    <?php endforeach; ?>
    <?php $message_error=Session::get('flashError'); if ($message_error) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.pnotify({
                title: 'Error',
                text: '<?php echo $message_error ?>',
                type: 'error',
                delay: 3000

            });
        });
    </script>

    <?php } ?>
    <?php $message_success=Session::get('flashSuccess');; if ($message_success) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.pnotify({
                title: 'Message',
                text: '<?php echo $message_success ?>',
                type: 'success',
                delay: 3000

            });
        });
    </script>

    <?php } ?>
@endsection