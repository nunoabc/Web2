<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$admin = new Administration();
$admin->saveConfig();

header("Location: index.php?action={$_REQUEST['return_action']}&module={$_REQUEST['return_module']}&record={$_REQUEST['return_id']}");
