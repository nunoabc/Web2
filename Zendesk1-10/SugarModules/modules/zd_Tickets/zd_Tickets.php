<?php

require_once('modules/zd_Tickets/zd_Tickets_sugar.php');
require_once('modules/zd_Tickets/ZendeskConnection.php');


class zd_Tickets extends zd_Tickets_sugar {
  
  var $connection;
  var $error_message;
  var $users;
  var $groups;
  
	function zd_Tickets() {
	  $this->users = array();
		parent::zd_Tickets_sugar();
    try {
  		$this->connection = getZendeskConnection();
		} catch (Exception $e) {
      $this->error_message = $e->getMessage();
    }
    $this->can_update = true;
    $this->can_edit_tags = true;
    $this->can_comment = true;
    $this->can_public_comment = true;
	}
	
	function initFromZendesk() {
	  $this->initGroups();
	  $this->initFields();
	}
	
	function isReadOnly() {
	  return $this->connection->read_only;
	}
	
	function isClosed() {
	  return $this->status == 4;
	}

  function retrieve($id = -1, $encode = true, $deleted = true) {
    $ret = parent::retrieve($id, $encode, $deleted);
	  $this->fetchFromZendesk();
    return $ret;
  }
  
  function load_from_db($id) {
    return parent::retrieve($id, true, true);
  }
  
  function getMacros() {
	  $xml = $this->connection->get('macros');

	  $ret = array();
    $macros = new SimpleXMLElement($xml);
    foreach ($macros->macro as $macro) {
      $ret[] = array('id' => $macro->id, 'title' => preg_replace('/::/', ' > ', $macro->title));
    }
    usort($ret, "titlecmp");
    return $ret;
  }
  
  function initGroups() {
	  $xml = $this->connection->get(ZENDESK_GROUPS);

    $this->groups = array();

    try {
      $groups = new SimpleXMLElement($xml);
    }
    catch (Exception $e) {
      return;
    }
    
    foreach ($groups->group as $group) {
      $this->groups[(int)$group->id] = array('name' => $group->name);
      $users = array();
      if ($group->users->user) {
        foreach ($group->users->user as $user) {
          $this->users[(int)$user->id] = $user->name;
          $users[] = (int)$user->id;
        }
      }
      $this->groups[(int)$group->id]['users'] = $users;
    }
  }
  
  function getUserName($user_id) {
    return $this->users[$user_id];
  }
  
  function getOrganizationName($org_id) {
    if ($org_id == 0) {
      return '';
    }
    
	  $xml = $this->connection->get(ZENDESK_ORGANIZATIONS, array('id' => $org_id));

    $organization = new SimpleXMLElement($xml);
    
    if ($organization->users->user) {
      foreach ($organization->users->user as $user) {
        $this->users[(int)$user->id] = $user->name;
      }
    }
    
    return $organization->name;
  }
  
  function initFields() {
    $xml = $this->connection->get('ticket_fields');
    
    $this->fields = array();
    
    try {
      $fields = new SimpleXMLElement($xml);
    }
    catch (Exception $e) {
      return;
    }
    
    foreach ($fields->record as $xml_field) {
      $field = array();
      $field['title'] = $xml_field->title;
      $field['required'] = ($xml_field->{'is-required'} == 'true');
      if (in_array($xml_field->type, array('FieldSubject', 'FieldDescription', 'FieldStatus', 'FieldPriority', 'FieldTicketType', 'FieldGroup', 'FieldAssignee'))) {
        $field_id = (string)$xml_field->type;
        $field['system'] = true;
      } else {
        $field_id = (string)$xml_field->id;
        $field['type'] = (string)$xml_field->type;
        $field['system'] = false;
        $field['regexp'] = (string)$xml_field->{'regexp-for-validation'};
        if ($field['type'] == 'FieldTagger') {
          $options = array();
          foreach ($xml_field->{'custom-field-options'}->{'custom-field-option'} as $val) {
            $options[(string)$val->value] = (string)$val->name;
          }
          $field['options'] = $options;
        } elseif ($field['type'] == 'FieldCheckbox') {
          $field['options'] = array('0' => 'No', '1' => 'Yes');
        }
      }
      $this->fields[$field_id] = $field;
    }
  }
  
  function getTicketData() {
    $ticket['details']['subject'] = $this->subject;
    $ticket['details']['set-tags'] = $this->tags;
    $ticket['details']['current-tags'] = $this->tags;
    $ticket['details']['status-id'] = $this->status ? $this->status : '0';
    $ticket['details']['ticket-type-id'] = $this->type ? $this->type : '0';
    if ($this->type == 4) {
      $ticket['details']['due-date'] = strftime('%FT%T', strtotime($this->due_date));
    }
    $ticket['details']['priority-id'] = $this->priority ? $this->priority : '0';
    $ticket['details']['group-id'] = $this->group_id;
    $ticket['details']['assignee-id'] = $this->assignee_id;
    $ticket['details']['ticket-field-entries'] = array();
    if (empty($this->nice_id)) {
      $ticket['details']['requester-name'] = $this->requester_name;
      $ticket['details']['requester-email'] = $this->requester_email;
      $ticket['details']['description'] = $this->description;
    } else {
      $ticket['id'] = $this->nice_id;
      $ticket['details']['comment']['is-public'] = $_REQUEST['comment_is_public'];
      $ticket['details']['comment']['value'] = $_REQUEST['comment_value'];
    }
    return $ticket;
  }
  
  function pushToZendesk() {
    $ticket['details']['subject'] = $this->subject;
    $ticket['details']['set-tags'] = $this->tags;
    $ticket['details']['current-tags'] = $this->tags;
    $ticket['details']['status-id'] = $this->status ? $this->status : '0';
    $ticket['details']['ticket-type-id'] = $this->type ? $this->type : '0';
    if ($this->type == 4) {
      $ticket['details']['due-date'] = strftime('%FT%T', strtotime($this->due_date));
    }
    $ticket['details']['priority-id'] = $this->priority ? $this->priority : '0';
    $ticket['details']['group-id'] = $this->group_id;
    $ticket['details']['assignee-id'] = $this->assignee_id;
    $ticket['details']['ticket-field-entries'] = array();
    foreach ($_REQUEST as $name => $value) {
      if (preg_match('/^custom_([0-9]+)$/', $name, $matches)) {
        $ticket['details']['ticket-field-entries'][] = array(
          'ticket-field-id' => $matches[1],
          'value' => $value,
        );
      }
    }
    if (empty($this->nice_id)) {
      // Create new ticket
  	  $GLOBALS['log']->error("Create Zendesk ticket");
  	  
  	  $req = explode('|', $this->requester_email);
  	  if (sizeof($req) > 1) {
  	    $this->requester_email = $req[0];
  	    $this->requester_name = $req[1];
  	  }

      $ticket['details']['requester-name'] = $this->requester_name;
      $ticket['details']['requester-email'] = $this->requester_email;
      $ticket['details']['description'] = $this->description;
      
      $result = $this->connection->create(ZENDESK_TICKETS, $ticket);
      if ($result) {
        $this->nice_id = $result;
      }
    } else {
      // Update ticket
      $GLOBALS['log']->error("Update Zendesk ticket " . $this->nice_id);
  	  
      $ticket['id'] = $this->nice_id;
      $ticket['details']['comment']['is-public'] = $_REQUEST['comment_is_public'] ? 'true' : 'false';
      $ticket['details']['comment']['value'] = $_REQUEST['comment_value'];
      
      $result = $this->connection->update(ZENDESK_TICKETS, $ticket);
    }
    return $result;
  }
  
  function getLastError() {
    $xml = $this->connection->last_result();
    
    libxml_use_internal_errors(true);
    try {
      $errors = new SimpleXMLElement($xml);
  	} catch (Exception $e) {
      return "Unrecognized result from Zendesk";
    }
        
    if (count($errors->error) > 0) {
      return $errors->error[0];
    }
    
    return "Unrecognized result from Zendesk";
  }
  
  function fetchFromZendesk() {
	  $GLOBALS['log']->error("Refetch ticket " . $this->nice_id);
	  
	  $this->initGroups();
	  if (!empty($this->nice_id)) {
	    $xml = $this->connection->get('tickets/' . $this->nice_id . '/events');

	    $comments = new SimpleXMLElement($xml);
  	  
      $this->comments = array();
      foreach ($comments->comment as $comment) {
        $this->comments[] = array(
          'author_id' => $comment->{'author-id'},
          'created_at' => strftime('%F %T', strtotime($comment->{'created-at'})),
          'is_public' => ($comment->{'is-public'} == 'true'),
          'type' => $comment->type,
          'value' => str_replace(array("\r\n", "\n", "\r"), '<br/>', $comment->value),
          'author_name' => $comment->author->name,
          'author_email' => $comment->author->email,
          'author_photo_url' => str_replace('http://', 'https://', $comment->author->{'photo-url'}),
        );
        $this->users[(int)$comment->{'author-id'}] = $comment->author->name;
      }
	    
  	  $xml = $this->connection->get(ZENDESK_TICKETS, array('id' => $this->nice_id));      
      
  	  $ticket = new SimpleXMLElement($xml);

      $this->subject = $ticket->subject;
      $this->name = '#' . $this->nice_id . ' \'' . $this->subject . '\'';

      $this->description = $ticket->description;
      $this->tags = $ticket->{'current-tags'};
      $this->cc_list = $ticket->{'current-collaborators'};
      $this->score = $ticket->score;

      $this->status = (int)$ticket->{'status-id'};
      $this->type = (int)$ticket->{'ticket-type-id'};
      $this->priority = (int)$ticket->{'priority-id'};
      $this->via = (int)$ticket->{'via-id'};

      $this->group_id = (int)$ticket->{'group-id'};
      $this->group_name = $this->groups[$this->group_id]['name'];

      $this->organization_id = (int)$ticket->{'organization-id'};
      $this->organization_name = $this->getOrganizationName($this->organization_id);

      $this->requester_id = (int)$ticket->{'requester-id'};
      $this->requester_name = array_key_exists($this->requester_id, $this->users) ? $this->users[$this->requester_id] : $this->requester_id;

      $this->assignee_id = (int)$ticket->{'assignee-id'};
      $this->assignee_name = array_key_exists($this->assignee_id, $this->users) ? $this->users[$this->assignee_id] : $this->assignee_id;

      $this->created_at = strftime('%F %T', strtotime($ticket->{'created-at'}));
      $this->updated_at = strftime('%F %T', strtotime($ticket->{'updated-at'}));
      $this->assigned_at = ($ticket->{'initially-assigned-at'} != '') ? strftime('%F %T', strtotime($ticket->{'initially-assigned-at'})) : null;
      $this->solved_at = ($ticket->{'solved-at'} != '') ? strftime('%F %T', strtotime($ticket->{'solved-at'})) : null;
      $this->due_date = ($this->type == 4) ? strftime('%F', strtotime($ticket->{'due-date'})) : null;

      $this->initFields();
      foreach ($ticket->{'ticket-field-entries'}->{'ticket-field-entry'} as $customfield) {
        $field_id = (int)$customfield->{'ticket-field-id'};
        if (array_key_exists($field_id, $this->fields)) {
          $this->fields[$field_id]['value'] = $customfield->value;
          if (array_key_exists('options', $this->fields[$field_id])) {
            $this->fields[$field_id]['display'] = $this->fields[$field_id]['options'][(string)$customfield->value];
          } else {
            $this->fields[$field_id]['display'] = preg_replace('/\n/', '<br/>', (string)$customfield->value);
          }
        }
      }
      
      $perm = $ticket->permissions;
      if ($perm) {
        $this->can_update = ($perm->{'can-update-ticket'} == 'true');
        $this->can_edit_tags = ($perm->{'can-edit-ticket-tags'} == 'true');
        //$this->can_comment = ($perm->{'can-make-comments'} == 'true');
        $this->can_comment = true;
        $this->can_public_comment = ($perm->{'can-make-public-comments'} == 'true');
      } else {
        $this->can_update = true;
        $this->can_edit_tags = true;
        $this->can_comment = true;
        $this->can_public_comment = true;
      }

      parent::save(false);
    }
  }
	
}

function titlecmp($a, $b) {
  return strcmp($a["title"], $b["title"]);
}
