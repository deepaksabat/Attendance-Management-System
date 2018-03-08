@extends('Company/CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="{!! URL::to('company/create-user') !!}">Add User</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> Create User</h2>

            </div>
            <div class="box-content" ng-app="mainApp">
                {!! Form::open(array('role' => 'form', 'id' => 'user_creation', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal', 'url' => 'company/create-user')) !!}
                <form class="form-horizontal" id="user_creation" method="post" action="">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="company_name" >User Name</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge " name="username" id="username" placeholder="user name">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="super_admin_name">User Pass</label>
                            <div class="controls">
                                <input type="password" required class="input-xlarge" id="super_admin_name" name="password" placeholder="user password">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_name">IP Protected(If ip protected click here)</label>
                            <div class="controls">
                                <input type="checkbox" name="check" id="check" value="need_ip" style="opacity: 100 !important;" ng-model="show">
                            </div>
                        </div>
                        <div class="control-group" id="ip" ng-show="show">
                            <label class="control-label" for="ip_address">IP Address</label>
                            <div class="controls">
                                <input type="text"  class="input-xlarge number" id="ip_address" name="ip_address" placeholder="ip address">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">Create</button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>

            </div>
        </div><!--/span-->
    </div>
    <script>
       var app = angular.module('mainApp', [], function($interpolateProvider) {
            $interpolateProvider.startSymbol('{kp');
    $interpolateProvider.endSymbol('kp}');
        });
    </script>
    <script type="text/javascript">
        /*$(document).ready(function() {
            $("#ip").hide();
            $("#check").click(function() {
                $("#ip").toggle();
            });
        });*/
    </script>
    <?php $punch_message_error=Session::get('flashError'); if ($punch_message_error) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.pnotify({
                title: 'Error',
                text: '<?php echo $punch_message_error ?>',
                type: 'error',
                delay: 3000

            });
        });
    </script>

    <?php } ?>
@endsection

