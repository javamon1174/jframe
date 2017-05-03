<?php
/**
 * Javamon's JFramework
 *
 * PHP 컴포저 기반 제이프레임워크
 *
 * Created on 2017. 5.
 * @package      Javamon\Jframe
 * @category     Index
 * @license      http://opensource.org/licenses/MIT
 * @author       javamon <javamon1174@gmail.com>
 * @link         http://javamon.be/Jframe
 * @link         https://github.com/javamon1174/jframe
 * @version      0.0.1
 */
namespace Javamon\Jframe;

use \Javamon\Jframe\Core\Route as Route;

require_once __DIR__.'/vendor/autoload.php';

/**
 * 개발모드 php ini 임시 세팅
 * For Develop ini set.
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('memory_limit','512M');

/**
 * 컴포저 라이브러리 'Whoops' 실행
 * Excute Whoops php debugger in Composer.
 */
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/**
 * 제이프레임워크 라우트로 HTTP 요청 전달
 * Forwarding HTTP requests to the jframework route.
 */
$route = Route::getRequest();
