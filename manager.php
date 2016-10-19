<?php
/**
 * Import/Export Kirby users.
 *
 * @author Julien Gargot
 * @version 0.0.1
 */

require_once __DIR__.DS.'routes'.DS.'import.php';
require_once __DIR__.DS.'routes'.DS.'export.php';

$kirby->set('widget', 'users', __DIR__ . '/widgets/users');
