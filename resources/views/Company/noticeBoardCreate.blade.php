@extends('Company.CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="{!! URL::to('company/notice-board/create') !!}">Notice Board</a>
            </li>
        </ul>
    </div>
<div ng-app="myApp" ng-controller="noticeController">
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> Notice Board</h2>

            </div>
            <div class="box-content">
                    {!! Form::open(array('id' => 'notice', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal',  'ng-submit'=>'save($event)')) !!}
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="catagory">Subject</label>
                            <div class="controls">
                                <input type="text" style="width: 50%" required class="input-xlarge input" name="subject" id="subject" placeholder="Notice Subject">
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="catagory_num">Message</label>
                            <div class="controls">
                                <textarea cols="80" rows="10" tabindex="1"  required class="input-xlarge ckeditor" name="message" id="message" placeholder="Notice Message">
                                    </textarea>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button  type="submit"  class="btn btn-success">Create</button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>

            </div>
        </div><!--/span-->

    </div>
    </div>
@endsection

@section('jsBottom')
    {!! HTML::script('js/ckeditor.js') !!}
<script>
    var myApp = angular.module('myApp', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('{kp');
        $interpolateProvider.endSymbol('kp}');
    });
    myApp.controller('noticeController',function($scope,$http){
        $scope.save = function(event){
            event.preventDefault();
            for ( instance in CKEDITOR.instances )
                CKEDITOR.instances[instance].updateElement();
                var req = {
                    method: 'POST',
                    url: "{!! URL::to('company/notice-board') !!}",
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: $("#notice").serialize()
                };

                $http(req).success(function(response){
                    if(response.type == 'error'){
                        $.pnotify({
                            title: 'ERROR',
                            text: response.info,
                            type: 'error',
                            delay: 3000
                        });
                    }
                    else{
                        window.location.href = "{!! URL::to('company/notice-board') !!}";
                    }
                });
        };
    });
</script>
@endsection