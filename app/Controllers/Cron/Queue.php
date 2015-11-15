<?php
/**
 * Created by PhpStorm.
 * User: san
 * Date: 11/14/2015
 * Time: 8:05 PM
 */

namespace App\Controllers\Cron {

    use Minute\Autoresponder\Autoresponder;
    use Minute\Autoresponder\Broadcast;
    use Minute\Autoresponder\Mailer;

    class Queue {
        public function queueResponses() {
            $autoresponder = Autoresponder::getInstance();
            $autoresponder->queueEmails();
        }

        public function queueBroadcast() {
            $broadcast = Broadcast::getInstance();
            $broadcast->queueBroadcast();
        }

        public function sendQueue() {
            $mailer = Mailer::getInstance();
            $mailer->sendPendingMails();
        }
    }
}