<div ng-app="ngApp" ng-controller="ngAppController" ng-init="init()">
    <div class="title">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2><a href="" ng-href="{{session.admin}}/autoresponder/broadcast">Broadcasts</a> &gt; {{broadcast.name || 'Untitled'}}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="box">
                    <form class="form-horizontal" name="mainform" method="POST" ng-submit="save()">
                        <fieldset>
                            <legend>{{broadcast.ar_broadcast_id && 'Edit' || 'Create'}} broadcast</legend>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Name:</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="broadcast.name" id="name" ng-required="true" placeholder="Name" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="schedule">Send:</label>

                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="schedule" name="schedule" ng-value="'now'" ng-required="true"> Immediately
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="schedule" name="schedule" ng-value="'fixed'" ng-required="true"> Fixed date
                                    </label>
                                </div>
                            </div>


                            <div class="form-group" ng-if="schedule=='fixed'">
                                <label class="col-sm-2 control-label" for="send_at">Send date:</label>

                                <div class="col-sm-10">
                                    <div class="dropdown">
                                        <a class="dropdown-toggle my-toggle-select" id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="">
                                            <div class="input-group">
                                                <input type="text" class="form-control" data-ng-model="broadcast.send_at" ng-required="true" placeholder="Date/time to send" />

                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                            <datetimepicker data-ng-model="broadcast.send_at" data-datetimepicker-config="{ dropdownSelector: '.my-toggle-select' }"></datetimepicker>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="mail_id">Mail to send:</label>

                                <div class="col-sm-10">
                                    <ol class="nya-bs-select form-control" ng-model="broadcast.mail_id" required="true" ng-required="true" data-live-search="true" size="15"
                                        ng-if="all_mails.length > 0">
                                        <li nya-bs-option="option in all_mails" value="option.mail_id">
                                            <span class="dropdown-header">{{$group}}</span>
                                            <a>
                                                <span>{{name(option)}}</span> <!-- this content will be search first -->
                                                <span class="glyphicon glyphicon-ok check-mark"></span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="list">List to send:</label>

                                <div class="col-sm-10">
                                    <ol class="nya-bs-select form-control" ng-model="broadcast.ar_list_id" data-live-search="true" size="15" ng-if="all_lists.length > 0">
                                        <li nya-bs-option="option in all_lists" value="option.ar_list_id">
                                            <span class="dropdown-header">{{$group}}</span>
                                            <a>
                                                <span>{{name(option)}}</span> <!-- this content will be search first -->
                                                <span class="glyphicon glyphicon-ok check-mark"></span>
                                            </a>
                                        </li>
                                    </ol>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="mailing_time">Mailing time:</label>

                                <div class="col-md-6 col-sm-12 col-lg-5">
                                    <div class="input-group">
                                        <input type="number" min="1" max="24" class="form-control" ng-model="broadcast.mailing_time" id="mailing_time" ng-required="true" placeholder="Mailing time" />

                                        <div class="input-group-addon">hours</div>
                                    </div>
                                    <p class="help-block">(amount of time allowed to deliver all mails)</p>
                                </div>
                            </div>


                            <div class="form-group form-submit">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary" ng-disabled="!broadcast.ar_list_id||!broadcast.mail_id||broadcast.status==='processing'||broadcast.status==='sent'">
                                        <i class="fa fa-check-circle"></i> {{broadcast.ar_broadcast_id && 'Update' || 'Create'}} broadcast
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    angular.module('ngApp', ['minutephp', 'nya.bootstrap.select', 'ui.bootstrap.datetimepicker'])
        .config(['$sessionProvider', function ($sessionProvider) {
            $sessionProvider.load(<?= $this->getSessionData() ?>);
        }])
        .controller('ngAppController', function ($scope, $minutephp, $notice) {
            $minutephp.import($scope, <?= $ar_broadcasts ?>);
            $minutephp.import($scope, <?= $all_lists ?>);
            $minutephp.import($scope, <?= $all_mails ?>);

            $scope.init = function () {
                $scope.broadcast = $scope.ar_broadcasts[0] || $scope.ar_broadcasts.create();
                $scope.$watch('schedule', $scope.setSendTime);
                $scope.schedule = $scope.broadcast.send_at ? 'fixed' : 'now';
            };

            $scope.setSendTime = function () {
                if ($scope.schedule == 'now') {
                    $scope.broadcast.send_at = new Date();
                }
            };

            $scope.save = function () {
                $scope.broadcast.saveAndRedirect('Saved');
            };

            $scope.name = function (item) {
                return item.name + (item.description ? ': ' + item.description : '');
            };
        });
</script>

