<?php

namespace App\Controllers\Admin {

    use ActiveRecord\DateTime;
    use Minute\Errors\BasicError;
    use Minute\Http\HttpResponse;
    use Minute\Autoresponder\Lists;

    class DownloadList {

        public function index($ar_list_id) {
            $rows = [];

            if ($users = Lists::getInstance()->getTargetUsers($ar_list_id)) {
                foreach ($users as $user) {
                    if ($attributes = $user->attributes()) {
                        unset($attributes['password']);
                        /** @var DateTime $r */
                        $attributes['created_at'] = ($attributes['created_at'] instanceof DateTime) ? $attributes['created_at']->format() : '';
                        $attributes['updated_at'] = ($attributes['updated_at'] instanceof DateTime) ? $attributes['updated_at']->format() : '';

                        if ($levels = $user->user_level) {
                            $attributes['levels'] = implode(', ', array_map(function ($level) { return $level->level; }, $levels));
                        }

                        if (empty($rows)) {
                            $rows[] = array_keys($attributes);
                        }

                        $rows[] = array_values($attributes);
                    }
                }

                HttpResponse::getInstance()->sendDownload(sprintf('ar_download_list_%d.csv', $ar_list_id), 'text/csv');

                $out = fopen('php://output', 'w');

                foreach ($rows as $row) {
                    fputcsv($out, $row);
                }

                fclose($out);

            } else {
                throw new BasicError("No matching users: List does not contain any users");
            }
        }
    }
}
