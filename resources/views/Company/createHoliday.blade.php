@extends('Company.CompanyLayout')
@section('content')
<div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>

            <li>
                <a href="{!! URL::to('company/create-holiday') !!}">Create Holiday</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> Add Holiday</h2>

            </div>
            <div class="box-content">
                {!! Form::open(array('id' => 'holiday_creation', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal', 'url' => 'company/create-holiday')) !!}
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="date">Select a date</label>
                            <div class="controls">
                                <input type="text" required readonly class="input-xlarge datepicker" id="date" name="holiday[]" placeholder="holiday">
                            </div>
                        </div>
                        <div id="more" >

                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date"></label>
                            <div class="controls">
                                <button type="button" id="add" class="btn btn-default">Add More</button>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">Create</button>
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
            $("#holiday_creation").submit(function(event) {
                event.preventDefault();
                var values = $("#holiday_creation").serialize();
                $.ajax({
                    url: "{!! URL::to('company/create-holiday') !!}",
                    type: "POST",
                    data: values,
                    cache: false,
                    beforeSend: function(){
                        $('#loader').html('<img src="{{ URL::to('images/loader_gif.gif') }}" style="height: 100px;margin-left: 100px;">');
                    },
                    success: function(data) {
                        $('#loader').hide();
                        if(data == 'true') {
                            window.location.href ="{!! URL::to('company/all-holiday') !!}";
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
        $(document).ready(function(){
            $("#add").click(function(event) {
                event.preventDefault();
                $("#more").append('<div class="control-group"><label class="control-label">Select another date</label><div class="controls"><input required readonly type="text" readonly class="input-xlarge datepicker"  name="holiday[]" placeholder="holiday">  <button class="remove">x</button></div></div>');
                $(".datepicker").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat:'yy-mm-dd'
                });
            });
        });
        $(document).on('click', ".remove", function () {
            $(this).parent().parent().closest(".control-group").html('');
        });
    </script>
@endsection