@extends('Company.CompanyLayout')
@section('content')
<div>
    <ul class="breadcrumb">
        <li>
            <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
        </li>
        <li>
            <a href="{!! URL::to('company/change-password') !!}">Change Pass</a>
        </li>
    </ul>
</div>
<div class="row-fluid sortable">
    <div class="box span12">
        <div class="box-header well" data-original-title>
            <h2><i class="icon-edit"></i> Change Password</h2>

        </div>
        <div class="box-content">
                {!! Form::open(array('role' => 'form', 'id' => 'pass_change', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal', 'url' => 'company/change-password')) !!}
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="old_pass">Old Password</label>
                        <div class="controls">
                            <input type="password" required  class="input-xlarge " id="old_pass" name="current_pass" placeholder="Old Password">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="user_password">New Password</label>
                        <div class="controls">
                            <input type="password" required  class="input-xlarge " id="user_password" name="new_password" placeholder="New Password">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="retype_user_password">Confirm New Password</label>
                        <div class="controls">
                            <input type="password" required  class="input-xlarge " id="retype_user_password" name="confirm_new_password" placeholder="Re-type sNew Password">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">Change</button>
                        <button type="reset" class="btn">Cancel</button>
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
        $("#pass_change").submit(function(event) {
            event.preventDefault();
            if($('input#user_password').val() != $('input#retype_user_password').val()){
                $.pnotify({
                    title: 'Sorry',
                    text: 'New Password and Confirm Password are not Matched',
                    type: 'error',
                    delay: 3000
                });
                return false;
            }
            var values = $("#pass_change").serialize();
            $.ajax({
                url: "{!! URL::to('company/change-password') !!}",
                type: "POST",
                data: values,
                cache: false,
                beforeSend: function(){
                    $('#loader').html('<img src="{{ URL::to('images/loader_gif.gif') }}" style="height: 100px;margin-left: 100px;">');
                },
                success: function(data) {
                    $('#loader').hide();
                    if(data == 'true') {
                        $.pnotify({
                            title: 'Message',
                            text: 'Password Changed Successfully',
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