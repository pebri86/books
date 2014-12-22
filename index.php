<?php
require_once 'src/Lib/Config.php';
\Lib\Config::setDirectory('../config');

$config = \Lib\Config::get('autoload');
require_once $config['class_path'] . '/Lib/Autoloader.php';

$route = null;
if (isset($_SERVER['REQUEST_URI'])) {
    $route = $_SERVER['REQUEST_URI'];
}

$router = new \Lib\Router();
$router->start($route);
?>