<?php
namespace Javamon\Jframe;

use \Javamon\Jframe\Core\Route as Route;

require_once __DIR__.'/vendor/autoload.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('memory_limit','512M');


$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$route = Route::getRequest();
