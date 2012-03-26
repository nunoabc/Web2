<?php

$dictionary['Lead']['fields']['zd_ticket_view'] =
array (
'name' => 'zd_ticket_view',
'vname' => 'LBL_ZENDESK_TICKETS',
'type' => 'html',
'function' => array('name'=>'getAssociatedTickets', 'returns'=>'html', 'include'=>'modules/zd_Tickets/TicketView.php'),
'len' => '6',
'comment' => '',
'source' => 'non-db',
'studio' => 'visible',
);
