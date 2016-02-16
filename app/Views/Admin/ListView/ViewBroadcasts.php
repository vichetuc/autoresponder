<div ng-app="ngApp" ng-cloak="" ng-controller="ngAppController" ng-init="init()">
    <div class="title">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>E-mail Broadcasts</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-default btn-success" href="" ng-href="{{edit(0)}}"><i class="fa fa-plus-circle"></i> New broadcast</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="box">
            <div angular-minute-search="broadcasts" title="Broadcast list" placeholder="Search Broadcast list.."></div>

            <table class="table table-striped graylinks" angular-minute-table="">
                <tr ng-repeat="broadcast in broadcasts" ng-link="{{edit(broadcast.getPKValue())}}">
                    <td ng-field="ar_broadcast_id" ng-title="Id">{{broadcast.ar_broadcast_id}}</td>
                    <td ng-field="send_at" ng-title="">{{broadcast.send_at | timeAgo}}</td>
                    <td ng-field="list.description" ng-title="List">{{broadcast.list.description}}</td>
                    <td ng-field="mail.description" ng-title="Mail">{{broadcast.mail.description}}</td>
                    <td ng-field="status" ng-title="">{{broadcast.status}}</td>

                    <td>
                        <div class="btn-group">
                            <a href="" ng-href="{{edit(broadcast.getPKValue())}}" class="btn btn-default btn-xs">Edit..</a>
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="" ng-click="broadcast.removeConfirm('Are you sure?', 'Removed')"><i class="fa fa-trash"></i> Remove..</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>

            <div angular-minute-pager="broadcasts"></div>

        </div>
    </div>
</div>

<script>
    angular.module('ngApp', ['minutephp', 'angularMinuteTable', 'angularMinuteSearch', 'angularMinutePager'])
        .controller('ngAppController', function ($scope, $minutephp) {
            $scope.extend(__broadcasts__);

            $scope.edit = function (id) {
                return '/admin/autoresponder/broadcast/edit/' + id;
            };
        });
</script>

