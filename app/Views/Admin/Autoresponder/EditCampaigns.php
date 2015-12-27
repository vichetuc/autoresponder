<div ng-app="ngApp" ng-controller="ngAppController" ng-init="init()">
    <div class="title">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2><a href="/admin/autoresponder/campaigns">Campaigns</a> &gt; {{campaign.name || 'Untitled'}}</h2>
                </div>
                <div class="pull-right">

                </div>
            </div>
        </div>
    </div>

    <div class="content">

        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="box">
                    <form class="form-horizontal" name="campaignForm" ng-submit="save()">
                        <fieldset>
                            <legend>{{campaign.ar_campaign_id&&'Edit '||'Create new'}} campaign</legend>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Name:</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="campaign.name" id="name" ng-required="true" placeholder="Name" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="description">Description:</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="campaign.description" id="description" placeholder="Description" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="list">Target list:</label>

                                <div class="col-sm-10">
                                    <ol class="nya-bs-select form-control" ng-model="campaign.ar_list_id" data-live-search="true" size="15" ng-if="all_lists.length > 0">
                                        <li nya-bs-option="option in all_lists" value="option.ar_list_id">
                                            <span class="dropdown-header">{{$group}}</span>
                                            <a>
                                                <span>{{option.name}}</span> <!-- this content will be search first -->
                                                <span class="glyphicon glyphicon-ok check-mark"></span>
                                            </a>
                                        </li>
                                    </ol>

                                    <p class="help-block">[ <a href="" ng-href="{{session.admin}}/autoresponder/lists">Manage lists</a> ]</p>
                                </div>
                            </div>

                            <div class="form-group" ng-if="campaign.ar_campaign_id">
                                <label class="col-sm-2 control-label" for="messages">Messages:</label>

                                <div class="col-sm-10">
                                    <ul id="sortable" class="list-group">
                                        <li class="list-group-item" data-mail-id="{{ar_message.mail_id}}" ng-repeat="ar_message in campaign.ar_messages | orderBy:'sequence'">
                                            <a class="badge" href="#" ng-click="ar_message.removeConfirm('', 'Removed')" title="remove message"><i class="fa fa-remove fa-xs fa-inverse"></i></a>
                                            <a class="badge extra-right-margin" href="#" ng-click="preview(ar_message.mail_id)" title="view email"><i class="fa fa-search fa-xs fa-inverse"></i></a>

                                            <div class="row item-sortable">
                                                <div class="col-sm-5">
                                                    {{$index+1}}.
                                                    {{ar_message.mail.name}}
                                                    <small ng-if="ar_message.mail.description" class="text-muted text-small" style="margin-left: 15px;"><br />{{ar_message.mail.description}}</small>
                                                </div>
                                                <div class="col-sm-5">
                                                    <span class="label label-info" ng-if="!$index">Sent immediately</span>

                                                    <div ng-if="$index">
                                                        <select class="form-control" ng-model="ar_message.wait" ng-required="true" ng-options="item.value as item.name for item in days">
                                                            <option value="">Please select one</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item not-sortable" style="padding: 20px;">
                                            <a href="" class="btn btn-default" ng-click="showMsgList()"><b><i class="fa fa-plus-circle"></i> Insert message..</b></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="schedule">Schedule:</label>

                                <div class="col-sm-10">
                                    <div class="table-responsive" ng-if="schedules.length">
                                        <table class="table table-bordered table-condensed">
                                            <tbody>
                                            <tr ng-repeat="schedule in schedules" class="text-links">
                                                <td ng-repeat="day in allDays" class="center-cell {{inarray(schedule, day) && 'success' || 'danger'}}" ng-click="toggle(schedule, day)">
                                                    <i class="fa {{inarray(schedule, day) && 'fa-check-circle' || 'fa-times-circle'}}"></i> {{day}}
                                                </td>
                                                <td>
                                                    <select class="form-control" ng-model="schedule.start_time" ng-required="true" ng-options="item for item in times">
                                                        <option value="">Start time</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" ng-model="schedule.end_time" ng-required="true" ng-options="item for item in remains(times, schedule.start_time)">
                                                        <option value="">End time</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="pull-right"><a href="#" ng-click="removeSchedule(schedule)"><i class="fa fa-remove"></i></a></div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <p class="help-block">
                                        <button type="button" class="btn btn-default btn-sm" ng-click="addSchedule()"><i class="fa fa-plus-circle"></i> Add schedule</button>
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="enabled">Enabled:</label>

                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="campaign.enabled" name="enabled" ng-value="'y'"> Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="campaign.enabled" name="enabled" ng-value="'n'"> No
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-check-circle"></i> Save changes</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="messagesModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add messages to autoresponder</h4>
                </div>
                <div class="modal-body">
                    <table datatable="ng" class="table row-border">
                        <thead>
                        <tr>
                            <th>ID#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Subject</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="mail in filter(all_mails)">
                            <td>{{mail.mail_id}}</td>
                            <td>{{mail.name}}
                                <small ng-if="mail.description" class="text-muted text-small"><br />{{mail.description}}</small>
                            </td>
                            <td>{{mail.category}}</td>
                            <td>{{mail.message.subject}}</td>
                            <td>
                                <button type="button" class="btn btn-xs btn-primary" ng-click="addMsg(mail.mail_id)"><i class="fa fa-plus-circle"></i> Add</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    angular.module('ngApp', ['minutephp', 'nya.bootstrap.select', 'datatables'])
        .config(['$sessionProvider', function ($sessionProvider) {
            $sessionProvider.load(<?= $this->getSessionData() ?>);
        }])
        .controller('ngAppController', function ($scope, $minutephp, $timeout) {
            $scope.extend(<?= $ar_campaign ?>);
            $scope.extend(<?= $all_mails ?>);
            $scope.extend(<?= $all_lists ?>);

            $scope.copy = [];
            $scope.allDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

            $scope.init = function () {
                $scope.campaign = $scope.ar_campaign[0] || $scope.ar_campaign.create();
                $scope.days = [];
                $scope.times = [];

                for (i = 1; i <= 35; i++) {
                    $scope.days.push({value: i, name: i + ' day' + (i > 1 ? 's' : '') + ' after last message'});
                }

                for (i = 0; i <= 23; i++) {
                    $scope.times.push((i < 10 ? '0' : '') + i + ':00');
                }

                var jsonSchedules = $scope.campaign.schedule;
                $scope.schedules = jsonSchedules ? angular.fromJson(jsonSchedules) : [];
                $scope.$watch('schedules', $scope.updateSchedule, true);

                $timeout(function () {
                    $("#sortable").sortable({
                        items: "li:not(.not-sortable)",
                        update: function (event, ui) {
                            var count = 0;

                            $('#sortable').find('li[data-mail-id]').each(function () {
                                var mail_id = $(this).data('mail-id');
                                if (mail = _.findWhere($scope.campaign.ar_messages, {mail_id: mail_id})) {
                                    mail.set('sequence', count++);
                                }
                            });

                            $scope.$apply();
                        }
                    });
                }, 100);
            };

            $scope.remains = function (arr, start) {
                var index = arr.indexOf(start);
                return (index > -1 ? arr.slice(index + 1) : arr).concat('23:59');
            };

            $scope.inarray = function (schedule, day) {
                return schedule.days && schedule.days.indexOf(day) > -1;
            };

            $scope.toggle = function (schedule, day) {
                if (!schedule.days || !angular.isArray(schedule.days)) {
                    schedule.days = [];
                }

                var index = schedule.days.indexOf(day);
                if (index > -1) {
                    schedule.days.splice(index, 1);
                } else {
                    var newArray = [];
                    angular.forEach($scope.allDays, function (v, k) {
                        if ((schedule.days.indexOf(v) > -1) || (v === day)) {
                            newArray.push(v);
                        }
                    });
                    schedule.days = newArray;
                }
            };

            $scope.addSchedule = function () {
                $scope.schedules.push({days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'], 'start_time': '09:00', 'end_time': '18:00'});
            };

            $scope.updateSchedule = function () {
                $scope.campaign.schedule = $scope.schedules.length > 0 ? angular.toJson($scope.schedules) : null;
            };

            $scope.removeSchedule = function (schedule) {
                var index = $scope.schedules.indexOf(schedule);
                if (index > -1) {
                    $scope.schedules.splice(index, 1);
                }
            };

            $scope.showMsgList = function () {
                $('#messagesModal').modal('show');
            };

            $scope.preview = function (id) {
                window.open($scope.session.admin + '/mails/edit/' + id, '_blank');
            };

            $scope.addMsg = function (id) {
                $scope.campaign.ar_messages.create().set('mail_id', id).set('sequence', $scope.campaign.ar_messages.length - 1).save();
            };

            $scope.save = function () {
                $scope.campaign.saveAndRedirect('Saved').then(function () {
                    $scope.campaign.ar_messages.saveAll();
                });
            };

            $scope.filter = function (arr) {
                $scope.copy.splice(0, $scope.copy.length);

                angular.forEach(arr, function (v, k) {
                    if (!_.findWhere($scope.campaign.ar_messages, {mail_id: v.mail_id})) {
                        $scope.copy.push(v);
                    }
                });

                return $scope.copy;
            };
        });
</script>

