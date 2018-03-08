@extends('Company.CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href=''{!! URL::to("company/report-summery") !!}'>Summery Report</a>
            </li>

        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> Table Report</h2>

            </div>
            <div class="box-content">
                {!! Form::open(array('role' => 'form', 'id' => 'full-calender', 'accept-charset' => 'utf-8', 'method' => 'post', 'class' => 'form-horizontal', 'url' => 'company/report-summery')) !!}
                <fieldset>
                    <div class="control-group">
                        <label class="control-label" for="company_name">From</label>
                        <div class="controls">
                            <input type="text" id="from" required readonly class="input-xlarge" name="from" id="company_name" placeholder="From Date" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="company_name">To</label>
                        <div class="controls">
                            <input type="text" id="to" required readonly class="input-xlarge" name="to" id="company_name" placeholder="To Date" >
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">Report</button>
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
    <?php $flashError=Session::get('flashError');; if ($flashError) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.pnotify({
                title: 'ERROR',
                text: '<?php echo $flashError ?>',
                type: 'error',
                delay: 5000

            });
        });
    </script>

    <?php } ?>
@endsection