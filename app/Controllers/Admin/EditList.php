<?php

namespace App\Controllers\Admin {

    use Minute\Http\HttpResponse;
    use Minute\Model\ActiveModel;
    use Minute\Model\Permission;
    use Minute\View\View;

    class EditList {

        /**
         * @param ActiveModel $ar_lists
         * @param ActiveModel $sqls
         */
        public function index($ar_lists, $sqls) {
            View::forge('Admin/EditList.php', $ar_lists, $sqls);
        }

        /**
         * @param ActiveModel $model
         * @param string $cmd
         */
        public function save($model, $cmd) {
            if ($cmd == 'remove') {
                $model::$deletePermission = 'admin';

                $result = $model->delete();
            } else {
                $model::$createPermission = 'admin';
                $model::$updatePermission = 'admin';

                $result = $model->save();
            }

            HttpResponse::getInstance()->display($result, empty($result) ? 'Permission denied' : '');
        }

    }
}
