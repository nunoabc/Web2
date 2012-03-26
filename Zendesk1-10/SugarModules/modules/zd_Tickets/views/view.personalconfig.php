<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/SugarView.php');
require_once('modules/zd_Tickets/ZendeskHelper.php');
require_once('modules/zd_Tickets/ZendeskConnection.php');

class ViewPersonalconfig extends SugarView
{
	protected function _getModuleTitleParams($bTitle=false)
	{
	  global $mod_strings;

    return array(
      $this->_getModuleTitleListParam($bTitle),
    	$mod_strings['LBL_ZENDESK_PERSONAL_SETTINGS'],
    );
  }

	public function display()
	{
	  global $current_user;
	  
    echo $this->getModuleTitle();

    $this->ss->assign("RETURN_MODULE", $_REQUEST['return_module']);
    $this->ss->assign("RETURN_ACTION", $_REQUEST['return_action']);
    $this->ss->assign("RETURN_ID", $_REQUEST['return_id']);

    $zendesk_helper = new ZendeskHelper();

    $zendesk_instance = $zendesk_helper->getConfigValue('instance');
    if ($zendesk_instance == null) {
      echo "Thank you for installing Zendesk for SugarCRM.<br/>";
      echo "Administrators, please start with <a href='index.php?module=zd_Tickets&action=config'>configuring your Zendesk credentials</a>.";
    } else {
  	  $connection = getZendeskConnection();

      $this->ss->assign('zendesk_instance', $connection->url());

      $this->ss->assign('current_user_id', '_' . $current_user->user_name);
      $this->ss->assign('zendesk_login', $zendesk_helper->getPersonalConfigValue('login'));

  	  $this->ss->assign('per_page', $zendesk_helper->getConfigValue('per_page', '6'));
  	  $this->ss->assign('sort', $zendesk_helper->getConfigValue('sort', '1'));
  	  $this->ss->assign('order_by', $zendesk_helper->getConfigValue('order_by', 'priority'));
  	  $this->ss->assign('status_filter', $zendesk_helper->getConfigValue('status_filter', 'lsolved'));
  	  $this->ss->assign('priority_filter', $zendesk_helper->getConfigValue('priority_filter', 'any'));
  	  $this->ss->assign('type_filter', $zendesk_helper->getConfigValue('type_filter', 'any'));

  	  $this->ss->assign('statusoptions', $zendesk_helper->getStatusFilterOptions());
  	  $this->ss->assign('priorityoptions', $zendesk_helper->getPriorityFilterOptions());
  	  $this->ss->assign('typeoptions', $zendesk_helper->getTypeFilterOptions());
  	  $this->ss->assign('columns', $zendesk_helper->getColumnOptions());

      $this->ss->display('modules/zd_Tickets/tpls/personalconfig.tpl');
    }
  }
}
