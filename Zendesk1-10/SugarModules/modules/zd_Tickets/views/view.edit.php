<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.edit.php');

class zd_TicketsViewEdit extends ViewEdit {
  
  var $referrer;
  var $requesters;
  
  function zd_TicketsViewEdit() {
    parent::ViewEdit();
    
    if ($_REQUEST['return_module'] && $_REQUEST['return_id']) {
      $this->requesters = array();
      switch ($_REQUEST['return_module']) {
        case 'Accounts':
          $this->referrer = new Account();
          $this->referrer->retrieve($_REQUEST['return_id']);
          $count = 0;
          foreach ($this->referrer->get_contacts() as $contact) {
            $this->requesters[$contact->email1] = $contact->name;
          }
          break;
        case 'Leads':
          $this->referrer = new Lead();
          $this->referrer->retrieve($_REQUEST['return_id']);
          $this->requesters = array($this->referrer->email1 => $this->referrer->name);
          break;
        case 'Contacts':
          $this->referrer = new Contact();
          $this->referrer->retrieve($_REQUEST['return_id']);
          $this->requesters = array($this->referrer->email1 => $this->referrer->name);
          break;
        case 'Opportunities':
          $this->referrer = new Opportunity();
          $this->referrer->retrieve($_REQUEST['return_id']);
          
          $this->referrer->load_relationship('contacts');
      		$query_array = $this->referrer->contacts->getQuery(true);
      		$query_array['select'] = "SELECT contacts.id, contacts.first_name, contacts.last_name, contacts.title, contacts.phone_work, opportunities_contacts.contact_role as opportunity_role, opportunities_contacts.id as opportunity_rel_id ";
      		$query = '';
      		foreach ($query_array as $qstring) {
      			$query .= ' ' . $qstring;
      		}
      		$temp = array('id', 'first_name', 'last_name', 'title', 'phone_work', 'opportunity_role', 'opportunity_rel_id');
      		$contacts = $this->referrer->build_related_list2($query, new Contact(), $temp);
          foreach ($contacts as $contact) {
            $contact->retrieve();
            $this->requesters[$contact->email1] = $contact->name;
          }
          break;
      }
      if (sizeof($this->requesters) == 0) {
        $this->requesters = array($this->referrer->email1 => $this->referrer->name);
      }
    }
  }
  
	public function display()
	{
	  global $dictionary;
	  global $app_list_strings;
	  global $timedate;
	  
	  echo $this->getModuleTitle();
	  
    $this->ss->assign("RETURN_MODULE", $_REQUEST['return_module']);
    $this->ss->assign("RETURN_ACTION", $_REQUEST['return_action']);
    $this->ss->assign("RETURN_ID", $_REQUEST['return_id']);
    
	  if ($this->bean->isReadOnly()) {
	    $link = 'index.php?module=zd_Tickets&action=personalconfig&return_module='.$_REQUEST['return_module'].'&return_id='.$_REQUEST['return_id'].'&return_action='.$_REQUEST['return_action'];
	    echo "Please <a href='$link'>configure your own Zendesk agent credentials</a> to edit or create tickets.";
	  } else if ($this->bean->isClosed()) {
	    echo "Cannot edit a closed ticket.";
	  } else {
      if (empty($this->bean->nice_id)) {
        $this->ss->assign('new', true);
        $this->bean->initFromZendesk();
      }
	    $this->ss->assign('referrer', $this->referrer);
	    $this->ss->assign('requesters', $this->requesters);
      $this->ss->assign('dropdowns', $app_list_strings);
  	  $this->ss->assign('datefmt', $timedate->get_cal_date_format());

      $this->ss->assign('bean', $this->bean);
	    $this->ss->assign('zendesk_fields', $this->bean->fields);

      $this->ss->display('modules/zd_Tickets/tpls/EditView.tpl');
    }
  }

}
