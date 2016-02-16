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
    }
}
