<?php
/**
 * Created by PhpStorm.
 * User: san
 * Date: 6/1/2015
 * Time: 6:43 PM
 */

namespace Minute\Autoresponder {
    use ActiveRecord\Connection;
    use App\Models\ArCampaign;
    use App\Models\ArHistory;
    use App\Models\ArQueue;
    use App\Models\User;
    use DateTime;
    use DateTimeZone;
    use Exception;
    use Minute\Core\Singleton;
    use Minute\Mail\SendMail;

    class Autoresponder extends Singleton {
        /**
         * @return Autoresponder
         */
        public static function getInstance() {
            return parent::getInstance();
        }

        public function queueEmails() {
            ArCampaign::$has_many = [['messages', 'foreign_key' => 'ar_campaign_id', 'class_name' => 'ArMessage', 'order' => 'sequence asc']];

            if ($autoresponders = ArCampaign::find_all_by_enabled('y')) {
                $lists       = Lists::getInstance();
                $ext_mail_id = function ($msg) { return $msg->mail_id ?: 0; };

                foreach ($autoresponders as $autoresponder_id => $autoresponder) {
                    if ($messages = $autoresponder->messages) {
                        $mails = $mail_ids = [];

                        foreach ($messages as $message) {
                            if ($mail_id = $message->mail_id) {
                                $mails[$mail_id] = $message;
                                $mail_ids[]      = $mail_id;
                            }
                        }

                        if ($user_ids = $lists->getTargetUserIds($autoresponder->ar_list_id)) {
                            printf("Sending mail to %d users..\n", count($user_ids));

                            $sql = sprintf('SELECT user_id, MAX(sent_at) AS last_sent, COUNT(user_id) AS total_sent FROM ar_history WHERE user_id IN (%s) AND mail_id IN (%s) ' .
                                           'GROUP BY user_id HAVING ((total_sent = %d) OR (last_sent > DATE_SUB(NOW(), INTERVAL 1 DAY)))', join(',', $user_ids), join(',', $mail_ids), count($messages));

                            if ($recent_or_full = User::find_by_sql($sql)) {        # find all users that have been sent all messages |OR| last mail sent is within 1 day
                                foreach ($recent_or_full as $i_user) {
                                    $ignored_user_ids[$i_user->user_id] = 1;
                                }
                            }

                            if ($send_to = !empty($ignored_user_ids) ? array_diff($user_ids, array_keys($ignored_user_ids)) : $user_ids) {
                                foreach ($send_to as $user_id) {
                                    $days_since_last_email = 1000;

                                    if ($messages_sent = ArHistory::find_all_by_user_id($user_id, ['select' => 'mail_id, sent_at', 'order' => 'sent_at ASC', 'limit' => count($messages)])) {
                                        $message_sent_ids = array_map($ext_mail_id, $messages_sent);
                                        if ($last_sent = end($messages_sent)) {
                                            $days_since_last_email = floor(max(0, time() - $last_sent->sent_at->getTimestamp()) / 86400);
                                        }
                                    }

                                    if ($messages_remaining = !empty($message_sent_ids) ? array_diff($mail_ids, $message_sent_ids) : $mail_ids) {
                                        if ($mail_id_to_send = array_shift($messages_remaining)) {
                                            try {
                                                $wait_days = !empty($message_sent_ids) && !empty($mails[$mail_id_to_send]) ? $mails[$mail_id_to_send]->wait : 0;

                                                if ($days_since_last_email >= $wait_days) {
                                                    echo "Sending mail to $user_id: $mail_id_to_send\n";
                                                    $send_at = new DateTime();

                                                    if ($schedule = $autoresponder->schedule) {
                                                        if ($ranges = json_decode($schedule, true)) {
                                                            $user     = User::find_cached($user_id);
                                                            $timezone = timezone_name_from_abbr("", $user->tz_offset * -60, 0);
                                                            $send_at  = $this->getQueueDate($ranges, $timezone);
                                                        }
                                                    }

                                                    $now     = new DateTime();
                                                    $sent_at = $send_at->format(Connection::$datetime_format);

                                                    if (ArHistory::create_direct(['user_id' => $user_id, 'mail_id' => $mail_id_to_send, 'sent_at' => $sent_at])) {
                                                        if ($send_at > $now) {
                                                            ArQueue::create_direct(['send_at' => $sent_at, 'user_id' => $user_id, 'mail_id' => $mail_id_to_send, 'status' => 'pending']);
                                                        } else {
                                                            SendMail::getInstance()->send($mail_id_to_send, $user_id);
                                                        }
                                                    }
                                                }
                                            } catch (Exception $e) {
                                                dd($e->getMessage());
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        public function getQueueDate($ranges, $timezone = '') {
            for ($i = 0; $i < 168; $i++) {
                $tz  = new DateTimeZone($timezone ?: 'America/Chicago');
                $now = new DateTime ("now + $i hour", $tz);

                foreach ($ranges as $range) {
                    foreach ($range['days'] as $day_id => $day) {
                        $start  = DateTime::createFromFormat('D H:i', sprintf('%s %s', $day, $range['start_time']), $tz);
                        $finish = DateTime::createFromFormat('D H:i', sprintf('%s %s', $day, $range['end_time']), $tz);

                        if (($now >= $start) && ($now <= $finish)) {
                            /** @var DateTime $date */
                            $date = $i > 0 ? $start : $now;

                            $date->setTimezone(new DateTimeZone(ini_get('data.timezone') ?: 'America/Chicago'));

                            return $date;
                        }
                    }
                }
            }

            return false;
        }
    }
}