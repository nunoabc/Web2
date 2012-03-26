<?php

class ZendeskWidgetConfig {

  var $admin;

	function ZendeskWidgetConfig()
	{
		$this->admin = new Administration();
    $this->admin->retrieveSettings('system');
    
    $configured = false;
    foreach ($this->admin->settings as $name => $value) {
      if (preg_match('/^system_zendesk_widget_/', $name)) {
        $configured = true;
        break;
      }
    }
    
    if (!$configured) {
      $this->admin->settings['system_zendesk_widget_Accounts'] = 'phone_office,website';
      $this->admin->settings['system_zendesk_widget_Contacts'] = 'account_id,phone_work,birthdate';
      $this->admin->settings['system_zendesk_widget_Leads'] = 'status,opportunity_amount';
    }
	}
	
	function getFields($module) {
	  return $this->admin->settings['system_zendesk_widget_' . $module];
	}

}
