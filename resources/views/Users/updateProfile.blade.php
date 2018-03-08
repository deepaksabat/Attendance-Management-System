@extends('Users/UserLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href=" {!! URL::to('user') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href=" {!! URL::to('user/update-profile') !!}">Edit Info</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> Update Info</h2>

            </div>
            <div class="box-content">
                {!! Form::open(array('role' => 'form', 'id' => 'user_update', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal', 'url' => 'user/update-profile')) !!}
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="user_first_name">First Name</label>
                        <div class="controls">
                            <input type="text" required class="input-xlarge input" name="user_first_name" id="user_first_name" value="<?php echo Auth::user()->user_first_name?>" placeholder="User Last Name">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="user_last_name">Last Name</label>
                        <div class="controls">
                            <input type="text" required class="input-xlarge input" name="user_last_name" id="user_last_name" value="<?php echo Auth::user()->user_last_name?>" placeholder="User Last Name">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="user_name">User Name</label>
                        <div class="controls">
                            <input type="text" required class="input-xlarge input" name="username" id="username" value="<?php echo Auth::user()->username?>" placeholder="User Name">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="user_last_name">Email</label>
                        <div class="controls">
                            <input type="text" required class="input-xlarge input" name="user_email" value="<?php echo Auth::user()->user_email?>" id="user_email" placeholder="User Email">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                    <div id="loader">
                    </div>
                </fieldset>
                    </form>

            </div>
        </div><!--/span-->

    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#user_update").submit(function(event) {
                event.preventDefault();
                var values = $("#user_update").serialize();
                $.ajax({
                    url: "{!! URL::to('user/update-profile') !!}",
                    type: "POST",
                    data: values,
                    cache: false,
                    beforeSend: function(){
                        $('#loader').html('<img src="{{ URL::to('images/loader_gif.gif') }}" style="height: 100px;margin-left: 100px;">');
                    },
                    success: function(data) {
                        $('#loader').hide();
                        if(data=='true') {
                            $.pnotify({
                                title: 'Success',
                                text: 'Updated Successfully',
                                type: 'success',
                                delay: 3000

                            });
                        }else{
                            $.pnotify({
                                title: 'Error',
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