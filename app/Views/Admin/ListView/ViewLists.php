<div ng-app="ngApp" ng-controller="ngAppController" ng-init="init()">
    <div class="title">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>Subscriber lists</h2>
                </div>
                <div class="pull-right">
                    <button class="btn btn-lg btn-success" ng-click="edit(0)"><i class="fa fa-plus-circle"></i> Create new list</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="box">
            <table datatable="ng" class="table row-border hover graylinks">
                <thead>
                <tr>
                    <th>ID#</th>
                    <th>Name</th>
                    <th>Created</th>
                    <th>SQL conditions</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr class="text-links" ng-repeat="ar_list in ar_lists" ng-click="edit(ar_list.getPKValue())">
                    <td>{{ar_list.ar_list_id}}</td>
                    <td>{{ar_list.name}}<small class="text-small text-muted" ng-if="!!ar_list.description"><br />{{ar_list.description}}</small></td>
                    <td class="text-capitalize">{{ar_list.created_at | timeAgo}}</td>
                    <td>{{ar_list.sqls.getTotalItems() || 'Not set'}}</td>
                    <td class="unclickable">
                        <div class="dropdown" ng-click="$event.stopPropagation();" onclick="$(this).find('.dropdown-menu').dropdown('toggle');">
                            <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-xs btn-default">
                                <i class="fa fa-cog"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dLabel">
                                <li><a href="#" ng-click="download(ar_list.getPKValue())"><i class="fa fa-download"></i> Export list to CSV</a></li>
                                <li class="divider"></li>
                                <li><a href="#" ng-click="ar_list.removeConfirm()"><i class="fa fa-trash"></i> Delete</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>

<script>
    angular.module('ngApp', ['minutephp', 'angularTimeAgo', 'datatables'])
        .config(['$sessionProvider', function ($sessionProvider) {
            $sessionProvider.load(<?= $this->getSessionData() ?>);
        }])
        .controller('ngAppController', function ($scope, $minutephp) {
            $minutephp.import($scope, <?= $ar_lists ?>);

            $scope.edit = function (id) {
                top.location.href = $scope.session.admin + '/autoresponder/lists/edit/' + id;
            };

            $scope.download = function (id) {
                top.location.href = $scope.session.admin + '/autoresponder/lists/download/' + id;
            };
        });
</script>

