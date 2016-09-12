<?php

require_once(__DIR__.'/../vendor/Loader.php');

$config = require_once(__DIR__.'/../App/config/config.php');

/**
 * output the error message depending on mode
 */

if ($config['mode'] === 'dev') {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}
else if ($config['mode'] === 'prod') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

if(empty($config['namespaces']) && !is_array($config['namespaces'])) {
    throw new Exception('Empty namespaces');
}
foreach ($config['namespaces'] as $namespace => $path) {
    Loader::addNamespacePath($namespace, realpath(__DIR__ . '/../' . $path));
}

$app = new Yurii\Application($config);

$app->run();