@extends('Company.CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href='{!! URL::to("company/all-user") !!}'>All User</a>
                <span class="divider">/</span>
            </li>
            <li>
                <a href=''{!! URL::to("company/user-update/$user->id") !!}'>Update Info</a>
            </li>

        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> User Update</h2>

            </div>
            <div class="box-content">
                <form class="form-horizontal" id="">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="user_name">User Name</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge input" name="username" id="username" placeholder="user name" value="<?php echo $user->username?>">
                                <input type="button" value="Update" class="btn btn-default btn btn-primary" id="user_name_update">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_name">User Designation</label>
                            <div class="controls">
                                <select required class="input-xlarge input" id="designation" name="designation_id">
                                    <option value="">Please Select a Designation</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" @if($designation->id == $user->designation_id) selected @endif>{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                                <input type="button" value="Update" class="btn btn-default btn btn-primary" id="user_designation_update" style="margin-left: 1%;">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_password">User Password</label>
                            <div class="controls">
                                <input type="password" required class="input-xlarge" id="password" name="password" placeholder="Password">
                                <input type="button" value="Update" class="btn btn-default btn btn-primary" id="user_password_update">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_name">Last In Time</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge inputmask" id="time" name="time" placeholder="Use 24 hours time format" value="<?php echo $user->time?>">
                                <input type="button" value="Update" class="btn btn-default btn btn-primary" id="time_update">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="auto_punch_out_time">Auto Punch Out Time</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge inputmask" id="auto_punch_out_time" name="auto_punch_out_time" placeholder="Use 24 hours time format" value="<?php echo $user->auto_punch_out_time?>">
                                <input type="button" value="Update" class="btn btn-default btn btn-primary" id="auto_punch_out_time_update">
                            </div>
                        </div>
                        <div id="loader">
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf">
                    </fieldset>
                </form>

            </div>
        </div><!--/span-->
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#user_password_update").click(function() {
                var values = $('#password').val();
                var csrf = $('#csrf').val();
                $.ajax({
                    url: '{!! URL::to("company/update-user-password/$user->id") !!}',
                    type: "POST",
                    data: {password: values, _token: csrf},
                    cache: false,
                    beforeSend: function(){
                        $('#loader').html('<img src="{{ URL::to('images/loader_gif.gif') }}" style="height: 100px;margin-left: 100px;">');
                    },
                    success: function(data) {
                        $('#loader').hide();
                        if(data == 'true') {
                            $.pnotify({
                                title: 'Message',
                                text: 'Password Updated Successfully',
                                type: 'success',
                                delay: 3000
                            });
                        }else{
                            $.pnotify({
                                title: 'Message',
                                text: data,
                                type: 'error',
                                delay: 3000
                            });
                        }
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#user_name_update").click(function() {
                var values = $('#username').val();
                var csrf = $('#csrf').val();
                $.ajax({
                    url: '{!! URL::to("company/update-user-username/$user->id") !!}',
                    type: "POST",
                    data: {username: values, _token: csrf},
                    cache: false,
                    beforeSend: function(){
                        $('#loader').html('<img src="{{ URL::to('images/loader_gif.gif') }}" style="height: 100px;margin-left: 100px;">');
                    },
                    success: function(data) {
                        $('#loader').hide();
                        if(data == 'true') {
                            $.pnotify({
                                title: 'Message',
                                text: 'Username Updated Successfully',
                                type: 'success',
                                delay: 3000
                            });
                        }else{
                            $.pnotify({
                                title: 'Message',
                                text: data,
                                type: 'error',
                                delay: 3000
                            });
                        }
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#time_update").click(function() {
                var values = $('#time').val();
                var csrf = $('#csrf').val();
                $.ajax({
                    url: '{!! URL::to("company/update-user-time/$user->id") !!}',
                    type: "POST",
                    data: {time: values, _token: csrf},
                    cache: false,
                    beforeSend: function(){
                        $('#loader').html('<img src="{{ URL::to('images/loader_gif.gif') }}" style="height: 100px;margin-left: 100px;">');
                    },
                    success: function(data) {
                        $('#loader').hide();
                        if(data == 'true') {
                            $.pnotify({
                                title: 'Message',
                                text: 'Time Updated Successfully',
                                type: 'success',
                                delay: 3000
                            });
                        }else{
                            $.pnotify({
                                title: 'Message',
                                text: data,
                                type: 'error',
                                delay: 3000
                            });
                        }
                    }
                });
            });

            $("#user_designation_update").click(function() {
                var values = $('#designation').val();
                if(values == ''){
                    $.pnotify({
                        title: 'Message',
                        text: 'User Designation Field is Required',
                        type: 'error',
                        delay: 3000
                    });
                    return false;
                }
                var csrf = $('#csrf').val();
                $.ajax({
                    url: '{!! URL::to("company/update-designation/$user->id") !!}',
                    type: "POST",
                    data: {designation_id: values, _token: csrf},
                    cache: false,
                    beforeSend: function(){
                        $('#loader').html('<img src="{{ URL::to('images/loader_gif.gif') }}" style="height: 100px;margin-left: 100px;">');
                    },
                    success: function(data) {
                        console.log(data);
                        $('#loader').hide();
                        if(data == 'true') {
                            $.pnotify({
                                title: 'Message',
                                text: 'Designation Updated Successfully',
                                type: 'success',
                                delay: 3000
                            });
                        }else{
                            $.pnotify({
                                title: 'Message',
                                text: data,
                                type: 'error',
                                delay: 3000
                            });
                        }
                    }
                });
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#auto_punch_out_time_update").click(function() {
                var values = $('#auto_punch_out_time').val();
                var csrf = $('#csrf').val();
                $.ajax({
                    url: '{!! URL::to("company/update-auto-punch-out-time/$user->id") !!}',
                    type: "POST",
                    data: {time: values, _token: csrf},
                    cache: false,
                    beforeSend: function(){
                        $('#loader').html('<img src="{{ URL::to('images/loader_gif.gif') }}" style="height: 100px;margin-left: 100px;">');
                    },
                    success: function(data) {
                        $('#loader').hide();
                        if(data == 'true') {
                            $.pnotify({
                                title: 'Message',
                                text: 'Auto Punch Out Time Updated Successfully',
                                type: 'success',
                                delay: 3000
                            });
                        }else{
                            $.pnotify({
                                title: 'Message',
                                text: data,
                                type: 'error',
                                delay: 3000
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
@section('jsBottom')
       {!! HTML::script('js/inputmusk.js') !!}
       <script>
           $(document).ready(function(){
               $('.inputmask').inputmask("99:99:99");  //static mask
           });
       </script>
@endsection