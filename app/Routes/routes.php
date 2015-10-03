<?php

use Minute\Routing\Router;

/** @var Router $router */

$router->get('/admin/autoresponder/campaigns', 'Admin/ViewCampaigns.php', 'admin', 'ar_campaigns[][20]', 'ar_lists[ar_campaigns.ar_list_id]', 'ar_messages[ar_campaigns.ar_campaign_id][1]')
       ->setPermissions('Arcampaign::$readPermission = "admin"');
$router->post('/admin/autoresponder/campaigns', 'Admin/ViewCampaigns.php@save', 'admin', '$model[Arcampaign]');

$router->get('/admin/autoresponder/campaigns/edit/:ar_campaign_id', 'Admin/EditCampaigns.php', 'admin', 'ar_campaign[ar_campaign_id][]', 'ar_messages[ar_campaign.ar_campaign_id][]', 'mail[ar_messages.mail_id]', 'ar_lists[ar_campaign.ar_list_id]', 'mails[][999] as all_mails', 'ar_lists[][999] as all_lists')
       ->setPermissions('Arcampaign::$readPermission = "admin";Mail::$readPermission = "admin";ArList::$readPermission = "admin";');
$router->post('/admin/autoresponder/campaigns/edit/:ar_campaign_id', 'Admin/EditCampaigns.php@save', 'admin', '$model[Arcampaign,ArMessage]');

$router->get('/admin/autoresponder/broadcast', 'Admin/ViewBroadcasts.php', 'admin', 'ar_broadcasts[][99] as broadcasts', 'mail[broadcasts.mail_id] as mail', 'ar_lists[broadcasts.ar_list_id] as list')
       ->setPermissions('ArBroadcast::$readPermission = "admin"');
$router->post('/admin/autoresponder/broadcast', 'Admin/ViewBroadcasts.php@save', 'admin', '$model[ArBroadcast]');

$router->get('/admin/autoresponder/broadcast/edit/:ar_broadcast_id', 'Admin/EditBroadcast.php', 'admin', 'ar_broadcasts[ar_broadcast_id][1]', 'mails[][999] as all_mails order by created_at desc', 'ar_lists[][999] as all_lists')
       ->setPermissions('ArBroadcast::$readPermission = "admin";Mail::$readPermission = "admin";ArList::$readPermission = "admin";');
$router->post('/admin/autoresponder/broadcast/edit/:ar_broadcast_id', 'Admin/EditBroadcast.php@save', 'admin', '$model[ArBroadcast]');

$router->get('/admin/autoresponder/lists', 'Admin/ViewLists.php', 'admin', 'ar_lists[][]', 'ar_list_sqls[ar_lists.ar_list_id][1] as sqls')->setPermissions('ArList::$readPermission = "admin"');
$router->get('/admin/autoresponder/lists/edit/:ar_list_id', 'Admin/EditList.php', 'admin', 'ar_lists[ar_list_id][1]', 'ar_list_sqls[ar_lists.ar_list_id][99] as sqls')
       ->setPermissions('ArList::$readPermission = "admin"');
$router->post('/admin/autoresponder/lists/edit/:ar_list_id', 'Admin/EditList.php@save', 'admin', '$model[ArList,ArListSql]');
$router->get('/admin/autoresponder/lists/download/:ar_list_id', 'Admin/DownloadList.php', 'admin');

