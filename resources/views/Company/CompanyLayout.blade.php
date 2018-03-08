<!DOCTYPE html>
<html lang="en">
<head style="display: inline !important;">
    <title>Kingpabel ATN
    </title>
    {!! HTML::script('js/charisma/js/jquery-1.7.2.min.js') !!}
    {!! HTML::script('css/bootstrap/js/bootstrap-alert.js') !!}
    {{--{!! HTML::script('js/charisma/js/jquery.dataTables.min.js') !!}--}}
    {!! HTML::style('css/bootstrap/css/bootstrap.css') !!}
    {!! HTML::style('css/custom.css') !!}
    {!! HTML::style('css/font-awesome.min.css') !!}
    {!! HTML::style('css/bootstrap/css/bootstrap-responsive.css') !!}
    {!! HTML::style('css/charisma/css/bootstrap-cerulean.css') !!}
    {!! HTML::style('css/charisma/css/charisma-app.css') !!}
    {!! HTML::style('css/jquery-ui.css') !!}
    {!! HTML::style('css/jquery-ui-timepicker.css') !!}
    {!! HTML::style('css/charisma/css/uniform.default.css') !!}
    {!! HTML::style('css/charisma/css/opa-icons.css') !!}
    {!! HTML::style('css/pnotify/jquery.pnotify.default.icons.css') !!}
    {!! HTML::style('css/pnotify/jquery.pnotify.default.css') !!}
    {!! HTML::script('js/pnotify/jquery.pnotify.js') !!}
    {!! HTML::script('js/angular.min.js') !!}
    {!! HTML::script('js/googleChart.js') !!}

    {{--codepan chart --}}
    {!! HTML::script('js/amCharts.js') !!}
    {!! HTML::script('js/serial.js') !!}
    {!! HTML::script('js/none.js') !!}
    @yield('cssTop')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-60038966-1', 'auto');
        ga('send', 'pageview');

    </script>

    @yield('jsBottom')
    <script>
        $(function() {
            $( ".datepicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat:'yy-mm-dd'
            });
            $( ".datepicker2" ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat:'yy-mm-dd'
            });
        });
    </script>
    <script>
        $(function(){
            $(".input").bind("keyup blur",function() {
                var $th = $(this);
                $th.val( $th.val().replace(/[^A-z0-9,#. _@-]/g, function(str) { return ''; } ) );
            });
        })
        $(function(){
            $(".number").bind("keyup blur",function() {
                var $th = $(this);
                $th.val( $th.val().replace(/[^0-9-.]/g, function(str) { return ''; } ) );
            });
        })
    </script>
    <!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js') !!}
    <![endif]-->
    <script type="text/javascript" language="javascript">
        function resultDelete()
        {
            var chk=confirm("Are you sure to delete this?");
            if(chk)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    </script>
</head>

<body>
<div class="navbar navbar-inverse">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="http://www.kingpabel.com">Kingpabel</a>
            <!-- user dropdown starts -->
            <div class="btn-group pull-right" >
                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="icon-user"></i><span class="hidden-phone"> {{  Auth::user()->username  }}</span>
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        {!! link_to("company/logout","Logout") !!}

                    </li>
                </ul>
            </div>
            <!-- user dropdown ends -->
        </div>
    </div>
</div>
<!-- topbar ends -->
<div class="container-fluid">
    <div class="row-fluid">

        <!-- left menu starts -->
        <div class="span2 main-menu-span">
            <div class="well nav-collapse sidebar-nav">
                <ul class="nav nav-tabs nav-stacked main-menu">
                    <li class="nav-header hidden-tablet"> Main</li>
                    <li><a class="ajax-link" href="{!! URL::to('company') !!}"><i class="icon-home"></i><span class="hidden-tablet"> Dashboard</span></a></li>
                    <li class="nav-header hidden-tablet"> User</li>
                    <li><a class="ajax-link" href="{!! URL::to('company/create-user') !!}"><i class="icon-plus"></i><span class="hidden-tablet"> Create User</span></a></li>
                    <li><a class="ajax-link" href="{!! URL::to('company/all-user') !!}"><i class="icon-list"></i><span class="hidden-tablet"> All User</span></a></li>
                    <li class="nav-header hidden-tablet"> Edit</li>
                    <li><a class="ajax-link" href="{!! URL::to('company/update-me') !!}"><i class="icon-edit"></i><span class="hidden-tablet"> Update Info</span></a></li>
                    <li><a class="ajax-link" href="{!! URL::to('company/change-password') !!}"><i class="icon-edit"></i><span class="hidden-tablet"> Password Change</span></a></li>
                    <li class="nav-header hidden-tablet"> Holiday</li>
                    <li><a class="ajax-link" href="{!! URL::to('company/create-holiday') !!}"><i class="icon-calendar"></i><span class="hidden-tablet"> Create Holiday</span></a></li>
                    <li><a class="ajax-link" href="{!! URL::to('company/all-holiday') !!}"><i class="icon-list"></i><span class="hidden-tablet"> All Holiday</span></a></li>
                    <li class="nav-header hidden-tablet"> Leave</li>
                    <li><a class="ajax-link" href="{!! URL::to('company/all-leave') !!}"><i class="icon-list"></i><span class="hidden-tablet"> All Leave</span></a></li>
                    <li><a class="ajax-link" href="{!! URL::to('company/leave-category') !!}"><i class="icon-th"></i><span class="hidden-tablet"> Leave Catagory</span></a></li>
                    <li class="nav-header hidden-tablet"> Notice Board</li>
                    <li><a class="ajax-link" href="{!! URL::to('company/notice-board/create') !!}"><i class="icon-list"></i><span class="hidden-tablet"> Create Notice</span></a></li>
                    <li><a class="ajax-link" href="{!! URL::to('company/notice-board') !!}"><i class="icon-list"></i><span class="hidden-tablet"> Notice Board</span></a></li>
                    <li class="nav-header hidden-tablet"> Designation</li>
                    <li><a class="ajax-link" href="{!! URL::to('company/designation') !!}"><i class="icon-list"></i><span class="hidden-tablet"> Designation</span></a></li>
                    <li class="nav-header hidden-tablet"> Report</li>
                    <li><a class="ajax-link" href="{!! URL::to('company/full-calender') !!}"><i class="icon-list"></i><span class="hidden-tablet"> Full Calender Report</span></a></li>
                    <li><a class="ajax-link" href="{!! URL::to('company/table-report') !!}"><i class="icon-list"></i><span class="hidden-tablet"> Table Report Individual</span></a></li>
                    <li><a class="ajax-link" href="{!! URL::to('company/report-summery') !!}"><i class="icon-list"></i><span class="hidden-tablet"> Summery Report</span></a></li>
                    <li><a class="ajax-link" href="{!! URL::to('company/chat') !!}"><i class="icon-list"></i><span class="hidden-tablet"> Chat</span></a></li>
                </ul>
                <label id="for-is-ajax" class="hidden-tablet" for="is-ajax"></label>
            </div><!--/.well -->
        </div><!--/span-->
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block span10">
                <h4 class="alert-heading">Warning!</h4>
                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
            </div>
        </noscript>

        <div id="content" class="span10">
            @yield('content')

        </div><!--/row-->
        <!-- content ends -->
    </div><!--/#content.span10-->
</div><!--/fluid-row-->
<hr>
<footer>

</footer>
</div><!--/.fluid-container-->
<!-- external javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
{!! HTML::script('js/jquery-ui.js') !!}
{!! HTML::script('js/jquery-ui-timepicker.js') !!}
{!! HTML::script('js/bootstrap-modal.js') !!}
{!! HTML::script('js/charisma/js/bootstrap-dropdown.js') !!}
{!! HTML::script('js/charisma/js/bootstrap-tab.js') !!}
{!! HTML::script('js/charisma/js/bootstrap-tooltip.js') !!}
{!! HTML::script('js/charisma/js/bootstrap-popover.js') !!}
{!! HTML::script('js/charisma/js/jquery.cookie.js') !!}
{{--{!! HTML::script('js/charisma/js/jquery.chosen.min.js') !!}--}}
{!! HTML::script('js/charisma/js/jquery.uniform.min.js') !!}
{!! HTML::script('js/charisma/js/jquery.colorbox.min.js') !!}
{!! HTML::script('js/charisma/js/jquery.cleditor.min.js') !!}
{!! HTML::script('js/charisma/js/jquery.noty.js') !!}
{!! HTML::script('js/charisma/js/jquery.elfinder.min.js') !!}
{!! HTML::script('js/charisma/js/jquery.raty.min.js') !!}
{!! HTML::script('js/charisma/js/jquery.iphone.toggle.js' )!!}
{!! HTML::script('js/charisma/js/jquery.autogrow-textarea.js') !!}
{!! HTML::script('js/charisma/js/jquery.uploadify-3.1.min.js') !!}
{!! HTML::script('js/charisma/js/jquery.history.js') !!}
{{--{!! HTML::script('js/charisma/js/charisma.js') !!}--}}

<script>
    $(function() {
    @if(Session::has('success'))
        $.pnotify({
            title: 'Success',
            text: "{!! Session::get('success') !!}",
            type: 'success',
            delay: 3000
        });
        @elseif(Session::has('error'))
        $.pnotify({
            title: 'ERROR',
            text: "{!! Session::get('error') !!}",
            type: 'error',
            delay: 3000
        });
        @endif
    });
</script>
</body>
</html>