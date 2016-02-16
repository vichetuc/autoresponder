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
    }
}
