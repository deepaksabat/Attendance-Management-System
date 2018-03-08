@extends('Company.CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="{!! URL::to('company/all-user') !!}">Total User</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid sortable">
        <div class="span12">
           <div class="span2">
               <a href="{!! URL::to('company/create-user') !!}" class="btn btn-large btn-success" style="width: 100%"><i class="icon-plus icon-white" ></i> Create User</a>
           </div>
        </div>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-user"></i> Users</h2>

            </div>
            <div class="span12" style="margin-top: 10px;margin-left: 10px">
                <input class="form-control col-md-4" type="text" id="search" placeholder="Search" autocomplete="off">
            </div>
            <div class="box-content" id="tableData">
                {!! $userTable !!}
            </div>
        </div><!--/span-->
    </div>
    <script>
        $(function(){

            $("#search").keyup(function(event){

                var values = "search="+$("#search").val();

                $.ajax({
                    url: "{{ URL::to('company/search-user') }}",
                    type: "GET",
                    data: values,
                    cache: false,
                    beforeSend: function(){
                        $('#tableData').html('<img src="{{ URL::to('images/loader_gif.gif') }}" style="height: 100px;margin-left: 100px;">');
                    },
                    success: function(data){
                        $('#tableData').html(data);

                    }
                }); //end ajax
            });
        });
    </script>
    <?php $punch_message_success=Session::get('flashSuccess');; if ($punch_message_success) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.pnotify({
                title: 'Message',
                text: '<?php echo $punch_message_success ?>',
                type: 'success',
                delay: 3000

            });
        });
    </script>

    <?php } ?>

@endsection
@section('jsBottom')
    {!! HTML::script('js/charisma/js/jquery.dataTables.min.js') !!}
@endsection

