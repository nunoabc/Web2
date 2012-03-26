<?php

class ZendeskHelper {

  var $admin;

	function ZendeskHelper()
	{
		$this->admin = new Administration();
    $this->admin->retrieveSettings('system');
	}

	function getConfigValue($name, $val_empty = null) {
    global $current_user;

    $personal_value = $this->admin->settings['system_zendesk_' . $name . '__' . $current_user->user_name];
    if ($personal_value != null && $personal_value != '') {
      return $personal_value;
    }
    
    $global_value = $this->admin->settings['system_zendesk_' . $name];
    if ($global_value != null && $global_value != '') {
      return $global_value;
    }
    
	  return $val_empty;
	}

	function getPersonalConfigValue($name, $val_empty = null) {
    global $current_user;
    
    $personal_value = $this->admin->settings['system_zendesk_' . $name . '__' . $current_user->user_name];
    if ($personal_value != null && $personal_value != '') {
      return $personal_value;
    }

	  return $val_empty;
	}

	function getGlobalConfigValue($name, $val_empty = null) {
    global $current_user;

    $global_value = $this->admin->settings['system_zendesk_' . $name];
    if (isset($global_value) && $global_value != '') {
      return $global_value;
    }
    
	  return $val_empty;
	}

  function getStatusFilterOptions() {
    return array(
      'any' => 'Show all',
      'enew' => 'New',
      'eopen' => 'Open',
      'epending' => 'Pending',
      'esolved' => 'Solved',
      'eclosed' => 'Closed',
      'gnew' => '> New',
      'gopen' => '> Open',
      'gpending' => '> Pending',
      'gsolved' => '> Solved',
      'lopen' => '< Open',
      'lpending' => '< Pending',
      'lsolved' => '< Solved',
      'lclosed' => '< Closed',
    );
  }

  function getPriorityFilterOptions() {
    return array(
      'any' => 'Show all',
      'elow' => 'Low',
      'enormal' => 'Normal',
      'ehigh' => 'High',
      'eurgent' => 'Urgent',
      'glow' => '> Low',
      'gnormal' => '> Normal',
      'ghigh' => '> High',
      'lnormal' => '< Normal',
      'lhigh' => '< High',
      'lurgent' => '< Urgent',
    );
  }

  function getTypeFilterOptions() {
    return array(
      'any' => 'Show all',
      'eproblem' => 'Problem',
      'eincident' => 'Incident',
      'equestion' => 'Question',
      'etask' => 'Task',
    );
  }

  function getColumnOptions() {
    return array(
      'subject' => 'Subject',
      'status' => 'Status',
      'priority' => 'Priority',
      'ticket_type' => 'Type',
      'created_at' => 'Created At',
      'updated_at' => 'Updated At',
    );
  }

}
