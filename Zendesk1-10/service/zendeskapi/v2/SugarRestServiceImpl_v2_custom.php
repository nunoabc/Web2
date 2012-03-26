<?php

require_once('service/core/SugarRestServiceImpl.php');
require_once('modules/zd_Tickets/ZendeskWidgetConfig.php');

class SugarRestServiceImpl_v2_custom extends SugarRestServiceImpl 
{

	function syncticket($session, $data)
	{
	  $GLOBALS['log']->error("syncticket");
		
	  if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'Cases', 'write', 'no_access', new SoapError())) {
	    echo "No access";
  		return;
  	}
	  
	  $admin = new Administration();
    $admin->retrieveSettings('system');

	  $ticket = new SimpleXMLElement(str_replace('!AMP!', '&', $data));

	  $GLOBALS['log']->error("find or create case");
    $case = new aCase();
    $nice_id = (int)$ticket->{'nice-id'};
    if ($case = $case->retrieve_by_string_fields(array('zendesk_ticket_id_c' => $nice_id))) {
  	  $GLOBALS['log']->error("found case " . $case->id);
    } else {
      $GLOBALS['log']->error("creating new case");
      $case = new aCase();
    }

    $case->zendesk_ticket_id_c = $nice_id;
    $case->name = $ticket->subject;
    
    if ($admin->settings['system_zendesk_status_map']) {
      $mapped_to = $admin->settings['system_zendesk_status_map'];
      $mapped_field = 'system_zendesk_status_map_' . $ticket->{'status-id'};
      $case->$mapped_to = $admin->settings[$mapped_field];
    }
    
    if ($admin->settings['system_zendesk_priority_map']) {
      $mapped_to = $admin->settings['system_zendesk_priority_map'];
      $mapped_field = 'system_zendesk_priority_map_' . $ticket->{'priority-id'};
      $case->$mapped_to = $admin->settings[$mapped_field];
    }
    
    if ($admin->settings['system_zendesk_type_map']) {
      $mapped_to = $admin->settings['system_zendesk_type_map'];
      $mapped_field = 'system_zendesk_type_map_' . $ticket->{'ticket-type-id'};
      $case->$mapped_to = $admin->settings[$mapped_field];
    }
    
    $requester = $ticket->requester;
    if ($requester->organization) {
      $account = new Account();
      if ($account = $account->retrieve_by_string_fields(array('name' => $requester->organization->name))) {
    	  $GLOBALS['log']->error("found account " . $account->id);
      } else {
        $account = new Account();
        $account->name = $requester->organization->name;
        $account->save();
    	  $GLOBALS['log']->error("created account " . $account->id);
      }
      $case->account_id = $account->id;
    }
    
    $GLOBALS['log']->error("save case with zendesk_ticket_id_c " . $case->zendesk_ticket_id_c);
    $case->save();

    $GLOBALS['log']->error("associate contacts");
		if ($case->load_relationship('contacts')) {
      $ea = new EmailAddress();
      $ids = $ea->getRelatedId($requester->email, 'contacts');
      if (!empty($ids)) {
  			$case->contacts->add($ids);
      } else {
        $contact = new Contact();
        $contact->email1 = $requester->email;
        $names = split(' ', $requester->name);
        $contact->last_name = $names[count($names)-1];
        $contact->first_name = join(' ', array_slice($names, 0, -1));
        $contact->save();
  			$case->contacts->add(array($contact->id));
      }
    }

    $GLOBALS['log']->error("syncticket done");
    
    echo "OK " . $case->id;
	}

  private function getBeanInstance($beanType) {
    /*switch ($beanType) {
      case 'SavedReport': return new SavedReport(); break;
      case 'Lead': return new Lead(); break;
      case 'Contact': return new Contact(); break;
      case 'Account': return new Account(); break;
      case 'Opportunity': return new Opportunity(); break;
      case 'EmailCampaign': return new EmailCampaign(); break;
      case 'SavedReport': return new SavedReport(); break;
      case 'Quote': return new Quote(); break;
      case 'Product': return new Product(); break;
      case 'Document': return new Document(); break;
      case 'aCase': return new aCase(); break;
      case 'Project': return new Project(); break;
      case 'Bug': return new Bug(); break;
      case 'Forecast': return new Forecast(); break;
      case 'Bug': return new Bug(); break;
      case 'Contract': return new Contract(); break;
      case 'KBDocument': return new KBDocument(); break;
      case 'User': return new User(); break;
      default : return null;
    }*/
    $something['x'] = $beanType;
    return new $something['x']();
  }


	function crmdata($session, $email)
	{
	  global $dictionary;
	  global $app_list_strings;
	  global $current_user;
    global $beanList, $beanFiles;
	  
	  if (!self::$helperObject->checkSessionAndModuleAccess($session, 'invalid_session', 'Accounts', 'read', 'no_access', new SoapError())) {
	    echo "No access";
  		return;
  	}
	  
	  $field_list = array();
    $config = new ZendeskWidgetConfig();
    
    foreach (query_module_access_list($current_user) as $module) {
      if ($object_name = $beanList[$module]) {
        $field_config = $config->getFields($module);
        if ($field_config != null && $field_config != '') {
          $field_list[$object_name] = $field_config;
        }
      }
    }

    $root = new SimpleXMLElement('<records></records>');

    $ea = new EmailAddress();
    $beans = $ea->getBeansByEmailAddress($email);

  	while ($bean = array_pop($beans)) {
      if (isset($field_list[$bean->object_name])) {
        $record = $root->addChild('record');
        $record->addChild('id', $bean->id);
        $record->addChild('url', htmlspecialchars("/index.php?module={$bean->module_dir}&action=DetailView&record={$bean->id}"));
        $record->addChild('label', $bean->name);
        $record->addChild('record_type', $bean->object_name);
        $fields = $record->addChild('fields');
        foreach (split(',', $field_list[$bean->object_name]) as $name) {        
          $field_meta = $dictionary[$bean->object_name]['fields'][$name];
          if ($field_meta['type'] == 'relate') {
            if ($field_meta['dbType'] == 'id') {
              $beanType = get_singular_bean_name($field_meta['module']);
              $newBean = $this->getBeanInstance($beanType);
              $newBean->retrieve($bean->$name);
              $beans[] = $newBean;
              continue;
            }
          }

          $field = $fields->addChild('field');
          $field->addChild('label', translate($field_meta['vname'], $bean->module_dir));
          $value = $bean->$name;
          if ($field_meta['type'] == 'enum') {
            $value = $app_list_strings[$field_meta['options']][$bean->$name];
          }
          $field->addChild('value', $value);
        }
      }
  	}

    return $root->asXML();
	}

	function version()
	{
	  echo "1.10";
	}

}
