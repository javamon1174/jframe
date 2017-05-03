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
namespace Javamon\Jframe\Core;

use \Javamon\Jframe\Core\Loader as Loader;

/**
 *  라우터 클래스 : 요청된 주소에 따라 로더로 데이터 전달
 */
class Route {

    static public function getRequest()
    {
        $segment = filter_input(INPUT_GET, "url");

        $url_array = array();
        $url_array = explode("/",$segment);

        $load = new loader();
        $load->init($url_array);
    }
}