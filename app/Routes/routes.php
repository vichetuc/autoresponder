<?php

use Minute\Routing\Router;

/** @var Router $router */

$router->get('/admin/autoresponder/campaigns', 'Admin/ViewCampaigns.php', 'admin', 'ar_campaigns[][10] ORDER BY created_at DESC', 'ar_lists[ar_campaigns.ar_list_id]', 'ar_messages[ar_campaigns.ar_campaign_id][1]')
       ->setReadPermissions('Arcampaign', 'admin')->allModifiersEnabled(true);
$router->post('/admin/autoresponder/campaigns', null, 'admin', '$model[Arcampaign]')
       ->setAllPermissions('Arcampaign', 'admin')->setDeleteCascade('Arcampaign', ['Armessage']);

$router->get('/admin/autoresponder/campaigns/edit/:ar_campaign_id', 'Admin/EditCampaigns.php', 'admin', 'ar_campaign[ar_campaign_id][]',
    'ar_messages[ar_campaign.ar_campaign_id][]', 'mail[ar_messages.mail_id]', 'ar_lists[ar_campaign.ar_list_id]', 'mails[][999] as all_mails', 'ar_lists[][999] as all_lists')
       ->setReadPermissions(['Arcampaign', 'Mail', 'ArList'], 'admin');
$router->post('/admin/autoresponder/campaigns/edit/:ar_campaign_id', null, 'admin', '$model[Arcampaign,ArMessage]')
       ->setAllPermissions(['Arcampaign', 'ArMessage'], 'admin');

$router->get('/admin/autoresponder/broadcast', 'Admin/ViewBroadcasts.php', 'admin', 'ar_broadcasts[][10] as broadcasts ORDER by send_at DESC', 'mail[broadcasts.mail_id] as mail',
    'ar_lists[broadcasts.ar_list_id] as list')->setReadPermissions('Arbroadcast', 'admin')->allModifiersEnabled(true);
$router->post('/admin/autoresponder/broadcast', null, 'admin', '$model[Arbroadcast]')->setAllPermissions('Arbroadcast', 'admin');

$router->get('/admin/autoresponder/broadcast/edit/:ar_broadcast_id', 'Admin/EditBroadcast.php', 'admin', 'ar_broadcasts[ar_broadcast_id][1]', 'mails[][999] as all_mails order by created_at desc', 'ar_lists[][999] as all_lists')
       ->setReadPermissions(['ArBroadcast', 'Mail', 'ArList'], 'admin');
$router->post('/admin/autoresponder/broadcast/edit/:ar_broadcast_id', null, 'admin', '$model[ArBroadcast]')
       ->setAllPermissions('ArBroadcast', 'admin');

$router->get('/admin/autoresponder/lists', 'Admin/ViewLists.php', 'admin', 'ar_lists[][10] order by created_at DESC', 'ar_list_sqls[ar_lists.ar_list_id][1] as sqls')
       ->setReadPermissions('ArList', 'admin')->allModifiersEnabled(true);
$router->post('/admin/autoresponder/lists', null, 'admin', '$model[ArList]')
       ->setAllPermissions('ArList', 'admin');

$router->get('/admin/autoresponder/lists/edit/:ar_list_id', 'Admin/EditList.php', 'admin', 'ar_lists[ar_list_id][1]', 'ar_list_sqls[ar_lists.ar_list_id][99] as sqls')
       ->setReadPermissions('ArList', 'admin');
$router->post('/admin/autoresponder/lists/edit/:ar_list_id', null, 'admin', '$model[ArList,ArListSql]')
       ->setAllPermissions(['ArList', 'ArListSql'], 'admin');

$router->get('/admin/autoresponder/lists/download/:ar_list_id', 'Admin/DownloadList.php', 'admin');