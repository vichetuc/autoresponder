<?php

namespace App\Controllers\Admin {

    use Minute\Http\HttpResponse;
    use Minute\Model\ActiveModel;
    use Minute\Model\Permission;
    use Minute\View\View;

    class ViewCampaigns {

        /**
         * @param ActiveModel $ar_campaigns
         * @param ActiveModel $ar_messages
         */
        public function index($ar_campaigns) {
            View::forge('Admin/ListView/ViewCampaigns.php', $ar_campaigns);
        }

        /**
         * @param ActiveModel $Arcampaign
         * @param string $cmd
         */
        public function save($Arcampaign, $cmd) {
            if ($cmd == 'remove') {
                $Arcampaign::$deletePermission = 'admin';

                $result = $Arcampaign->delete_cascaded('ar_messages');
            } else {
                $Arcampaign::$updatePermission = 'admin';

                $result = $Arcampaign->save();
            }

            HttpResponse::getInstance()->display($result, empty($result) ? 'Permission denied' : '');
        }

    }
}
