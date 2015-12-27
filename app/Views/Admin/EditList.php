<script type="text/javascript" src="/static/bower_components/ace-builds/src-min-noconflict/ace.js"></script>
<script type="text/javascript" src="/static/bower_components/angular-ui-ace/ui-ace.js"></script>

<div ng-app="ngApp" ng-controller="ngAppController" ng-init="init()">
    <div class="title">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h2><a href="/admin/autoresponder/lists">Lists</a> &gt; {{list.name || 'Untitled list'}}</h2>
                </div>
                <div class="pull-right">
                    <a href="" minute-help="autoresponder/list" class="btn btn-default btn-xs"><i class="fa fa-check-circle"></i> Need help?</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="box">
                    <form class="form-horizontal" name="listForm" ng-submit="list.save('Saved')">
                        <fieldset>
                            <legend>{{list.ar_list_id && 'Edit' || 'Create new'}} list:</legend>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="name">Name:</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="list.name" id="name" ng-required="true" placeholder="Name" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="description">Description:</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" ng-model="list.description" id="description" placeholder="Description" />
                                </div>
                            </div>

                            <div ng-repeat="type in ['positive', 'negative']" ng-if="list.ar_list_id">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">{{type|ucfirst}} SQLs:</label>

                                    <div class="col-sm-10">
                                        <ul class="list-group">
                                            <li class="list-group-item" ng-repeat="sql in list.sqls" ng-if="sql.type===type">
                                                <span class="badge"><a href="" ng-click="sql.removeConfirm()" title="remove"><i class="fa fa-remove fa-inverse"></i></a></span>
                                                <a href="" class="graylinks" ng-click="editSQL(sql)">{{sql.sql}}</a>
                                            </li>
                                            <li class="list-group-item"><a href="" ng-click="addSQL(type)">Add new {{type}} SQL..</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" ng-if="list.ar_list_id">
                                <label class="col-sm-2 control-label" for="export">Export:</label>

                                <div class="col-sm-10">
                                    <p class="help-block">
                                        <button type="button" class="btn btn-default btn-xs" ng-click="download()"><i class="fa fa-download"></i> Download list of matching users..</button>
                                        <small>(to verify results)</small>
                                    </p>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> {{list.ar_list_id && 'Save' || 'Create'}} list</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sqlModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add new {{lastSQL.type}} SQL statement:</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning alert-dismissible" role="alert" ng-if="lastSQL.type==='positive'">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <p>Positive SQL statement must return a list of <code>user_id</code> (to add to the mailing list).
                            The result should not contain any other column except <code>user_id</code>.</p>
                    </div>
                    <div class="alert alert-warning alert-dismissible" role="alert" ng-if="lastSQL.type==='negative'">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <p>Negative SQL statement must return a list of <code>user_id</code> which will be removed from the list of positive <code>user_id</code>. The result should not contain any
                            other column except <code>user_id</code>.</p>
                    </div>

                    <div id="editor" ui-ace='{"mode": "mysql", useWrapMode : true}' ng-model="lastSQL.sql" class="ace-slim"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" ng-click="saveSQL();">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    angular.module('ngApp', ['minutephp', 'angularStringFilters', 'ui.ace'])
        .config(['$sessionProvider', function ($sessionProvider) {
            $sessionProvider.load(<?= $this->getSessionData() ?>);
        }])
        .controller('ngAppController', function ($scope, $minutephp, $timeout) {
            $scope.extend(<?= $ar_lists ?>);

            $scope.init = function () {
                $scope.list = $scope.ar_lists[0] || $scope.ar_lists.create();
                $('#sqlModal').bind('hide.bs.modal', $scope.modalDismiss)
            };

            $scope.addSQL = function (type) {
                var sql = $scope.list.sqls.create().set('type', type).set('sql', 'SELECT user_id from USERS WHERE user_id IN (SELECT user_id from user_levels WHERE level = "admin") LIMIT 1');
                $scope.editSQL(sql);
            };

            $scope.editSQL = function (sql) {
                $scope.lastSQL = sql;
                $('#sqlModal').modal('show');
                $timeout(function () {
                    var textarea = $('#editor textarea').get()[0];
                    textarea.focus();
                    textarea.select();
                }, 200);
            };

            $scope.saveSQL = function () {
                var hideModal = function () {
                    $('#sqlModal').modal('hide');
                };
                $scope.lastSQL.save('SQL added').then(hideModal, hideModal);
            };

            $scope.modalDismiss = function () {
                if (!$scope.lastSQL.get('ar_list_sql_id') || !$scope.lastSQL.get('sql')) {
                    $scope.lastSQL.remove();
                }

                $scope.lastSQL = null;
            };

            $scope.download = function () {
                window.open($scope.session.admin + '/autoresponder/lists/download/' + $scope.list.ar_list_id, '_blank');
            };
        });
</script>

