<?php

use Monolog\Logger;

$config = require __DIR__ . '/config.php';

// Used for generating links in API routes markdown.
$config['rest']['url'] = 'http://localhost/ims-api';

$config['monolog']['level'] = Logger::DEBUG;

return $config;
