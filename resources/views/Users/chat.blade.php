@extends('Users/UserLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('user') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="{!! URL::to('user/chat') !!}">Chat</a>
            </li>
        </ul>
    </div>
    <div>

        <div class="ui-254" ng-app="myApp" ng-controller="chatController">
            <!-- Profile window -->
            <div class="ui-window">
                <div class="row-fluid">
                    <div class="span4">
                        <!-- Contact Heading -->
                        <h3><i class="fa fa-user lblue"></i>&nbsp; All Contacts</h3>
                        <!-- Profile user chat contacts -->
                        <div class="chat-contact ">
                            <!-- Chat contact member -->
                            @foreach($sorted_user as $key=>$user)
                                <a href="#{{ $user->id }}Message" data-toggle="tab" ng-click="messageCheck(<?php echo $user->id?>)">
                                    <div class="chat-member @if((reset($sorted_user)->id == $user->id)) active @endif">
                                        <h4>{kp UserLists[<?php echo $user->id?>].username kp}
                                        <button ng-if="UserLists[<?php echo $user->id?>].unread" class="btn btn-round btn-red">{kp UserLists[<?php echo $user->id?>].unread kp}</button>
                                        </h4>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="span8 tab-content">
                        <h3><i class="fa fa-comments-o lblue"></i>&nbsp; Live Chat</h3>
                        <!-- Profile chat content -->
                        <div class="tab-pane {kp key == <?php if(isset(reset($sorted_user)->id)) echo reset($sorted_user)->id ?> ? 'active' : '' kp}" id="{kp key kp}Message" ng-repeat="(key,User) in Usermessages">
                        <div class="chat-content " id="{kp key kp}">
                                <div class="messages" ng-repeat="message in User">
                                    <div class="chat-box chat-in" ng-if="<?php echo Auth::user()->id ?> != message.sender_id" >
                                        <!-- image -->
                                        <div class="img-container">
                                            {kp message.user.username kp}
                                        </div>
                                        <div class="message">
                                            <h5><i class="fa fa-clock-o"></i>&nbsp;
                                                {kp message.created_at | asDate | date:'medium' kp}
                                            </h5>
                                            <p>
                                                {kp message.message kp}
                                            </p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="chat-box chat-out" ng-if="<?php echo Auth::user()->id ?> == message.sender_id">
                                        <!-- image -->
                                        <div class="img-container">
                                            {kp message.user.username kp}
                                        </div>
                                        <div class="message">
                                            <!-- Name -->
                                            <h5><i class="fa fa-clock-o"></i>&nbsp; {kp message.created_at | asDate | date:'medium' kp}</h5>
                                            <p>{kp message.message kp}</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                        </div>
                        {!! Form::open(array('class' => 'messageSave', 'accept-charset' => 'utf-8',  'ng-submit'=>'messageSave($event)')) !!}
                        <div class="chat-input-box">
                            <div class="input-group">
                                <input type="text" required=""  class="form-control messageWrite" name="message" placeholder="Type your message" ng-click="messageAsRead(key)">
                                <input type="hidden" class="form-control" name="receiver_id" value="{kp key kp}">
                                <input type="hidden" class="form-control" name="sender_id" value="{!! Auth::user()->id !!}">
                                            <span class="input-group-btn">
                                                <button class="btn btn-lblue" type="submit">Send</button>
                                            </span>
                            </div>
                        </div>
                        {!! Form::close(); !!}
                        </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        if(isset(reset($sorted_user)->id))
            $arr[reset($sorted_user)->id] = json_decode($active_message);
    ?>
@endsection
@section('cssTop')
    <style>
        .btn-round{
            border-radius: 40px !important;
            -webkit-border-radius: 40px;
            -moz-border-radius: 40px;
            font-size:12px;
            padding-top:4px;
        }
    </style>
    {!! HTML::style('css/style-254.css') !!}
@endsection
@section('jsBottom')
    {!! HTML::script('js/placeholder.js') !!}
    {!! HTML::script('js/jquery.nicescroll.min.js') !!}
    {!! HTML::script('js/respond.min.js') !!}
    {!! HTML::script('js/pusher.min.js') !!}
    <script>
        $(document).ready(function () {
            $(".chat-member").click(function(){
                $(".chat-member").removeClass('active');
                $(this).addClass('active');
            });
            $(".chat-content").click(function(){
                $(".chat-content").removeClass('active');
                $(this).addClass('active');
            });


            $(".chat-contact").niceScroll({
                cursorcolor: "#999",
                cursoropacitymin: 0,
                cursoropacitymax: 0.3,
                cursorwidth: 5,
                cursorborder: "0px",
                cursorborderradius: "0px",
                cursorminheight: 50,
                zindex: 1,
                mousescrollstep: 20
            });

            $(".chat-content").niceScroll({
                cursorcolor: "#999",
                cursoropacitymin: 0,
                cursoropacitymax: 0.3,
                cursorwidth: 5,
                cursorborder: "0px",
                cursorborderradius: "0px",
                cursorminheight: 50,
                zindex: 1,
                mousescrollstep: 20
            });
            $(".chat-content").animate({ scrollTop: $(document).height()-$(window).height() }, "slow");

            $(".chat-content").bind("scroll", function() {
                var UserMessage = $('[ng-controller="chatController"]').scope().Usermessages;
                if($(this).scrollTop() == 0){
                    var currentId = $(this).attr('id');
                    var xVals = UserMessage[currentId].map(function(obj) { return obj.id; });
                    var min = Math.min.apply(null, xVals);
                    $.ajax({
                        url: "{!! URL::to('user/message-more') !!}",
                        type: "POST",
                        data: { 'userId' : currentId, 'minRow': min, _token: "<?php echo csrf_token() ?>" },
                        cache: false,
                        success: function(response) {
                            for (var key in response) {
                                if (response.hasOwnProperty(key)) {
                                    var obj = response[key];
                                    $('[ng-controller="chatController"]').scope().$apply(function () {
                                        $('[ng-controller="chatController"]').scope().Usermessages[currentId].unshift(obj);
                                    });

                                }
                            }
                        }
                    });
                }
            });

        });

        var myApp = angular.module('myApp', [], function ($interpolateProvider) {
            $interpolateProvider.startSymbol('{kp');
            $interpolateProvider.endSymbol('kp}');
        });

        myApp.filter("asDate", function () {
                    return function (input) {
                        input = input.replace(/-/g,'/');
                        return new Date(input);
                    }
                });

        myApp.filter('checkUnreadMessage', function() {
            return function(collection, id) {
                var i=0, len=collection[id].length;
                for (; i<len; i++) {
                    if (collection[id][i]['read'] == +0) {
                        return 1;
                    }
                }
                return null;
            }
        });
        myApp.controller('chatController', function ($scope,$http,$filter) {
            $scope.UserLists = {};
            <?php if(isset(reset($sorted_user)->id)){?>
            $scope.Usermessages = <?php echo json_encode($arr) ?>;
            $scope.Usermessages[<?php echo reset($sorted_user)->id ?>].reverse();
            <?php }?>
            <?php
            foreach ($sorted_user as $key => $user) {
              if ($key < 1) continue; ?>
            $scope.Usermessages[<?php echo $user->id ?>] = [];
            $scope.Usermessages[<?php echo $user->id ?>].reverse();
           <?php }
           foreach ($sorted_user as $key => $user) { ?>
            $scope.UserLists["<?php echo $user->id ?>"] = <?php echo json_encode($user)?>;
            <?php if(isset($user->total_read) && $user->total_read != null && $user->total_read < $user->total_messages){ ?>
            $scope.UserLists["<?php echo $user->id ?>"].unread = 1;
            <?php }?>
            /*var found = $filter('checkUnreadMessage')($scope.Usermessages,<?php echo $user->id ?>);
            if( found ==1 ){
                $scope.UserLists["<?php echo $user->id ?>"].unread = 1;
            }*/
           <?php }
            ?>
//            console.log($scope.Usermessages);
            $scope.messageSave = function(event){
                event.preventDefault();
                    var req = {
                        method: 'POST',
                        url: "{!! URL::to('user/message-save') !!}",
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        data: $(event.target).serialize()
                    };

                    $http(req).success(function(response){
                        $(".messageWrite").val('');
                    });
            };

            $scope.messageCheck = function (id) {
                if ($scope.Usermessages[id].length == 0) {
                    var req = {
                        method: 'GET',
                        url: '{!! URL::to("user/check-message/") !!}/' + id,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        data: ''
                    };
                    $http(req).success(function (response) {
                        for (var key in response) {
                            if (response.hasOwnProperty(key)) {
                                var obj = response[key];
                                $scope.Usermessages[id].push(obj);
                            }
                        }
                        $scope.Usermessages[id].reverse();
                        $scope.UserLists[id].unread = 0;
                        $(".chat-content").animate({ scrollTop: 100}, "slow");
                    });
                }else{
                    var req = {
                        method: 'GET',
                        url: '{!! URL::to("user/message-mark-read/") !!}/' + id,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        data: ''
                    };
                    $http(req).success(function (response) {
                        $scope.UserLists[id].unread = 0;
                    });
                }
            };
            $scope.messageAsRead = function (id) {
                var req = {
                    method: 'GET',
                    url: '{!! URL::to("user/message-mark-read/") !!}/' + id,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    data: ''
                };
                $http(req).success(function (response) {
                    $scope.UserLists[id].unread = 0;
                });
            };

            Pusher.log = function (message) {
                if (window.console && window.console.log) {
                    window.console.log(message);
                }
            };
            $scope.pusher = new Pusher('d5269cbeecc044ac62cb');
            $scope.channel = $scope.pusher.subscribe('messageAction');
            $scope.channel.bind("App\\Events\\MessageCreated", function (data) {
                $scope.$apply(function () {
                    if(data.response.sender_id == <?php echo Auth::user()->id?>){
                        $scope.Usermessages[data.response.receiver_id].push(data.response);
                    }
                    if(data.response.receiver_id == <?php echo Auth::user()->id?>){
                        $scope.Usermessages[data.response.sender_id].push(data.response);
                        $scope.UserLists[data.response.sender_id].unread = 1;
                    }
                });
            });
        });

    </script>
@endsection