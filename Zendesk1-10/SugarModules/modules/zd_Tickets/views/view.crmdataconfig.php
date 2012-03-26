<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/SugarView.php');
require_once('modules/zd_Tickets/ZendeskWidgetConfig.php');

class ViewCrmdataconfig extends SugarView
{
  var $object_list = array(
    'SavedReport',
    'Lead',
    'Contact',
    'Account',
    'Opportunity',
    'EmailCampaign',
    'SavedReport',
    'Quote',
    'Product',
    'Document',
    'aCase',
    'Project',
    'Bug',
    'Forecast',
    'Bug',
    'Contract',
    'KBDocument',
    'User',
  );
  
	protected function _getModuleTitleParams()
	{
	  global $mod_strings;
	    
    return array(
    	"<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
    	$mod_strings['LBL_ZENDESK_CRM_DATA'],
    );
  }

	public function preDisplay()
 	{
    global $current_user;
	    
	  if (!is_admin($current_user)) {
      sugar_die("No access");
	  }
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
  
  private function getRecordFields($bean) {
    global $dictionary;
    global $app_list_strings;

	  $fields = array();

    foreach ($dictionary[$bean->object_name]['fields'] as $name => $field_meta) {
      if ($field_meta['type'] != 'link') {
        $field_name = translate($field_meta['vname'], $bean->module_dir);
        $field_name = preg_replace('/:\s*$/', '', $field_name);
        
        if ($field_name != '') {
          if (($field_meta['type'] == 'relate' && $field_meta['dbType'] == 'id')) {
            $fields[$name] = $field_name . ' (references ' . get_singular_bean_name($field_meta['module']) . ')';
          } else {
            $fields[$name] = $field_name;
          }
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

    $config = new ZendeskWidgetConfig();
    
    $records = array();
    
    foreach (query_module_access_list($current_user) as $module) {
      if ($object_name = $beanList[$module]) {
        if (!in_array($object_name, array('zd_Tickets'))) {
          $bean = $this->getBeanInstance($object_name);
          $field_config = $config->getFields($module);
          $records[$module] = array(
            'key' => $module,
            'name' => $app_list_strings['moduleList'][$module],
            'enabled' => ($field_config != null && $field_config != ''),
            'fields' => $this->getRecordFields($bean),
            'current' => $field_config,
          );
        }
      }
    }
    
    usort($records, "namecmp");
    $this->ss->assign('records', $records);
    
    $this->ss->display('modules/zd_Tickets/tpls/crmdataconfig.tpl');
  }
}

function namecmp($a, $b) {
  return strcmp($a["name"], $b["name"]);
}