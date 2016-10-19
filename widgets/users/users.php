<?php
return array(
  'title' => 'Users Import/Export',
  'options' => array(
    array(
      'text' => 'Import',
      'icon' => 'upload',
      'link' => '#upload'
    ),
    array(
      'text' => 'Export',
      'icon' => 'download',
      'link' => 'users',
    )
  ),
  'html' => function() {
    return tpl::load(__DIR__ . DS . 'assets' . DS . 'users.css.php')
    . tpl::load(__DIR__ . DS . 'users.html.php', array(
      'text' => c::get('management.widget.message', 'Import new users from a CSV file or export them to save their infos.'),
      '$actionUrl' => u(c::get('management.import', 'import'))
    ))
    . tpl::load(__DIR__ . DS . 'assets' . DS . 'users.js.php');
  }
);
