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
    use DateInterval;
    use DateTime;
    use DateTimeZone;
    use Exception;
    use Minute\App\App;
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
                            $count = 0;
                            printf("Found %d users in list: $autoresponder->ar_list_id\n", count($user_ids));

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
                                                    $send_at = new DateTime();

                                                    if ($schedule = $autoresponder->schedule) {
                                                        if ($ranges = json_decode($schedule, true)) {
                                                            $user     = User::find_cached($user_id);
                                                            $timezone = !empty($user->tz_offset) ? timezone_name_from_abbr("", $user->tz_offset * -60, 0) : date_default_timezone_get();
                                                            $send_at  = $this->getQueueDate($ranges, $timezone) ?: $send_at;
                                                        }
                                                    }

                                                    $sent_at = $send_at->format(Connection::$datetime_format);

                                                    if (ArHistory::create_direct(['user_id' => $user_id, 'mail_id' => $mail_id_to_send, 'sent_at' => $sent_at])) {
                                                        $now   = new DateTime();
                                                        $count = $count + 1;

                                                        if ($send_at > $now) {
                                                            echo "Queued mail_id #$mail_id_to_send for user_id #$user_id at $sent_at\n";
                                                            ArQueue::create_direct(['send_at' => $sent_at, 'user_id' => $user_id, 'mail_id' => $mail_id_to_send, 'status' => 'pending']);
                                                        } else {
                                                            echo "Sending mail_id #$mail_id_to_send to user_id #$user_id right now\n";
                                                            SendMail::getInstance()->send($mail_id_to_send, $user_id);
                                                        }
                                                    }
                                                }
                                            } catch (Exception $e) {
                                                App::getInstance()->warn("Unable to send mail: " . $e->getMessage());
                                            }
                                        }
                                    }
                                }
                            }

                            print "$count mails sent\n";
                        }
                    }
                }
            }
        }

        public function getQueueDate($ranges, $timezone = '') {
            $tz      = new DateTimeZone($timezone ?: (date_default_timezone_get() ?: 'America/Chicago'));
            $now     = new DateTime ("now", $tz);
            $current = function ($date) {
                /** @var DateTime $date */
                $date->setTimezone(new DateTimeZone(date_default_timezone_get()));

                return $date;
            };

            foreach ($ranges as $range) {
                foreach ($range['days'] as $day_id => $day) {
                    $start  = DateTime::createFromFormat('D H:i', sprintf('%s %s', $day, $range['start_time']), $tz);
                    $finish = DateTime::createFromFormat('D H:i', sprintf('%s %s', $day, $range['end_time']), $tz);

                    if (($now >= $start) && ($now <= $finish)) {
                        return $current($now);
                    } elseif ($start > $now) {
                        $next = empty($next) ? $start : ($start < $next ? $start : $next);
                    } elseif (empty($next)) {
                        $next = $start->add(new DateInterval('P1W'));
                    }
                }
            }

            return !empty($next) ? $current($next) : false;
        }
    }
}