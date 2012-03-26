<?php

class zd_TicketsController extends SugarController {

	public function action_save(){
	  if ($this->bean->pushToZendesk()) {
  		$id = $this->bean->save(!empty($this->bean->notify_on_save));
      $this->return_id = $id;
      $this->return_action = 'DetailView';
      $this->return_module = 'zd_Tickets';
    } else {
      $this->return_id = urlencode(preg_replace('/[\'"]/', '', $this->bean->getLastError()));
      $this->return_action = 'Error';
      $this->return_module = $this->bean->module_dir;
    }
	}

}
