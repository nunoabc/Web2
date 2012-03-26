<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$focus = new zd_Tickets();

$focus->retrieve($_REQUEST['record']);

foreach($focus->column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$focus->$field = $_REQUEST[$field];
	}
}

foreach($focus->additional_column_fields as $field)
{
	if(isset($_REQUEST[$field]))
	{
		$value = $_REQUEST[$field];
		$focus->$field = $value;
	}
}

if ($focus->pushToZendesk()) {
  echo "OK";
} else {
  echo $focus->getLastError();
}
