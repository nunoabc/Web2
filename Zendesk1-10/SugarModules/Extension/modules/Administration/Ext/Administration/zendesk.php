<?php

$admin_option_defs = array();
$admin_option_defs['zd_Tickets']['credentials'] = array(
  'zd_Tickets',
  'LBL_ZENDESK_CREDENTIALS',
  'LBL_ZENDESK_CREDENTIALS_DESC',
  './index.php?module=zd_Tickets&action=config'
);
$admin_option_defs['zd_Tickets']['crmdata'] = array(
  'zd_Tickets',
  'LBL_ZENDESK_CRM_DATA',
  'LBL_ZENDESK_CRM_DATA_DESC',
  './index.php?module=zd_Tickets&action=crmdataconfig'
);
$admin_option_defs['zd_Tickets']['sync'] = array(
  'zd_Tickets',
  'LBL_ZENDESK_TICKET_SYNC',
  'LBL_ZENDESK_TICKET_SYNC_DESC',
  './index.php?module=zd_Tickets&action=syncconfig'
);

$admin_group_header[] = array('LBL_ZENDESK', '', false, $admin_option_defs, 'LBL_ZENDESK_DESC');
