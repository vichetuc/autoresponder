<div ng-app="ngApp" ng-controller="ngAppController" ng-init="init()">
    <div class="title">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2>Autoresponder campaigns</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-lg btn-success" href="" ng-click="edit(0)"><i class="fa fa-plus-circle"></i> New campaign</a>
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
                    <th>Target</th>
                    <th>Messages</th>
                    <th>Enabled</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                <tr class="text-links" ng-repeat="ar_campaign in ar_campaigns" ng-click="edit(ar_campaign.ar_campaign_id)">
                    <td>{{ar_campaign.ar_campaign_id}}</td>
                    <td>{{ar_campaign.name}}</td>
                    <td>{{ar_campaign.created_at | timeAgo}}</td>
                    <td>{{ar_campaign.ar_lists.name || 'None'}}</td>
                    <td>{{ar_campaign.ar_messages.getTotalItems() || 'None'}}</td>
                    <td>{{ar_campaign.enabled === 'y' && 'Yes' || 'No'}}</td>
                    <td class="unclickable">
                        <div class="dropdown">
                            <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-xs btn-default">
                                <i class="fa fa-cog"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dLabel">
                                <li><a href="" ng-show="ar_campaign.enabled === 'n'" ng-click="ar_campaign.set('enabled', 'y').save('Campaign enabled')"><i class="fa fa-check-circle"></i> Enable
                                        campaign</a></li>
                                <li><a href="" ng-show="ar_campaign.enabled === 'y'" ng-click="ar_campaign.set('enabled', 'n').save('Campaign disabled')"><i class="fa fa-power-off"></i> Disable
                                        campaign</a></li>
                                <li class="divider"></li>
                                <li><a href="" ng-click="ar_campaign.removeConfirm();"><i class="fa fa-times"></i> Remove campaign</a></li>
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
        .controller('ngAppController', function ($scope, $minutephp) {
            $minutephp.import($scope, <?= $ar_campaigns ?>);

            $scope.edit = function (id) {
                if (!$(event.target).closest('td.unclickable').length) {
                    location.href = $scope.session.admin + '/autoresponder/campaigns/edit/' + id;
                }
            };
        });
</script>

