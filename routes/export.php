<?php

$kirby->set('route', array(
  'pattern' => c::get('management.export', 'export'),
  'method' => 'POST',
  'action'  => function() {

    echo 'Will export something.';

  }
));
