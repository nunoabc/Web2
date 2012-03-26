<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/SugarView.php');
require_once('modules/zd_Tickets/ZendeskHelper.php');

class ViewAssociated extends SugarView
{
  var $focus;
  var $connection;
  var $error_message;
  var $result_count;
  var $pages;

  var $per_page;
  var $page;
  var $sort;
  var $order_by;
  var $status_filter;
  var $priority_filter;
  var $type_filter;
  
  function ViewAssociated() {
    parent::SugarView();
    
    $zendesk_helper = new ZendeskHelper();
    
    $this->focus = new $_REQUEST['focus']();
    $this->focus = $this->focus->retrieve($_REQUEST['rec']);
  
    $this->per_page = $zendesk_helper->getConfigValue('per_page', 6);
    $this->sort = $zendesk_helper->getConfigValue('sort', '1')==1 ? 'desc' : 'asc';
    $this->order_by = $zendesk_helper->getConfigValue('order_by', 'priority');
    $this->status_filter = $zendesk_helper->getConfigValue('status_filter', 'any');
    $this->priority_filter = $zendesk_helper->getConfigValue('priority_filter', 'lsolved');
    $this->type_filter = $zendesk_helper->getConfigValue('type_filter', 'any');
  
    if ($_REQUEST['page']) {
      $this->page = $_REQUEST['page'];
    }
    if ($_REQUEST['sort']) {
      $this->sort = $_REQUEST['sort'];
    }
    
    if ($_REQUEST['order_by']) {
      $this->order_by = $_REQUEST['order_by'];
    }
    if ($_REQUEST['status_filter']) {
      $this->status_filter = $_REQUEST['status_filter'];
    }
    if ($_REQUEST['priority_filter']) {
      $this->priority_filter = $_REQUEST['priority_filter'];
    }
    if ($_REQUEST['type_filter']) {
      $this->type_filter = $_REQUEST['type_filter'];
    }

    $this->error_message = null;
    try {
  		$this->connection = getZendeskConnection();
		} catch (Exception $e) {
      $this->error_message = $e->getMessage();
    }
  }
  
  private function getZendeskQuery() {
    $zendesk_helper = new ZendeskHelper();
    
    $search_query = 'type:ticket';
    
    $op_map = array('e' => ':', 'g' => '>', 'l' => '<');
    
    if ($this->status_filter != null && $this->status_filter != "any") {
      $search_query .= ' status' . $op_map[$this->status_filter[0]] . substr($this->status_filter, 1);
    }
    if ($this->priority_filter != null && $this->priority_filter != "any") {
      $search_query .= ' priority' . $op_map[$this->priority_filter[0]] . substr($this->priority_filter, 1);
    }
    if ($this->type_filter != null && $this->type_filter != "any") {
      $search_query .= ' ticket_type' . $op_map[$this->type_filter[0]] . substr($this->type_filter, 1);
    }
    
    switch ($this->focus->object_name) {
      case 'Contact':
      case 'Lead':
        $search_query .= " requester:{$this->focus->email1}";
        if ($this->focus->email2) {
          $search_query .= " requester:{$this->focus->email2}";
        }
        break;
      case 'Account':
        if ($this->focus->zendesk_organization_c != null && $this->focus->zendesk_organization_c != '') {
        $search_query .= " organization:\"{$this->focus->zendesk_organization_c}\"";   
        } else if ($zendesk_helper->getGlobalConfigValue('use_account_name')) {
          $search_query .= " organization:\"{$this->focus->name}\"";   
        } else {
          $count = 0;
          foreach ($this->focus->get_contacts() as $contact) {
            if ($contact->email1 && $contact->email1 != '') {
              $search_query .= " requester:{$contact->email1}";
              $count++;
            }
            if ($contact->email2) {
              $search_query .= " requester:{$contact->email2}";
              $count++;
            }
          }
          if ($count == 0) {
            $search_query .= " organization:\"{$this->focus->name}\"";
          }
        }
        break;
      case 'Opportunity':
        $this->focus->load_relationship('contacts');
    		$query_array = $this->focus->contacts->getQuery(true);

    		$query_array['select'] = "SELECT contacts.id, contacts.first_name, contacts.last_name, contacts.title, contacts.phone_work, opportunities_contacts.contact_role as opportunity_role, opportunities_contacts.id as opportunity_rel_id ";
    		$query = '';
    		foreach ($query_array as $qstring) {
    			$query .= ' ' . $qstring;
    		}
    		$temp = array('id', 'first_name', 'last_name', 'title', 'phone_work', 'opportunity_role', 'opportunity_rel_id');
    		$contacts = $this->focus->build_related_list2($query, new Contact(), $temp);

  		  $count = 0;
        foreach ($contacts as $contact) {
          $contact->retrieve();
          $search_query .= " requester:{$contact->email1}";
          if ($contact->email2) {
            $search_query .= " requester:{$contact->email2}";
          }
          $count++;
        }
        if ($count == 0) {
          $search_query .= " organization:\"{$this->focus->account_name}\"";
        }
        break;
      default:
        return "Cannot display tickets for " . $this->focus->object_name;
    }
    return $search_query;
  }
  
  private function retrieveTickets() {
    $tickets = array();
    
    try {
      $xml = $this->connection->get(ZENDESK_SEARCH, array(
        'query' => array(
          'query' => $this->getZendeskQuery(),
          'per_page' => $this->per_page,
          'page' => $this->page,
          'sort' => $this->sort,
          'order_by' => $this->order_by,
        )
      ));
    } catch (Exception $e) {
      if ($e->getMessage() == "Query too long") {
        $this->error_message = "Cannot display ticket information as there are too many contacts to search. Please set \"Zendesk Organization\" for this account.";
      } else {
        $this->error_message = $e->getMessage();
      }
      return $tickets;
    }

    if ($xml === false) {
      $this->error_message = "Could not connect to Zendesk " . $this->connection->url() . " using " . $this->connection->username;
    } else {
      libxml_use_internal_errors(true);
      try {
        $results = new SimpleXMLElement($xml);
  		} catch (Exception $e) {
        $this->error_message = "Unrecognized response from Zendesk " . $this->connection->url();
      }
      if ($this->error_message == null) {
        $this->result_count = $results->attributes()->count;
        $this->pages = ceil($this->result_count / $this->per_page);

        foreach ($results->record as $result) {
          $nice_id = $result->{'nice-id'};
          $ticket = new zd_Tickets_sugar();
          $ticket->retrieve_by_string_fields(array('nice_id' => $nice_id));
          $ticket->nice_id = $nice_id;
          $ticket->subject = $result->subject;
          $ticket->status = (int)$result->{'status-id'};
          $ticket->type = (int)$result->{'ticket-type-id'};
          $ticket->priority = (int)$result->{'priority-id'};
          $ticket->created_at = strftime('%F %T', strtotime($result->{'created-at'}));
          $ticket->updated_at = strftime('%F %T', strtotime($result->{'updated-at'}));
          $ticket->description = $result->description;
          $ticket->save();

          $tickets[] = $ticket;
        }
      }
    }

    return $tickets;
  }
  
	public function display()
	{
	  $zendesk_helper = new ZendeskHelper();
    
	  if ($this->error_message != null) {
      echo $this->error_message;
      echo " <a href='index.php?module=zd_Tickets&action=personalconfig&return_module={$this->focus->module_dir}&return_id={$this->focus->id}&return_action=DetailView'>My settings</a>";
    } else {
      $tickets = $this->retrieveTickets();
  	  if ($this->error_message != null) {
        echo $this->error_message;
      } else {
    	  global $timedate;

        $this->ss->assign("RETURN_MODULE", $this->focus->module_dir);
        $this->ss->assign("RETURN_ACTION", "DetailView");
        $this->ss->assign("RETURN_ID", $this->focus->id);

    	  $this->ss->assign('datetimefmt', $timedate->get_cal_date_time_format());
    	  $this->ss->assign('tickets', $this->retrieveTickets());
    	  $this->ss->assign('url', $this->connection->url() . "/search?query=" . urlencode($this->getZendeskQuery()));
    	  $this->ss->assign('base_url', $this->connection->url());
    	  $this->ss->assign('query', $this->getZendeskQuery());
    	  $this->ss->assign('count', $this->result_count);
    	  $this->ss->assign('from_count', ($this->per_page * $this->page - $this->per_page + 1));
    	  $this->ss->assign('to_count', min($this->per_page * $this->page, $this->result_count));
    	  $this->ss->assign('pages', $this->pages);

    	  $this->ss->assign('per_page', $this->per_page);
    	  $this->ss->assign('page', $this->page);
    	  $this->ss->assign('sort', $this->sort);
    	  $this->ss->assign('order_by', $this->order_by);
    	  $this->ss->assign('status_filter', $this->status_filter);
    	  $this->ss->assign('priority_filter', $this->priority_filter);
    	  $this->ss->assign('type_filter', $this->type_filter);
    
    	  $this->ss->assign('statusoptions', $zendesk_helper->getStatusFilterOptions());
    	  $this->ss->assign('priorityoptions', $zendesk_helper->getPriorityFilterOptions());
    	  $this->ss->assign('typeoptions', $zendesk_helper->getTypeFilterOptions());

        $this->ss->display('modules/zd_Tickets/tpls/associated.tpl');
      }
    }
  }
}
