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

use \Javamon\Jframe\Core\Config as Config;

/**
 *  프레임워크간의 관계 정의 및 객체생성 클래스
 */
class Loader
{
    /**
     * @access private
     * @var String $class : 현재 클래스
     */
    private $class;
    /**
     * @access private
     * @var String $function : 현재 함수
     */
    private $function;

    /**
     * 기본 세팅값 대입 및 요청 된 클래스 및 함수 실행
     * @access public
     * @return Object $instance : 요청된 객체
     */
    public function init($arg = Array())
    {
        empty($this->config) ? $this->config = (new Config())->configure() : false;

        //default controller mapping
        empty($arg[0]) ? $arg[0]  = $this->config["default"]["controller"] : false;
        empty($arg[1]) ? $arg[1]  = $this->config["default"]["controller"] : false;

        $class_name = "\\Javamon\\Jframe\\Processor\\".ucfirst($this->config["default"]["controller"]);

        $instance = new $class_name();
        method_exists($instance,ucfirst($arg[0])) ? $construct =  true : $construct =  false ;

        if ($construct)
        {
            (!empty($arg[1])) ? $function_name = $arg[1] : $function_name = $arg[0];

            //remove class name and function name from arguments
            array_shift($arg);
            array_shift($arg);

            return $instance->$function_name($arg);
        }
        else
        {
            return trigger_error("The defined function can not be found : ".ucfirst($arg[0])."()", E_USER_ERROR);
        }
    }

    /**
     * 모델 로드 함수
     * @access public
     * @return Object $instance : 요청된 모델 객체
     */
    public function model($class = null)
    {
        //for query builder and orm
        (!empty($class))
        ? $class_name = "\\Javamon\\Jframe\\Model\\".ucfirst($class)
        : $class_name = "\\Javamon\\Jframe\\Core\\Model";

        return new $class_name();
    }

    /**
     * 컨트롤러(프로세서) 로드 함수
     * @access public
     * @return Object $instance : 요청된 컨트롤러(프로세서) 객체
     */
    public function controller($class = null)
    {
        $class_name = "\\Javamon\\Jframe\\Processor\\".ucfirst($class);
        return new $class_name();
    }

    /**
     * 공통 뷰 로드 함수
     * @access public
     * @return Object $instance : 공통 뷰 객체
     */
    public function view()
    {
        $class_name = "\\Javamon\\Jframe\\Core\\View";
        return new $class_name();
    }

    public function __construct() {}

}
