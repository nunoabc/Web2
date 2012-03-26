<?php


require_once('service/core/SugarRestService.php');
require_once('custom/zendeskapi/v2/SugarRestServiceImpl_v2_custom.php');

class SugarRestService_v2_custom extends SugarRestService
{
	protected $implementationClass = 'SugarRestServiceImpl_v2_custom';
}

?>