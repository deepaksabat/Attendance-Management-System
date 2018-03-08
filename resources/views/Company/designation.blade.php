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
                <h2><i class="icon-edit"></i> Create Designation</h2>

            </div>
            <div class="box-content">
                    {!! Form::open(array('id' => 'designation', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal',  'ng-submit'=>'save($event)')) !!}
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="catagory">Designation Name</label>
                            <div class="controls">
                                <input type="text" ng-model="designation.name" required class="input-xlarge input" name="name" id="designation_name" placeholder="Designation Name">
                            <span style="margin-left: 5%;color:red" ng-if="checkExist()">{kp checkExist() kp}</span>
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


    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-user"></i> Designation List</h2>

            </div>
            <div class="box-content" id="ajax_table">
                <table class="table table-striped table-bordered bootstrap-datatable datatable" ng-controller="deleteController">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>designation</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="designationAjax">
                    <?php
                    if($designations){
                    foreach($designations as $key=>$designation): ?>
                    <tr class="list" id="row_<?php echo $designation->id ?>">
                        <td><?php echo $key+1?></td>
                        <td class="center"><?php echo $designation->name?></td>
                        <td class="center">
                            <a class="btn btn-danger" ng-click="delete(<?php echo $designation->id ?>)" id="<?php echo $designation->id ?>" >
                                <i class="icon-white icon-trash"></i>Delete</a>
                            <a class="btn btn-success" href='{!! URL::to("company/designation/$designation->id/edit") !!}' >
                                <i class="icon-white icon-edit"></i>Update</a>

                        </td>
                    </tr>
                    <?php endforeach;
                    }?>

                    <tr  ng-repeat="designation in designations" id="row_{kp designation.id kp}">
                        <td>
                            {kp designation.number kp}
                        </td>
                        <td>
                            {kp designation.name kp}
                        </td>

                        <td class="center">
                            <a class="btn btn-danger" ng-click="delete(designation.id)" id="{kp designation.id kp}" >
                                <i class="icon-white icon-trash"></i>Delete</a>
                            <a class="btn btn-success" href='{!! URL::to("company/designation/{kp designation.id kp}/edit") !!}' >
                                <i class="icon-white icon-edit"></i>Update</a>
                        </td>
                    </tr>
                    <tr ng-if="designation.name">
                        <td>
                            {kp designation.number kp}
                        </td>
                        <td>
                            {kp designation.name kp}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <ul class="pagination">
                    {!! str_replace('/?', '?', $designations->render()) !!}
                </ul>
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

    myApp.controller('deleteController',function($scope,$http){
        $scope.delete = function(id) {
            var req = {
                method: 'DELETE',
                url: '{!! URL::to("company/designation/") !!}/' + id,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: ''
            };
            var chk = confirm("Are you sure to delete this?");
            if (chk)
            {
            $http(req).success(function (response) {
                console.log(response);
                if (response == 'true') {
                    $("#row_" + id).html('');
                    $.pnotify({
                        title: 'Success',
                        text: 'Designation Deleted',
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

    myApp.controller('designationController',function($scope,$http){
        $scope.designations = [];

        $scope.list = [<?php  foreach($designations as $designation){ ?>
            '<?php echo $designation->name;?>',
            <?php }?>
        ];

        $scope.designation = {};
        $scope.designation.number = $("tr").length ;
        $scope.checkExist = function(){
            if($scope.list.indexOf($scope.designation.name) > -1){
                return 'Already Exist This designation';
            }
        };

        $scope.save = function(event){
            event.preventDefault();
            if($scope.list.indexOf($scope.designation.name) > -1){
                $scope.checkExist = function(){
                    if($scope.list.indexOf($scope.designation.name) > -1){
                        return 'Already Exist This designation';
                    }
                };
            }else{
                var req = {
                    method: 'POST',
                    url: "{!! URL::to('company/designation') !!}",
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: $("#designation").serialize()
                };

                $http(req).success(function(response){
//                    console.log(response);
                    if(response.type == 'error'){
                        $.pnotify({
                            title: 'ERROR',
                            text: response.info,
                            type: 'error',
                            delay: 3000
                        });
                    }
                    else{
                        $.pnotify({
                            title: 'Message',
                            text: response.info,
                            type: 'success',
                            delay: 3000
                        });
                        $scope.designation.id = response.id;
                        $scope.designations.push($scope.designation);
                        $scope.list.push($scope.designation.name);
                        var oldNumber = $scope.designation.number;
                        $scope.designation = {};
                        $scope.designation.number = +oldNumber + +1;
                    }
                });
            }
        };
    });
</script>
@endsection