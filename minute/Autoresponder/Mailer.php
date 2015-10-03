<?php
/**
 * Created by PhpStorm.
 * User: san
 * Date: 6/5/2015
 * Time: 9:27 PM
 */

namespace Minute\Autoresponder {

    use App\Models\ArQueue;
    use Minute\Core\Singleton;
    use Minute\Mail\SendMail;

    class Mailer extends Singleton {
        /**
         * @return Mailer
         */
        public static function getInstance() {
            return parent::getInstance();
        }

        public function sendPendingMails() {
            if ($mails = ArQueue::find('all', ['conditions' => 'status="pending" and send_at < NOW()'])) {
                $sendMail = SendMail::getInstance();

                foreach ($mails as $mail) {
                    $status = 'fail';

                    try {
                        if ($sendMail->send($mail->mail_id, $mail->user_id)) {
                            $status = 'pass';
                        }
                    } finally {
                        $mail->status = $status;
                        $mail->save_direct();
                    }
                }
            }
        }

    }

}