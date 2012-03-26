<?PHP

require_once('include/SugarObjects/templates/basic/Basic.php');

class zd_Tickets_sugar extends Basic {
	var $new_schema = true;
	var $module_dir = 'zd_Tickets';
	var $object_name = 'zd_Tickets';
	var $table_name = 'zd_tickets';
	var $importable = false;

	var $id;
	var $name;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $modified_by_name;
	var $created_by;
	var $created_by_name;
	var $description;
	var $deleted;
	var $created_by_link;
	var $modified_user_link;
	var $assigned_user_id;
	var $assigned_user_name;
	var $assigned_user_link;
	var $zd_tickets_number;
	var $type;
	var $status;
	var $priority;
	var $system_id;
	var $work_log;
	var $nice_id;
	var $tags;
	var $requester_email;
	var $requester_name;

	var $requester_id;

  var $subject;
	var $created_at;
	var $updated_at;
	var $assigned_at;
	var $solved_at;
	var $via;
	var $score;
	var $due_date;
	var $assignee_id;
	var $assignee_name;
	var $group_id;
	var $group_name;
	var $organization_id;
	var $organization_name;
	var $cc_list;

  var $comments;
  var $fields;

	var $disable_row_level_security = true;
  
  var $can_update;
  var $can_edit_tags;
  var $can_comment;
  var $can_public_comment;

	function zd_Tickets_sugar() {
		parent::Basic();
	}
	
	function getPriority() {
  	global $app_list_strings;
    return $app_list_strings['priority_list'][$this->priority];
	}
	
	function getType() {
  	global $app_list_strings;
    return $app_list_strings['type_list'][$this->type];
	}
	
	function getStatus() {
  	global $app_list_strings;
    return $app_list_strings['status_list'][$this->status];
	}
	
	function getShortenedSubject() {
  	if (strlen($this->subject) == 0) {
  	  return "(no subject)";
  	} elseif (strlen($this->subject) < 35) {
  	  return $this->subject;
  	} else {
  	  return substr($this->subject, 0, 32) . '...';
  	}
	}
	
  public function descriptionLines() {
    return str_replace(array("\r\n", "\n", "\r"), '<br/>', $this->description);
  }
  
	function bean_implements($interface){
		switch($interface){
			case 'ACL': return true;
		}
	  return false;
  }

}
