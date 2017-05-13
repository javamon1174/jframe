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
use \Javamon\Jframe\Core\Config as Config;

/**
 *  class static Route : 요청된 주소에 따라 로더로 맵핑된 데이터 전달
 */
class Route
{
    private static $config;

    //사용자 라우트 규칙 정의
    static public function route($request)
    {
        // $route['default'] = 'sample/test';
        $route['default'] = 'sample/sample';
        $route['main'] = 'sample/sample';
        $route['userpicture'] = 'sample/userpicture';

        $route['test/(:any)'] = 'sample/test/$1';
        $route['test/(:any)/(:any)'] = 'sample/test/$1/$2';

        return static::routing($request, $route);
    }

    /**
    * http 요청 매핑 및 라우팅
    * Parse Routes and Routing
    *
    * Matches any routes that may exist in the route rules.
    * against the URI to determine if the class/method need to be remapped.
    *
    * @param Array $request : 요청받은 페이지
    * @param Array $routes : 사용자 정의 라우트 규칙 / custom route rules
    * @return String $routes["class"] : 클래스 이름
    * @return String $routes["method"] : 함수 이름
    * @return Array $request : http-get 요청받은 데이터
    */
    static public function routing($request = array(), $routes = array())
    {
        $uri = URI;
        $mapping = true; // flag mapping

        // 사용자 정의 라우트 규칙 반복문 실행
        foreach ($routes as $key => $val)
        {
            // 라우트 규칙 체크
            if (is_array($val))
            {
                $val = array_change_key_case($val, CASE_LOWER);
                if (isset($val[$http_verb]))
                {
                    $val = $val[$http_verb];
                }
                else
                {
                    continue;
                }
            }

            // 와일드 카드 문자열 정규식으로 대체
            $key = str_replace(array(':any', ':num'), array('[^/]+', '[0-9]+'), $key);

            //사용자 정의 라우트 규칙 실행 => 참고 코드이그나이터 라우트 _parse_routes();
            if (preg_match('#^'.$key.'$#', $uri, $matches))
            {

                // 기본 라우트 역 참조 및 콜백 체크
                if ( ! is_string($val) && is_callable($val))
                {
                    // 배열 내 원래 문자열 값 제거
                    array_shift($matches);
                    // 함수 확인
                    $val = call_user_func_array($val, $matches);
                }
                elseif (strpos($val, '$') !== FALSE && strpos($key, '(') !== FALSE)
                {
                    $val = preg_replace('#^'.$key.'$#', $val, $uri);
                }

                $_temp = explode('/', $val);

                (empty($_temp[0])) ? null : $routes['class'] = '\Javamon\Jframe\Processor\\'.ucfirst($_temp[0]);
                (empty($_temp[1])) ? null : $routes['method'] = ucfirst($_temp[1]);
                $mapping = false;
            }
            // default 규칙 적용
            elseif ($key === "default" && empty($uri))
            {
                $_temp = explode('/', $val);

                (empty($_temp[0])) ? null : $routes['class'] = '\Javamon\Jframe\Processor\\'.ucfirst($_temp[0]);
                (empty($_temp[1])) ? null : $routes['method'] = ucfirst($_temp[1]);
                $mapping = false;
            }
            //조건에 맞지 않을 때, 내장 라우트 규칙 실행
            elseif ($mapping)
            {
                $routes['class'] = null;
                $routes['method'] = null;
            }
        }

        if (!empty($routes['method']))
        {
            array_shift($_temp);
            array_shift($_temp);
            $request = $_temp;
        }

        // 내장 라우트 규칙 실행 : class/method/data1/data2
        if (empty($routes['class'])
            || empty($routes['method']))
        {
            // 클래스 존재 확인
            if (file_exists(ROOT.'/src/application/'.ucfirst($request[0]).".php"))
            {
                $temp_class = '\Javamon\Jframe\Processor\\'.ucfirst($request[0]);

                //클래스내 함수 존재 여부 확인
                if (method_exists((new $temp_class),ucfirst($request[1])))
                {
                    //클래스와 함수에 매핑
                    $routes['class']= '\Javamon\Jframe\Processor\\'.ucfirst($request[0]);
                    $routes['method']= ucfirst($request[1]);
                    array_shift($request);array_shift($request);
                }
                else
                {
                    //클래스와 클래스 생성자에 매핑
                    $routes['class']= '\Javamon\Jframe\Processor\\'.ucfirst($request[0]);
                    $routes['method']= ucfirst($request[0]);
                    array_shift($request);
                }
            }
        }
        // echo "class : ".$routes['class']; echo "<br />method : ".$routes['method']; echo "<br /> data : ";var_dump($request); exit;

        $load = new loader();
        return $load->init($routes['class'], $routes['method'], $request);
    }

    static public function getRequest()
    {
        empty(static::$config) ? static::$config = (new Config())->configure() : null;

        $segment = filter_input(INPUT_GET, "url");

        // 마지막 문자열 체크 후 '/' 일때 제거 로직
        $string_last =  ( substr($segment, -1) );
        ($string_last === '/') ? $segment = substr($segment , 0, -1) : null;

        defined('NAMESPACE') or define('NAMESPACE', '\\Javamon\\Jframe\\Processor\\');
        defined('URI') or define('URI', $segment);

        $request = array();
        $request = explode("/",$segment);

        return static::route($request);
    }
}