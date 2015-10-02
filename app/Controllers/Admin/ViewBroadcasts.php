<?php

namespace App\Controllers\Admin {

    use Minute\Http\HttpResponse;
    use Minute\Model\ActiveModel;
    use Minute\Model\Permission;
    use Minute\View\View;

    class ViewBroadcasts {

        public function index($broadcasts) {
            View::forge('Admin/ListView/ViewBroadcasts.php', $broadcasts);
        }

        /**
         * @param ActiveModel $ArBroadcast
         * @param string $cmd
         */
        public function save($ArBroadcast, $cmd) {
            if ($cmd == 'remove') {
                $ArBroadcast::$deletePermission = 'admin';

                $result = $ArBroadcast->delete();
            } else {
                $ArBroadcast::$createPermission = 'admin';
                $ArBroadcast::$updatePermission = 'admin';

                $result = $ArBroadcast->save();
            }

            HttpResponse::getInstance()->display($result, empty($result) ? 'Permission denied' : '');
        }
    }
}
