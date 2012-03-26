<?php

require_once('include/MVC/View/views/view.detail.php');
include_once('modules/Administration/QuickRepairAndRebuild.php') ;

class zd_TicketsViewDetail extends ViewDetail {
  
  function zd_TicketsViewDetail() {
    parent::SugarView();
    
    $repair = new RepairAndClear();
    $repair->module_list = array('zd_Tickets');
    $repair->clearTpls();
  }
  
 	function preDisplay() {
    $metadataFile = $this->getMetaDataFile();
		$this->dv = new DetailView2();
		$this->dv->ss =& $this->ss;
		$this->dv->setup($this->module, $this->bean, $metadataFile, 'include/DetailView/DetailView.tpl');
		
		$column = array();
		$col = 0;
		foreach ($this->bean->fields as $field) {
		  if (!$field['system']) {
  		  $column[$col] = array(
          'customLabel' => $field['title'],
          'customCode' => (string)$field['display'],
        );
        if ($col == 1) {
          $this->dv->defs['panels']['default'][] = $column;
          $column = array();
          $col = 0;
        } else {
          $col = 1;
        }
      }
    }
    if ($col == 1) {
      $this->dv->defs['panels']['default'][] = $column;
    }
 	}

}
