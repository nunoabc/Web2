<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/SugarView.php');

class ViewSyncconfig extends SugarView
{
	protected function _getModuleTitleParams()
	{
	  global $mod_strings;
	    
    return array(
    	"<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
    	$mod_strings['LBL_ZENDESK_TICKET_SYNC'],
    );
  }

	public function preDisplay()
 	{
    global $current_user;
	    
	  if (!is_admin($current_user)) {
      sugar_die("No access");
	  }
  }
  
  private function getRecordFields($bean) {
    global $dictionary;
    global $app_list_strings;
	  
	  $fields = array();
	  
    foreach ($dictionary[$bean->object_name]['fields'] as $name => $field_meta) {
      if ($field_meta['type'] != 'link') {
        $field_name = translate($field_meta['vname'], $bean->module_dir);
        $field_name = preg_replace('/:\s*$/', '', $field_name);
        
        if ($field_name != '') {
          if ($field_meta['type'] == 'relate' && $field_meta['dbType'] == 'id') {
            $fields[$name] = $field_name . ' (references ' . get_singular_bean_name($field_meta['module']) . ')';
          } else {
            $fields[$name] = $field_name;
          }
        }

        if ($field_meta['type'] == 'meta') {
          
        }
      }
    }
    
    asort($fields);
    return $fields;
  }

	public function display()
	{
	  global $dictionary;
	  global $current_user;
    global $beanList, $beanFiles;
    
    global $app_list_strings;
    
		
    echo $this->getModuleTitle();

    $this->ss->assign("RETURN_MODULE", "Administration");
    $this->ss->assign("RETURN_ACTION", "index");

		$admin = new Administration();
    $admin->retrieveSettings('system');
    $this->ss->assign('system_zendesk_status_map', $admin->settings['system_zendesk_status_map']);
    $this->ss->assign('system_zendesk_status_map_0', $admin->settings['system_zendesk_status_map_0']);
    $this->ss->assign('system_zendesk_status_map_1', $admin->settings['system_zendesk_status_map_1']);
    $this->ss->assign('system_zendesk_status_map_2', $admin->settings['system_zendesk_status_map_2']);
    $this->ss->assign('system_zendesk_status_map_3', $admin->settings['system_zendesk_status_map_3']);
    $this->ss->assign('system_zendesk_status_map_4', $admin->settings['system_zendesk_status_map_4']);
    $this->ss->assign('system_zendesk_type_map', $admin->settings['system_zendesk_type_map']);
    $this->ss->assign('system_zendesk_type_map_0', $admin->settings['system_zendesk_type_map_0']);
    $this->ss->assign('system_zendesk_type_map_1', $admin->settings['system_zendesk_type_map_1']);
    $this->ss->assign('system_zendesk_type_map_2', $admin->settings['system_zendesk_type_map_2']);
    $this->ss->assign('system_zendesk_type_map_3', $admin->settings['system_zendesk_type_map_3']);
    $this->ss->assign('system_zendesk_type_map_4', $admin->settings['system_zendesk_type_map_4']);
    $this->ss->assign('system_zendesk_priority_map', $admin->settings['system_zendesk_priority_map']);
    $this->ss->assign('system_zendesk_priority_map_0', $admin->settings['system_zendesk_priority_map_0']);
    $this->ss->assign('system_zendesk_priority_map_1', $admin->settings['system_zendesk_priority_map_1']);
    $this->ss->assign('system_zendesk_priority_map_2', $admin->settings['system_zendesk_priority_map_2']);
    $this->ss->assign('system_zendesk_priority_map_3', $admin->settings['system_zendesk_priority_map_3']);
    $this->ss->assign('system_zendesk_priority_map_4', $admin->settings['system_zendesk_priority_map_4']);
    
	  $fields = array();
	  $dropdowns = array();
	  $bean = new aCase();
    
    foreach ($dictionary[$bean->object_name]['fields'] as $name => $field_meta) {
      if ($field_meta['type'] != 'link') {
        $field_name = translate($field_meta['vname'], $bean->module_dir);
        $field_name = preg_replace('/:\s*$/', '', $field_name);
        
        if ($field_name != '') {
          if ($field_meta['type'] == 'enum' || $field_meta['type'] == 'varchar' || $field_meta['type'] == 'text' || $field_meta['type'] == 'name') {
            $fields[$name] = $field_name;
          }

          if ($field_meta['type'] == 'enum') {
            $dropdowns[$name] = $app_list_strings[$field_meta['options']];
          }
        }
      }
    }
    
    asort($fields);
    
    $this->ss->assign('case_fields', $fields);
    $this->ss->assign('dropdowns', $dropdowns);
    
    $this->ss->display('modules/zd_Tickets/tpls/syncconfig.tpl');
  }
}

function namecmp($a, $b) {
  return strcmp($a["name"], $b["name"]);
}