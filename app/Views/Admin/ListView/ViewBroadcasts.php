<div ng-app="ngApp" ng-controller="ngAppController" ng-init="init()">
    <div class="title">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>All Broadcasts</h2>
                </div>
                <div class="pull-right">
                    <button class="btn btn-lg btn-success" ng-click="edit(0)"><i class="fa fa-plus-circle"></i> New broadcast</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="box">
            <table datatable="ng" class="table row-border hover graylinks">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Send date</th>
                    <th>List</th>
                    <th>Mail</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr class="text-links" ng-repeat="broadcast in broadcasts" ng-click="edit(broadcast.getPKValue())">
                    <td>{{broadcast.ar_broadcast_id}}</td>
                    <td data-order="{{broadcast.send_at}}">{{broadcast.send_at | timeAgo}}</td>
                    <td>{{broadcast.list.description || broadcast.list.name}}</td>
                    <td>{{broadcast.mail.description || broadcast.mail.name}}</td>
                    <td>{{broadcast.status | ucfirst}}</td>
                    <td class="unclickable">
                        <div class="dropdown" ng-click="$event.stopPropagation();" onclick="$(this).find('.dropdown-menu').dropdown('toggle');">
                            <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-xs btn-default">
                                <i class="fa fa-cog"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dLabel">
                                <li><a href="#" ng-click="broadcast.removeConfirm('Removed')"><i class="fa fa-trash"></i> Delete</a></li>
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
    angular.module('ngApp', ['minutephp', 'angularStringFilters', 'angularTimeAgo', 'datatables'])
        .config(['$sessionProvider', function ($sessionProvider) {
            $sessionProvider.load(<?= $this->getSessionData() ?>);
        }])
        .controller('ngAppController', function ($scope, $minutephp) {
            $minutephp.import($scope, <?= $broadcasts ?>);

            $scope.edit = function (id) {
                top.location.href = $scope.session.admin + '/autoresponder/broadcast/edit/' + id
            };
        });
</script>

