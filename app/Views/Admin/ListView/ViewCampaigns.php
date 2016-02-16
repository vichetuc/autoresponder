<div ng-app="ngApp" ng-cloak="" ng-controller="ngAppController" ng-init="init()">
    <div class="title">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>Autoresponder campaigns</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-default btn-success" href="" ng-href="{{edit(0)}}"><i class="fa fa-plus-circle"></i> New campaign</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="box">
            <div angular-minute-search="ar_campaigns" title="Campaign list" placeholder="Search campaigns.."></div>

            <table class="table table-striped graylinks" angular-minute-table="">
                <tr ng-repeat="ar_campaign in ar_campaigns" ng-link="{{edit(ar_campaign.ar_campaign_id)}}">
                    <td ng-field="ar_campaign_id" ng-title="Id">{{ar_campaign.ar_campaign_id}}</td>
                    <td ng-field="name">{{ar_campaign.name}}</td>
                    <td ng-field="created_at">{{ar_campaign.created_at | timeAgo}}</td>
                    <td ng-title="List name">{{ar_campaign.ar_lists.name || 'None'}}</td>
                    <td ng-title="Total messages">{{ar_campaign.ar_messages.getTotalItems() || 'None'}}</td>
                    <td ng-field="enabled"><a href="//google.com">{{ar_campaign.enabled === 'y' && 'Yes' || 'No'}}</a></td>
                    <td>
                        <div class="btn-group">
                            <a href="" ng-href="{{edit(ar_campaign.ar_campaign_id)}}" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Edit..</a>
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="" ng-click="ar_campaign.removeConfirm('Are you sure?', 'Item removed', false, true)"><i class="fa fa-trash"></i> Remove..</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>

            <div angular-minute-pager="ar_campaigns"></div>
        </div>
    </div>
</div>

<script>
    angular.module('ngApp', ['minutephp', 'angularMinuteTable', 'angularMinuteSearch', 'angularMinutePager'])
        .controller('ngAppController', function ($scope, $minutephp) {
            $scope.extend(__ar_campaigns__);

            $scope.edit = function (id) {
                return '/admin/autoresponder/campaigns/edit/' + id;
            };
        });
</script>

