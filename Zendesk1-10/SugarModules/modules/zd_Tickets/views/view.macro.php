<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/SugarView.php');

class ViewMacro extends SugarView
{
  var $connection;
  var $bean;
  
  function ViewMacro() {
    parent::SugarView();
    $this->connection = getZendeskConnection();
    $this->bean = new zd_Tickets();
    $this->bean->load_from_db($_REQUEST['record']);
  }
  
	public function display()
	{
    $ticket = $this->bean->getTicketData();
    $ticket['query']['ticket_id'] = $this->bean->nice_id ? $this->bean->nice_id : 0;
    $this->connection->set_output('json');
	  $json = $this->connection->post('macros/' . $_REQUEST['macro_id'] . '/apply', $ticket);
	  echo $json;
  }
}
