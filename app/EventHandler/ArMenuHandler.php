<?php
/**
 * Created by PhpStorm.
 * User: san
 * Date: 5/10/2015
 * Time: 4:06 PM
 */

namespace App\EventHandler {

    use Minute\Events\AddHandlerEvent;

    class ArMenuHandler {
        public function menu(AddHandlerEvent $event) {
            $menu = [['name' => 'Mails', 'icon' => 'glyphicon glyphicon-envelope', 'priority' => 'highest',
                      'sub-menu' => [['name' => 'Auto responder', 'icon' => 'glyphicon glyphicon-retweet', 'priority' => 'high', 'href' => 'autoresponder/campaigns'],
                                     ['name' => 'Broadcast', 'icon' => 'glyphicon glyphicon-bullhorn', 'priority' => 'medium', 'href' => 'autoresponder/broadcast'],
                                     ['name' => 'Lists', 'icon' => 'glyphicon glyphicon-tasks', 'priority' => 'low', 'href' => 'autoresponder/lists']]]];

            $event->addHandler($menu);
        }
    }
}