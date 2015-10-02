<?php

namespace App\Controllers\Admin {

    use Minute\Http\HttpResponse;
    use Minute\Model\ActiveModel;
    use Minute\Model\Permission;
    use Minute\View\View;

    class ViewLists {

        /**
         * @param ActiveModel $ar_lists
         * @param ActiveModel $ar_list_sqls
         */
        public function index($ar_lists, $ar_list_sqls) {
            View::forge('Admin/ListView/ViewLists.php', $ar_lists, $ar_list_sqls);
        }

    }
}
