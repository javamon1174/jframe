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
use \Javamon\Jframe\Core\Loader as Loader;
use \Javamon\Jframe\Core\Model as Model;
use \Javamon\Jframe\Core\ORM as ORM;
use Philo\Blade\Blade;

/**
 *  프로세서(컨트롤러) 클래스 : 프로세서 클래스의 부모 클래스 - 로더를 통해 기본 동작을 위한 클래스 변수에 객체 저장
 */
class Processor
{
    /**
     * @access protected
     * @var Array $config : 환경설정 값
     */
    protected $config;

    /**
     * @access protected
     * @var Object $load : 로더 객체
     */
    protected $load;

    /**
     * @access protected
     * @var Object $database : 모델 객체
     */
    protected $database;

    /**
     * @access protected
     * @var Object $view : 뷰 객체
     */
    protected $view;

    public function __construct()
    {
        empty($this->config) ? $this->config = (new Config())->configure() : false;
        empty($this->load) ? $this->load = new loader() : false;
        empty($this->model) ? $this->database = $this->load->model() : false;
        empty($this->view) ? $this->view = $this->load->view() : false;
    }
}
