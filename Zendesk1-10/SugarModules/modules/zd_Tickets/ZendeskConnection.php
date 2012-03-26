<?php

require_once('modules/zd_Tickets/Zendesk.php');
require_once('modules/zd_Tickets/ZendeskHelper.php');

function getZendeskConnection() {
  global $current_user;
  
  $read_only = true;
  $zendesk_https = false;

  $zendesk_helper = new ZendeskHelper();

  $admin = new Administration();
  $admin->retrieveSettings('system');

  if ($admin->settings['system_zendesk_instance']) {
    $zendesk_instance = $admin->settings['system_zendesk_instance'];
  } else {
    throw new Exception('Zendesk credentials not configured');
  }
  if ($admin->settings['system_zendesk_https']) {
    $zendesk_https = true;
  }
  
  $personal_login = $zendesk_helper->getPersonalConfigValue('login');
  if ($personal_login && $personal_login != '') {
    $read_only = false;
    $zendesk_login = $personal_login;
    $zendesk_password = $zendesk_helper->getPersonalConfigValue('password');
  } else {
    $zendesk_login = $zendesk_helper->getGlobalConfigValue('login');
    $zendesk_password = $zendesk_helper->getGlobalConfigValue('password');
  }

  $c = new Zendesk($zendesk_instance, $zendesk_login, $zendesk_password, true, $zendesk_https);
  $c->read_only = $read_only;
  return $c;
}
