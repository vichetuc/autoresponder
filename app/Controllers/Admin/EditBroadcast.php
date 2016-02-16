<?php

namespace App\Controllers\Admin {

    use Minute\Http\HttpResponse;
    use Minute\Model\ActiveModel;
    use Minute\Model\Permission;
    use Minute\View\View;

    class EditBroadcast {

        /**
         * @param ActiveModel $ar_broadcasts
         */
        public function index($ar_broadcasts, $all_mails, $all_lists) {
            View::forge('Admin/Autoresponder/EditBroadcast.php', $ar_broadcasts, $all_mails, $all_lists);
        }
    }
}
