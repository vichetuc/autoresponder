<?php
/**
 * Created by PhpStorm.
 * User: san
 * Date: 11/24/2015
 * Time: 6:11 PM
 */

namespace App\EventHandler {

    use Minute\Events\DatabaseEvent;

    class ArDbExportHandler {
        public function export(DatabaseEvent $event) {
            $event->addContent('ar_queue');
        }
    }
}