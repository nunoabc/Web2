<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/SugarView.php');
require_once('modules/zd_Tickets/ZendeskHelper.php');

class ViewConfig extends SugarView
{
	protected function _getModuleTitleParams()
	{
	  global $mod_strings;
	    
    return array(
    	"<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
    	$mod_strings['LBL_ZENDESK_CREDENTIALS'],
    );
  }

	public function preDisplay()
 	{
    global $current_user;
	    
	  if (!is_admin($current_user)) {
      sugar_die("No access");
	  }
  }

	public function display()
	{
    echo $this->getModuleTitle();

    $this->ss->assign("RETURN_MODULE", "Administration");
    $this->ss->assign("RETURN_ACTION", "index");

    $zendesk_helper = new ZendeskHelper();

		$admin = new Administration();
    $admin->retrieveSettings('zendesk');

    $this->ss->assign('zendesk_instance', $admin->settings['system_zendesk_instance']);
    $this->ss->assign('zendesk_https', $admin->settings['system_zendesk_https']);
    $this->ss->assign("zendesk_https_checkbox", ($admin->settings['system_zendesk_https']) ? "checked='checked'" : "");
    $this->ss->assign('zendesk_login', $admin->settings['system_zendesk_login']);
    $this->ss->assign('use_account_name', $zendesk_helper->getGlobalConfigValue('use_account_name'));
    
    $this->ss->assign('per_page', $zendesk_helper->getGlobalConfigValue('per_page', '6'));
	  $this->ss->assign('sort', $zendesk_helper->getGlobalConfigValue('sort', '1'));
	  $this->ss->assign('order_by', $zendesk_helper->getGlobalConfigValue('order_by', 'priority'));
	  $this->ss->assign('status_filter', $zendesk_helper->getGlobalConfigValue('status_filter', 'lsolved'));
	  $this->ss->assign('priority_filter', $zendesk_helper->getGlobalConfigValue('priority_filter', 'any'));
	  $this->ss->assign('type_filter', $zendesk_helper->getGlobalConfigValue('type_filter', 'any'));

	  $this->ss->assign('statusoptions', $zendesk_helper->getStatusFilterOptions());
	  $this->ss->assign('priorityoptions', $zendesk_helper->getPriorityFilterOptions());
	  $this->ss->assign('typeoptions', $zendesk_helper->getTypeFilterOptions());
	  $this->ss->assign('columns', $zendesk_helper->getColumnOptions());
	  
    $this->ss->display('modules/zd_Tickets/tpls/config.tpl');
  }
}
