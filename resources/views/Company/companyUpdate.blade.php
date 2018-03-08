@extends('Company.CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href=''{!! URL::to("company/update-me") !!}'>Update Info</a>
            </li>

        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> Update Info</h2>

            </div>
            <div class="box-content">
                {!! Form::open(array('role' => 'form', 'id' => 'companyUpdate', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal', 'url' => 'company/update-me')) !!}
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="company_name">Company Name</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge input" name="company_name" id="company_name" placeholder="company name" value="<?php echo $myInfo->Company->company_name?>">
                                <input type="hidden" name="companyID" value="{!! $myInfo->company->id !!}">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="company_email">Company Email</label>
                            <div class="controls">
                                <input type="email" required class="input-xlarge" id="company_email" name="company_email" placeholder="company Email" value="<?php echo $myInfo->Company->company_email?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="company_email">Company Phone No.</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge number" id="company_email" name="phone" placeholder="company Email" value="<?php echo $myInfo->Company->phone?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="company_user_name">User Name</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge input" id="company_user_name" name="username" placeholder="My user name" value="<?php echo $myInfo->username?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="company_user_name">User First Name</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge input" id="user_first_name" name="user_first_name" placeholder="User Last name" value="<?php echo $myInfo->user_first_name?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="company_user_name">User Last Name</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge input" id="company_user_name" name="user_last_name" placeholder="User First name" value="<?php echo $myInfo->user_last_name?>">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">Update</button>
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
            $("#companyUpdate").submit(function(event) {
                event.preventDefault();
                var values = $("#companyUpdate").serialize();
                $.ajax({
                    url: "{!! URL::to('company/update-me') !!}",
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