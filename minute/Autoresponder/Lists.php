<?php
/**
 * Created by PhpStorm.
 * User: san
 * Date: 5/31/2015
 * Time: 10:04 PM
 */

namespace Minute\Autoresponder {

    use ActiveRecord\Cache;
    use App\Models\ArList;
    use App\Models\User;
    use Minute\Core\Singleton;
    use Minute\User\UserManager;

    class Lists extends Singleton {
        protected $userClass;

        function __construct() {
            ArList::$has_many = [['sqls', 'foreign_key' => 'ar_list_id', 'class_name' => 'ArListSql']];
            $this->userClass  = UserManager::getInstance()->getUserModelClass();
        }

        /**
         * @return Lists
         */
        public static function getInstance() {
            return parent::getInstance();
        }

        public function getTargetUserIds($ar_list_id) {
            $positive = $negative = [];

            if ($list = ArList::find($ar_list_id)) {
                if ($sqls = $list->sqls) {
                    foreach ($sqls as $sql) {
                        if ($sqlStatement = $sql->sql) {
                            $users = Cache::get(md5($sqlStatement), function () use ($sqlStatement) {
                                return User::find_by_sql(strtolower($sqlStatement)); //strtolower to make table name lowercase
                            });

                            if (!empty($users)) {
                                foreach ($users as $user) {
                                    if ($user_id = $user->user_id) {
                                        if ($sql->type === 'positive') {
                                            $positive[$user_id] = 1;
                                        } elseif ($sql->type === 'negative') {
                                            $negative[$user_id] = 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return array_diff(array_keys($positive), array_keys($negative)) ?: [];
        }

        public function getTargetUsers($ar_list_id) {
            if ($user_ids = Lists::getInstance()->getTargetUserIds($ar_list_id)) {
                $users = User::find($user_ids);

                if (!empty($users)) {
                    return (is_array($users) ? $users : [$users]);
                }
            }

            return null;
        }
    }
}