<div ng-app="ngApp" ng-cloak="" ng-controller="ngAppController" ng-init="init()">
    <div class="title">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>Subscriber lists</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-default btn-success" href="" ng-href="{{edit(0)}}"><i class="fa fa-plus-circle"></i> New list</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="box">
            <div angular-minute-search="ar_lists" title="Subscriber target lists" placeholder="Search Subscriber target lists.."></div>

            <table class="table table-striped graylinks" angular-minute-table="">
                <tr ng-repeat="ar_list in ar_lists" ng-link="{{edit(ar_list.getPKValue())}}">
                    <td ng-field="ar_list_id" ng-title="Id">{{ar_list.ar_list_id}}</td>
                    <td ng-field="name">{{ar_list.name}}</td>
                    <td ng-field="created_at">{{ar_list.created_at | timeAgo}}</td>
                    <td ng-title="Messages">{{ar_list.sqls.getTotalItems() || 'Not set'}}</td>

                    <td>
                        <div class="btn-group">
                            <a href="" ng-href="{{edit(ar_list.getPKValue())}}" class="btn btn-default btn-xs">Edit..</a>
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="" ng-href="{{download(ar_list.getPKValue())}}"><i class="fa fa-download"></i> Export list to CSV</a></li>
                                <li class="divider"></li>
                                <li><a href="" ng-click="ar_list.removeConfirm('Are you sure?', 'Item removed')"><i class="fa fa-trash"></i> Remove..</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>

            <div angular-minute-pager="ar_lists"></div>

        </div>
    </div>
</div>

<script>
    angular.module('ngApp', ['minutephp', 'angularMinuteTable', 'angularMinuteSearch', 'angularMinutePager'])
        .config(['$sessionProvider', function ($sessionProvider) {
            $sessionProvider.load(<?= $this->getSessionData() ?>);
        }])
        .controller('ngAppController', function ($scope, $minutephp) {
            $scope.extend(__ar_lists__);

            $scope.edit = function (id) {
                return '/admin/autoresponder/lists/edit/' + id;
            };

            $scope.download = function (id) {
                return '/admin/autoresponder/lists/download/' + id;
            };
        });
</script>

