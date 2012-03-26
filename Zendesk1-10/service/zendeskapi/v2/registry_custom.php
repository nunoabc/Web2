<?php

require_once('service/v2/registry.php');

class registry_custom extends registry
{
	public function __construct($serviceClass)
	{
		parent::__construct($serviceClass);
	}
	
	protected function registerFunction() 
	{
		parent::registerFunction();
		$this->serviceClass->registerFunction('crmdata', array('session'=>'xsd:string', 'email'=>'xsd:string'), array('return'=>'xsd:string'));
		$this->serviceClass->registerFunction('syncticket', array('session'=>'xsd:string', 'data'=>'xsd:string'), array('return'=>'xsd:string'));
		$this->serviceClass->registerFunction('version', array(), array('return'=>'xsd:string'));
	}
}