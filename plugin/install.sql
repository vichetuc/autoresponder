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
  KEY `send_at` (`send_at`)
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ar_lists` (
  `ar_list_id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ar_list_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `ar_list_sqls` (
  `ar_list_sql_id` int(11) NOT NULL AUTO_INCREMENT,
  `ar_list_id` int(11) NOT NULL,
  `sql` longtext NOT NULL,
  `type` enum('positive','negative') NOT NULL DEFAULT 'positive',
  PRIMARY KEY (`ar_list_sql_id`),
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
  KEY `send_at` (`send_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

INSERT IGNORE INTO `events` VALUES (NULL,'admin_menu_render','App\\EventHandler\\ArMenuHandler@menu',NULL,0,'Autoresponder menu in admin');
INSERT IGNORE INTO `events` VALUES (NULL,'todo_admin','App\\EventHandler\\ArTodoHandler@todo',NULL,0,'List of todos for autoresponders');
INSERT IGNORE INTO `events` VALUES (NULL,'database_export','App\\EventHandler\\ArDbExportHandler@export',NULL,0,'Ignore ar_queue table when exporting database');