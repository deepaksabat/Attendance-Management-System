@extends('Company.CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="{!! URL::to('company/designation') !!}">Designation</a>
            </li>
        </ul>
    </div>
<div ng-app="myApp" ng-controller="designationController">
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> Update Designation</h2>

            </div>
            <div class="box-content">
                    {!! Form::open(array('id' => 'designation', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal',  'ng-submit'=>'save($event)')) !!}
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="catagory">Designation Name</label>
                            <div class="controls">
                                <input type="text" required class="input-xlarge input" name="name" id="designation_name" value="{{ $designation->name }}">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button  type="submit"  class="btn btn-success">Update</button>
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
<script>
    var myApp = angular.module('myApp', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('{kp');
        $interpolateProvider.endSymbol('kp}');
    });
    myApp.controller('designationController',function($scope,$http){
        $scope.save = function(event){
            event.preventDefault();
                var req = {
                    method: 'PUT',
                    url: '{!! URL::to("company/designation/$designation->id") !!}',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: $("#designation").serialize()
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
                        window.location.href = "{!! URL::to('company/designation') !!}";
                    }
                });
        };
    });
</script>
@endsection