@extends('Company.CompanyLayout')

@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="{!! URL::to('company/notice-board') !!}">Notice Board</a>
            </li>
        </ul>
    </div>
    <div ng-app="myApp" ng-controller="deleteController">
        <?php
        if($allNotice){
        foreach($allNotice as $key=>$notice): ?>
        <div class="row-fluid sortable" id="row_{!! $notice->id !!}">
            <div class="box span12">
                <div class="box-header well" data-original-title>
                    <h2><i class="icon icon-notice"></i> <?php echo $notice->subject?></h2>
                    @if(Auth::user()->user_label ==1 )
                    <div class="box-icon">
                        <a href='{!! URL::to("company/notice-board/$notice->id/edit") !!}' class="btn btn-minimize btn-round"><i class="icon-edit"></i></a>
                        <a href="#" ng-click="delete(<?php echo $notice->id ?>, $event)" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
                    </div>
                        @endif
                </div>
                <div class="box-content" id="ajax_table">
                    <?php echo $notice->message?>
                </div>
            </div><!--/span-->

        </div>
        <?php endforeach;}?>
    </div>
    <ul class="pagination">
        {!! str_replace('/?', '?', $allNotice->render()) !!}
    </ul>
@endsection

@section('jsBottom')
    <script>
        var myApp = angular.module('myApp', [], function($interpolateProvider) {
            $interpolateProvider.startSymbol('{kp');
            $interpolateProvider.endSymbol('kp}');
        });

        myApp.controller('deleteController',function($scope,$http){
            $scope.delete = function(id, event) {
                event.preventDefault();
                var req = {
                    method: 'DELETE',
                    url: '{!! URL::to("company/notice/") !!}/' + id,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: ''
                };
                var chk = confirm("Are you sure to delete this?");
                if (chk)
                {
                    $http(req).success(function (response) {
                        if (response == 'true') {
                            $("#row_" + id).html('');
                            $.pnotify({
                                title: 'Success',
                                text: 'Notice Deleted',
                                type: 'success',
                                delay: 3000
                            });
                        } else {
                            $.pnotify({
                                title: 'ERROR',
                                text: response,
                                type: 'error',
                                delay: 3000
                            });
                        }
                    });
                }
            };
        });
    </script>
@endsection
