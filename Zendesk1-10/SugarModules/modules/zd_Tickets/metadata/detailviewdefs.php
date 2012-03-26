<?php

$module_name = 'zd_Tickets';
$_object_name = 'zd_tickets';
$viewdefs [$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'footerTpl'=>'modules/zd_Tickets/tpls/Comments.tpl',
        'buttons' => 
        array (
          0 => 'EDIT',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'requester_name',
            'label' => 'LBL_REQUESTER_NAME',
          ),
          1 => 
          array (
            'name' => 'cc_list',
            'label' => 'LBL_CC_LIST',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'status',
            'label' => 'LBL_STATUS',
          ),
          1 => 
          array (
            'name' => 'priority',
            'label' => 'LBL_PRIORITY',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'type',
            'label' => 'LBL_TYPE',
          ),
          1 => 
          array (
            'name' => 'due_date',
            'label' => 'LBL_DUE_DATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'group_name',
            'label' => 'LBL_GROUP_NAME',
          ),
          1 => 
          array (
            'name' => 'assignee_name',
            'label' => 'LBL_ASSIGNEE_NAME',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'organization_name',
            'label' => 'LBL_ORGANIZATION_NAME',
          ),
          1 => 
          array (
            'name' => 'created_at',
            'label' => 'LBL_CREATED_AT',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'updated_at',
            'label' => 'LBL_UPDATED_AT',
          ),
          1 => 
          array (
            'name' => 'assigned_at',
            'label' => 'LBL_ASSIGNED_AT',
          ),
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'solved_at',
            'label' => 'LBL_SOLVED_AT',
          ),
          1 => 
          array (
            'name' => 'tags',
            'label' => 'LBL_TAGS',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'score',
            'label' => 'LBL_SCORE',
          ),
          1 => 
          array (
            'name' => 'via',
            'label' => 'LBL_VIA',
          ),
        ),
        8 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
