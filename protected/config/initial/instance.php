<?php
 return array (
  'name' => 'Couponic',
  'components' => 
  array (
    'db' => 
    array (
      'connectionString' => 'mysql:host=localhost;dbname=myDatabase',
      'username' => 'myUsername',
      'password' => 'myPassword',
      'tablePrefix' => 'cpnc_',
    ),
    'urlManager' => 
    array (
      'urlFormat' => 'path',
      'showScriptName' => false,
      'rules' => 
      array (
        'page/<view>' => 'base/page',
        'deals/<url>' => 'deal/view',
      ),
    ),
  ),
  'theme' => 'classic',
);