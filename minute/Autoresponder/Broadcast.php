<?php
/**
 * Created by PhpStorm.
 * User: san
 * Date: 6/5/2015
 * Time: 7:32 PM
 */

namespace Minute\Autoresponder {

    use App\Models\ArBroadcast;
    use App\Models\ArQueue;
    use Minute\App\App;
    use Minute\Core\Singleton;

    class Broadcast {
        protected $app;

        function __construct() {
            $this->app = App::getInstance();
        }

        public function queueBroadcast() {
            if ($broadcasts = ArBroadcast::find('all', ['conditions' => 'status = "pending" and send_at <= NOW()'])) {
                foreach ($broadcasts as $broadcast) {
                    try {
                        $this->updateBroadcastStatus($broadcast, 'processing');

                        if ($user_ids = Lists::getInstance()->getTargetUserIds($broadcast->ar_list_id)) {
                            if ($mail_id = $broadcast->mail_id) {
                                if ($max_time = ($broadcast->mailing_time ?: 1) * 60 * 60) {
                                    $time_offset   = 0;
                                    $mails_per_sec = min(1, $max_time / count($user_ids));

                                    foreach ($user_ids as $user_id) {
                                        try {
                                            $send_at = date('Y-m-d H:i:s', time() + floor($time_offset));

                                            if (ArQueue::create_direct(['user_id' => $user_id, 'mail_id' => $mail_id, 'send_at' => $send_at, 'status' => 'pending'])) {
                                                $time_offset += $mails_per_sec;
                                            }
                                        } catch (\Exception $e) {
                                        }
                                    }
                                }
                            }
                        }
                    } finally {
                        $this->updateBroadcastStatus($broadcast, 'sent');
                    }
                }
            }
        }

        /**
         * @param Broadcast $broadcast
         * @param string $status
         */
        private function updateBroadcastStatus($broadcast, $status) {
            $broadcast->status = $status;
            $broadcast->save_direct();
        }
    }
}