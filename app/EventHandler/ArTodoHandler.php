<?php
/**
 * Created by PhpStorm.
 * User: san
 * Date: 5/10/2015
 * Time: 4:06 PM
 */

namespace App\EventHandler {

    use App\Models\ArCampaign;
    use Minute\Events\AddContentEvent;

    class ArTodoHandler {
        public function todo(AddContentEvent $event) {
            $todos[] = ['name' => "Create an autoresponder campaign", 'status' => ArCampaign::count(['conditions' => ['enabled="y"']]) ? 'complete' : 'incomplete', 'link' => '/admin/mails'];

            $contents = $event->getContents();
            $event->setContents(array_merge($contents, ['Autoresponder' => $todos]));
        }
    }
}