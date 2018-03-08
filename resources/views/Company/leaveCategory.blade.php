@extends('Company.CompanyLayout')
@section('content')
    <div>
        <ul class="breadcrumb">
            <li>
                <a href="{!! URL::to('Company') !!}">Home</a> <span class="divider">/</span>
            </li>
            <li>
                <a href="{!! URL::to('Company/leave-category') !!}">Leave Catagory</a>
            </li>
        </ul>
    </div>
<div ng-app="myApp" ng-controller="leaveController">
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i> Create Category</h2>

            </div>
            <div class="box-content">
                    {!! Form::open(array('id' => 'category', 'accept-charset' => 'utf-8', 'class' => 'form-horizontal',  'ng-submit'=>'save($event)')) !!}
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label" for="catagory">Category Name</label>
                            <div class="controls">
                                <input type="text" ng-model="category.name" required class="input-xlarge input" name="category" id="category_name" placeholder="Catagory Name">
                            <span style="margin-left: 5%;color:red" ng-if="checkExist()">{kp checkExist() kp}</span>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label" for="catagory_num">Maximum in a Year</label>
                            <div class="controls">
                                <input type="text" ng-model="category.max" required class="input-xlarge number" name="category_num" id="category_num" placeholder="Maximum in a Year">
                                {{--<input type="hidden" name="extraAngular">--}}
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
                <h2><i class="icon-user"></i> Category List</h2>

            </div>
            <div class="box-content" id="ajax_table">
                <table class="table table-striped table-bordered bootstrap-datatable datatable" ng-controller="deleteController">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Category</th>
                        <th>Maximum Number in Year</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="categoryAjax">
                    <?php
                    if($allCategory){
                    foreach($allCategory as $key=>$category): ?>
                    <tr class="list" id="row_<?php echo $category->id ?>">
                        <td><?php echo $key+1?></td>
                        <td class="center"><?php echo $category->category?></td>
                        <td class="center"><?php echo $category->category_num?></td>
                        <td class="center">
                            <a class="btn btn-danger" ng-click="delete(<?php echo $category->id ?>)" id="<?php echo $category->id ?>" >
                                <i class="icon-white icon-trash"></i>Delete</a>

                        </td>
                    </tr>
                    <?php endforeach;
                    }else{?>
                    <tr>
                        <td>
                            No data are availables
                        </td>
                        <td>
                            No data are availables
                        </td>
                        <td>
                            No data are availables
                        </td>
                        <td>
                            No data are availables
                        </td>
                    </tr>
                    <?php   }
                    ?>
                    <tr  ng-repeat="category in categories" id="row_{kp category.id kp}">
                        <td>
                            {kp category.number kp}
                        </td>
                        <td>
                            {kp category.name kp}
                        </td>
                        <td>
                            {kp category.max kp}
                        </td>
                        <td class="center">
                            <a class="btn btn-danger" ng-click="delete(category.id)" id="{kp category.id kp}" >
                                <i class="icon-white icon-trash"></i>Delete</a>
                        </td>
                    </tr>
                    <tr ng-if="category.name">
                        <td>
                            {kp category.number kp}
                        </td>
                        <td>
                            {kp category.name kp}
                        </td>
                        <td>
                            {kp category.max kp}
                        </td>
                    </tr>
                    </tbody>
                </table>
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
                method: 'GET',
                url: '{!! URL::to("company/delete-leave-category/") !!}/' + id,
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
                        text: 'Leave Category Deleted',
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

    myApp.controller('leaveController',function($scope,$http){
        $scope.categories = [];

        $scope.list = [<?php  foreach($allCategory as $category){ ?>
            '<?php echo $category->category;?>',
            <?php }?>
        ];

        $scope.category = {};
        $scope.category.number = $("tr").length ;
        $scope.checkExist = function(){
            if($scope.list.indexOf($scope.category.name) > -1){
                return 'Already Exist This Category';
            }
        };

        $scope.save = function(event){
            event.preventDefault();
            if($scope.list.indexOf($scope.category.name) > -1){
                $scope.checkExist = function(){
                    if($scope.list.indexOf($scope.category.name) > -1){
                        return 'Already Exist This Category';
                    }
                };
            }else{
                var req = {
                    method: 'POST',
                    url: "{!! URL::to('company/leave-category') !!}",
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    data: $("#category").serialize()
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
                        $.pnotify({
                            title: 'Message',
                            text: response.info,
                            type: 'success',
                            delay: 3000
                        });
                        $scope.category.id = response.id;
                        $scope.categories.push($scope.category);
                        $scope.list.push($scope.category.name);
                        var oldNumber = $scope.category.number;
                        $scope.category = {};
                        $scope.category.number = +oldNumber + +1;
                    }
                });
            }
        };
    });
</script>
@endsection