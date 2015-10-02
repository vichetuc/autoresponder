<?php

namespace App\Controllers\Admin {

    use Minute\Http\HttpResponse;
    use Minute\Model\ActiveModel;
    use Minute\Model\Permission;
    use Minute\View\View;

    class EditCampaigns {

        /**
         * @param ActiveModel $ar_campaign
         * @param ActiveModel $ar_messages
         */
        public function index($ar_campaign, $all_mails, $all_lists) {
            View::forge('Admin/Autoresponder/EditCampaigns.php', $ar_campaign, $all_mails, $all_lists);
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
