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

//Please set the basic environment. => application/core/Config.php

/**
 * 제이프레임워크 라우트로 HTTP 요청 전달
 * Forwarding HTTP requests to the jframework route.
 */
$route = Route::getRequest();
