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

/**
 *  프레임워크간의 관계 정의 및 객체생성 클래스
 */
class Loader
{
    /**
     * 기본 세팅값 대입 및 요청 된 클래스 및 함수 실행
     * @access public
     * @return Object $instance : 요청된 객체
     */
    public function init($class_name, $method, $data)
    {
        $instance = new $class_name();
        return $instance->$method($data);
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
