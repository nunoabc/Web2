<?php

$manifest = array (
  'acceptable_sugar_versions' => array(
    'regex_matches' => array (
      '6\\.[123]\\..*',
    ),
  ),
  'acceptable_sugar_flavors' => array('CE', 'PRO', 'CORP', 'ENT', 'ULT'),
  'readme' => '',
  'key' => 'zd',
  'author' => 'Zendesk',
  'description' => 'Zendesk for SugarCRM',
  'icon' => '',
  'is_uninstallable' => true,
  'name' => 'Zendesk',
  'published_date' => '2012-01-23',
  'type' => 'module',
  'version' => '1.10',
);

$installdefs = array (
  'id' => 'Zendesk',
  'beans' => 
  array (
    0 => 
    array (
      'module' => 'zd_Tickets',
      'class' => 'zd_Tickets',
      'path' => 'modules/zd_Tickets/zd_Tickets.php',
      'tab' => true,
    ),
  ),
  'layoutdefs' => 
  array (
  ),
  'relationships' => 
  array (
  ),
  'image_dir' => '<basepath>/icons',
  'copy' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/modules/zd_Tickets',
      'to' => 'modules/zd_Tickets',
    ),
    1 => 
    array (
      'from' => '<basepath>/service/zendeskapi/v2',
      'to' => 'custom/zendeskapi/v2',
    ),
    2 => 
    array (
      'from' => '<basepath>/basepath',
      'to' => '.',
    ),
    3 => array (
      'from' => '<basepath>/SugarModules/Extension',
      'to' => 'custom/Extension',
      ),
    ),
  'language' => 
  array (
    0 => 
    array (
      'from' => '<basepath>/SugarModules/language/application/en_us.lang.php',
      'to_module' => 'application',
      'language' => 'en_us',
    ),
  ),
);

$upgrade_manifest = array (
  'upgrade_paths' =>
  array (
    '1.6' =>
    array (
      'id' => 'Zendesk',
      'copy' => array (
        0 => 
        array (
          'from' => '<basepath>/SugarModules/modules/zd_Tickets',
          'to' => 'modules/zd_Tickets',
        ),
        1 => 
        array (
          'from' => '<basepath>/service/zendeskapi/v2',
          'to' => 'custom/zendeskapi/v2',
        ),
        2 => 
        array (
          'from' => '<basepath>/basepath',
          'to' => '.',
        ),
        3 =>
        array (
          'from' => '<basepath>/SugarModules/Extension',
          'to' => 'custom/Extension',
        ),
      ),
    ),
    '1.7' =>
    array (
      'id' => 'Zendesk',
      'copy' => array (
        0 => 
        array (
          'from' => '<basepath>/SugarModules/modules/zd_Tickets',
          'to' => 'modules/zd_Tickets',
        ),
        1 => 
        array (
          'from' => '<basepath>/service/zendeskapi/v2',
          'to' => 'custom/zendeskapi/v2',
        ),
        2 => 
        array (
          'from' => '<basepath>/basepath',
          'to' => '.',
        ),
        3 =>
        array (
          'from' => '<basepath>/SugarModules/Extension',
          'to' => 'custom/Extension',
        ),
      ),
    ),
    '1.8' =>
    array (
      'id' => 'Zendesk',
      'copy' => array (
        0 => 
        array (
          'from' => '<basepath>/SugarModules/modules/zd_Tickets',
          'to' => 'modules/zd_Tickets',
        ),
        1 => 
        array (
          'from' => '<basepath>/service/zendeskapi/v2',
          'to' => 'custom/zendeskapi/v2',
        ),
        2 => 
        array (
          'from' => '<basepath>/basepath',
          'to' => '.',
        ),
        3 =>
        array (
          'from' => '<basepath>/SugarModules/Extension',
          'to' => 'custom/Extension',
        ),
      ),
    ),
    '1.9' =>
    array (
      'id' => 'Zendesk',
      'copy' => array (
        0 => 
        array (
          'from' => '<basepath>/SugarModules/modules/zd_Tickets',
          'to' => 'modules/zd_Tickets',
        ),
        1 => 
        array (
          'from' => '<basepath>/service/zendeskapi/v2',
          'to' => 'custom/zendeskapi/v2',
        ),
        2 => 
        array (
          'from' => '<basepath>/basepath',
          'to' => '.',
        ),
        3 =>
        array (
          'from' => '<basepath>/SugarModules/Extension',
          'to' => 'custom/Extension',
        ),
      ),
    ),
  ),
);
