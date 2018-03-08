@extends('Company.CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href='{!! URL::to("company/all-user") !!}'>All User</a>
            </li>

        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> Force Punch in/out</h2>

            </div>
            <div class="box-content">
                {!! Form::open(array('class' => 'form-horizontal', 'accept-charset' => 'utf-8', 'url' => "company/all-user/$id/force")) !!}
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="user_name">Punch Type</label>

                        <div class="controls bold mar-top-5">
                            <h4>
                                @if($currentPunchStatus)
                                    Punch Out
                                @else
                                    Punch In
                                @endif
                            </h4>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="user_name">Time</label>

                        <div class="controls">
                            <input type="text" required class="input-xlarge" name="time" id="dateTime" readonly
                                   value="<?php echo date('Y-m-d h:i:s')?>">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label"></label>

                        <div class="controls">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </fieldset>
                {!! Form::close() !!}
            </div>
        </div>
        <!--/span-->
    </div>
@endsection

@section('jsBottom')
    <script type="text/javascript">
        $(document).ready(function () {
            $("#dateTime").datetimepicker({
                dateFormat: 'yy-mm-dd',
                timeFormat: "hh:mm:ss"
            });
        });
    </script>
@endsection