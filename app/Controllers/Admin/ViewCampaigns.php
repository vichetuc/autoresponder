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
    }
}
