/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ar_broadcasts` (
  `ar_broadcast_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `send_at` datetime NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `ar_list_id` int(11) DEFAULT NULL,
  `mail_id` int(11) DEFAULT NULL,
  `mailing_time` int(11) DEFAULT '60',
  `status` enum('pending','processing','sent') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`ar_broadcast_id`),
  KEY `status_send_at` (`status`,`send_at`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ar_campaigns` (
  `ar_campaign_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `ar_list_id` int(11) DEFAULT NULL,
  `schedule` longtext,
  `enabled` enum('n','y') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`ar_campaign_id`),
  KEY `ar_list_id` (`ar_list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ar_history` (
  `ar_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `sent_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `mail_id` int(11) NOT NULL,
  PRIMARY KEY (`ar_history_id`),
  UNIQUE KEY `user_id_mail_id` (`user_id`,`mail_id`),
  KEY `user_id_mail_id_sent_at` (`user_id`,`mail_id`,`sent_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ar_lists` (
  `ar_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ar_list_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ar_list_sqls` (
  `ar_list_sql_id` int(11) NOT NULL AUTO_INCREMENT,
  `ar_list_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sql` longtext NOT NULL,
  `type` enum('positive','negative') NOT NULL DEFAULT 'positive',
  PRIMARY KEY (`ar_list_sql_id`),
  UNIQUE KEY `ar_list_id_name` (`ar_list_id`,`name`),
  KEY `ar_list_id` (`ar_list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ar_messages` (
  `ar_message_id` int(11) NOT NULL AUTO_INCREMENT,
  `ar_campaign_id` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `mail_id` int(11) NOT NULL,
  `sequence` int(11) DEFAULT NULL,
  `wait` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ar_message_id`),
  UNIQUE KEY `autoresponder_campaign_id_mail_id` (`ar_campaign_id`,`mail_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ar_queue` (
  `ar_queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `send_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `mail_id` int(11) NOT NULL,
  `status` enum('pending','pass','fail') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`ar_queue_id`),
  UNIQUE KEY `user_id_mail_id` (`user_id`,`mail_id`),
  KEY `status_send_at` (`status`,`send_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT IGNORE INTO `events` VALUES (NULL,'admin_menu_render','App\\EventHandler\\ArMenuHandler@admin',NULL,0,'Autoresponder menu in admin');
INSERT IGNORE INTO `events` VALUES (NULL,'todo_admin','App\\EventHandler\\ArTodoHandler@todo',NULL,0,'List of todos for autoresponders');
INSERT IGNORE INTO `events` VALUES (NULL,'database_export','App\\EventHandler\\ArDbExportHandler@export',NULL,0,'Ignore ar_queue table when exporting database');

INSERT IGNORE INTO `ar_lists` VALUES (NULL,'2015-11-14 13:16:34','2015-12-30 17:21:40','All users','Sent to all registered users');
INSERT IGNORE INTO `ar_lists` VALUES (NULL,'2015-12-30 15:07:23','2015-12-30 17:21:27','Free users','Sent to users who have never paid once');
INSERT IGNORE INTO `ar_lists` VALUES (NULL,'2015-12-30 13:19:19','2015-12-30 17:21:12','Paid users','Sent to users who have paid at sometime');
INSERT IGNORE INTO `ar_lists` VALUES (NULL,'2015-12-30 15:22:47','2015-12-30 17:24:28','Inactive users','Sent to users who haven\'t been active since last week');
INSERT IGNORE INTO `ar_lists` VALUES (NULL,'2015-12-30 17:25:40','2015-12-30 17:25:40','Uploaders','Sent to users who have uploaded something (sample)');

INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'All users'), 'Find all users with email','SELECT user_id from users WHERE email is not null','positive');
INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'All users'), 'Ignore users who have already unsubscribed	','SELECT user_id from mail_unsubscribes where mail_type in (\'tip\', \'offer\')','negative');

INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'Free users'), 'Find all users who have never paid','SELECT user_id from USERS WHERE user_id NOT IN (SELECT DISTINCT `user_id` FROM `cart_logs` WHERE (`amount` > 0) and (`success` = \'y\'))','positive');
INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'Free users'), 'Ignore users who have already unsubscribed	','SELECT user_id from mail_unsubscribes where mail_type in (\'tip\', \'offer\')','negative');

INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'Paid users'), 'Find all users who have paid sometime','select distinct `user_id` from `cart_logs` where ((`amount` > 0) and (`success` = \'y\'))','positive');
INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'Paid users'), 'Ignore users who have already unsubscribed	','SELECT user_id from mail_unsubscribes where mail_type in (\'tip\', \'offer\')','negative');

INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'Inactive users'), 'Find users with activity in last 1 week','SELECT user_id from USERS WHERE user_id NOT IN (SELECT distinct user_id FROM user_activities where created_at > DATE_SUB(NOW(), INTERVAL 1 WEEK))','positive');
INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'Inactive users'), 'Ignore users who have already unsubscribed	','SELECT user_id from mail_unsubscribes where mail_type in (\'tip\', \'offer\')','negative');

INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'Uploaders'), 'Find all users with upload activity ','SELECT DISTINCT user_id FROM `user_activities` WHERE `event_name_id` IN (SELECT event_name_id FROM `event_names` WHERE `event_name` LIKE \'user_upload%\')\r\n','positive');
INSERT IGNORE INTO `ar_list_sqls` VALUES (NULL, (select ar_list_id from ar_lists where name = 'Uploaders'), 'Ignore users who have already unsubscribed	','SELECT user_id from mail_unsubscribes where mail_type in (\'tip\', \'offer\')','negative');